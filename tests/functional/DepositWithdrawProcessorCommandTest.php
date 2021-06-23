<?php

declare(strict_types=1);

namespace App\Tests\functional;

use PHPUnit\Framework\TestCase;

class DepositWithdrawProcessorCommandTest extends TestCase
{
    /**
     * @param array $inputData
     * @param array $expectedValues
     *
     * @dataProvider data
     */
    public function testData(array $inputData, array $expectedValues) {
        $fp = fopen('tests/mocks/file.csv', 'w');

        foreach ($inputData as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        $output=null;
        $retval=null;
        exec('php bin/console app:deposit_withdraw_processor_command tests/mocks/file.csv', $output, $retval);
        for($i = 0; $i < count($expectedValues); $i++) {
            self::assertEquals($expectedValues[$i], $output[$i]);
        }
    }

    public function data():array
    {
        return [
            'first' => [
                [
                    [
                        '2015-01-01',
                        '4',
                        'private',
                        'withdraw',
                        '1000.00',
                        'EUR'
                    ],
                    [
                        '2015-01-01',
                        '4',
                        'private',
                        'withdraw',
                        '1000.00',
                        'EUR'
                    ]
                ],
                [
                    '0.00',
                    '3.00'
                ]
            ],
        ];
    }

    public function shouldReturnFeeForWithdrawBusiness() {

    }

    public function shouldReturnFeeForWithdrawFor2PreviousWithdrawsInDifferentCurrency() {

    }

    public function shouldReturnFeeForWithdrawPrivateWith2PreviousWithdrawsExceedingThreshold() {

    }

    public function shouldReturnFreeFromFeeForWithdrawPrivateWith2PreviousWithdrawsNotExceedingThreshold() {

    }

    public function shouldReturnFeeForWithdrawPrivateWith3PreviousWithdraws() {

    }

    public function shouldReturnFeeForDeposit() {

    }

    public function shouldReturnFeeForDepositForMultipleUsersWithMultipleTransactions() {

    }
}