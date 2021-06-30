<?php

declare(strict_types=1);

namespace App\DepositWithdrawProcessor\Input;

use App\DepositWithdrawProcessor\Enums\Currency;
use App\DepositWithdrawProcessor\Enums\DepositType;
use App\DepositWithdrawProcessor\Enums\UserType;
use App\DepositWithdrawProcessor\Input\Exception\CannotConvertDateTimeException;
use App\DepositWithdrawProcessor\Input\Exception\CannotParseToEnumException;
use App\DepositWithdrawProcessor\Input\Exception\StreamOpenFailedException;
use App\DepositWithdrawProcessor\Model\UserOperationDTO;
use App\SharedKernel\Number\ExchangeableNumberFactory;
use DateTime;
use Exception;
use UnexpectedValueException;

class CsvInputHandler implements InputHandler
{
    private ExchangeableNumberFactory $exchangeableNumberFactory;
    private array $currentRow;
    private int $currentPosition;
    private $handle;
    private string $streamName;

    /**
     * @throws StreamOpenFailedException
     */
    public function __construct(ExchangeableNumberFactory $exchangeableNumberFactory, string $streamName)
    {
        $this->exchangeableNumberFactory = $exchangeableNumberFactory;
        $this->streamName = $streamName;
        $this->rewind();
    }

    /**
     * @throws StreamOpenFailedException
     */
    public function rewind(): void
    {
        if ($this->handle !== false && $this->handle !== null) {
            fclose($this->handle);
        }
        if (!file_exists($this->streamName)) {
            throw new StreamOpenFailedException(sprintf('File not found: %s', $this->streamName));
        }
        $this->handle = fopen($this->streamName, 'rb');
        if ($this->handle === false) {
            throw new StreamOpenFailedException(sprintf('File cannot be open: %s', $this->streamName));
        }
        $this->currentPosition = 0;
        $this->currentRow = fgetcsv($this->handle);
    }

    /**
     * @throws CannotConvertDateTimeException
     * @throws CannotParseToEnumException
     */
    public function current(): UserOperationDTO
    {
        try {
            $datetime = new DateTime($this->currentRow[0]);
        } catch (Exception $e) {
            throw new CannotConvertDateTimeException($e->getMessage(), $e->getCode(), $e);
        }
        try {
            $userType = UserType::from(strtoupper($this->currentRow[2]));
            $depositType = DepositType::from(strtoupper($this->currentRow[3]));
            $currentCurrency = Currency::from(strtoupper($this->currentRow[5]));
        } catch (UnexpectedValueException $e) {
            throw new CannotParseToEnumException($e->getMessage(), $e->getCode(), $e);
        }

        return new UserOperationDTO(
            $datetime,
            (int) $this->currentRow[1],
            $userType,
            $depositType,
            $this->exchangeableNumberFactory->create($this->currentRow[4], $currentCurrency),
            $currentCurrency
        );
    }

    public function next(): void
    {
        ++$this->currentPosition;
        $row = fgetcsv($this->handle);
        if ($row === false) {
            $row = [];
        }
        $this->currentRow = $row;
    }

    public function key(): int
    {
        return $this->currentPosition;
    }

    public function valid(): bool
    {
        return !empty($this->currentRow);
    }
}
