<?php

declare(strict_types=1);

namespace App\Tests\functional;

use PHPUnit\Framework\TestCase;

class DepositWithdrawProcessorCommandTest extends TestCase
{
    /**
     * @test
     * @dataProvider data
     */
    public function shouldAssertFees(array $inputData, array $expectedValues): void
    {
        $this->writeToFile($inputData);

        $output=null;
        exec('php bin/console app:deposit_withdraw_processor_command tests/mocks/file.csv', $output);
        for($i = 0; $i < count($expectedValues); $i++) {
            self::assertEquals($expectedValues[$i], $output[$i]);
        }
    }

    private function writeToFile(array $inputData): void
    {
        $fp = fopen('tests/mocks/file.csv', 'w');

        foreach ($inputData as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);
    }

    public function data():array
    {
        return [
            'should return fee for withdraw business' => [
                [
                    [
                        '2016-01-06',
                        '2',
                        'business',
                        'withdraw',
                        '300.00',
                        'EUR'
                    ]
                ],
                [
                    '1.50'
                ]
            ],
            'shouldReturnFeeForPrivateWithdrawFor2PreviousWithdrawsInDifferentCurrency' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'withdraw',
                        '500',
                        'USD'
                    ],
                    [
                        '2016-01-06',
                        '1',
                        'private',
                        'withdraw',
                        '30000',
                        'JPY'
                    ],
                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '1000.00',
                        'EUR'
                    ]
                ],
                [
                    '0.00',
                    '0',
                    '1.35'
                ]
            ],
            'shouldReturnFeeForPrivateWithdrawInCurrentCurrency' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'withdraw',
                        '300000',
                        'JPY'
                    ],
                ],
                [
                    '450',
                ]
            ],
            'shouldReturnFreeFromFeeForWithdrawPrivateWith2PreviousWithdrawsNotExceedingThreshold' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'withdraw',
                        '100.00',
                        'EUR'
                    ],
                    [
                        '2016-01-05',
                        '1',
                        'private',
                        'withdraw',
                        '200.00',
                        'EUR'
                    ],
                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '300.00',
                        'EUR'
                    ]
                ],
                [
                    '0.00',
                    '0.00',
                    '0.00'
                ]
            ],
            'shouldReturnFeeForWithdrawPrivateWith3PreviousWithdrawsOneInMonday' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'withdraw',
                        '100.00',
                        'EUR'
                    ],
                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '200.00',
                        'EUR'
                    ],
                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '300.00',
                        'EUR'
                    ],

                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '100.00',
                        'EUR'
                    ]
                ],
                [
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.30'
                ]
            ],
            'shouldReturnFeeForWithdrawPrivateWith3PreviousWithdraws' => [
                [
                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '100.00',
                        'EUR'
                    ],
                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '200.00',
                        'EUR'
                    ],
                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '300.00',
                        'EUR'
                    ],

                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '100.00',
                        'EUR'
                    ]
                ],
                [
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.30'
                ]
            ],

            'shouldReturnFeeForDeposit' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'deposit',
                        '1000.00',
                        'EUR'
                    ]
                ],
                [
                    '0.30'
                ]
            ],
            'shouldReturnFeeForDepositForMultipleUsersWithMultipleTransactions' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'deposit',
                        '1000.00',
                        'EUR',

                    ],
                    [
                        '2016-01-04',
                        '2',
                        'private',
                        'deposit',
                        '1000.00',
                        'EUR',

                    ],
                    [
                        '2016-01-04',
                        '3',
                        'private',
                        'deposit',
                        '1000.00',
                        'EUR',

                    ],
                ],
                [
                    '0.30',
                    '0.30',
                    '0.30'
                ]
            ],
            'shouldReturnFeeForWithdrawForMultipleUsersWithMultipleTransactions' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'withdraw',
                        '100.00',
                        'EUR'
                    ],
                    [
                        '2016-01-04',
                        '2',
                        'private',
                        'withdraw',
                        '200.00',
                        'EUR'
                    ],
                    [
                        '2016-01-05',
                        '1',
                        'private',
                        'withdraw',
                        '200.00',
                        'EUR'
                    ],
                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '300.00',
                        'EUR'
                    ],

                    [
                        '2016-01-07',
                        '1',
                        'private',
                        'withdraw',
                        '100.00',
                        'EUR'
                    ]
                ],
                [
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.00',
                    '0.30'
                ]
            ],
            'shouldContinueWhenCurrencyIsNotANumber' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'withdraw',
                        'asd.00',
                        'EUR'
                    ],
                    [
                        '2016-01-04',
                        '2',
                        'private',
                        'withdraw',
                        '200.00',
                        'EUR'
                    ],
                ],
                [
                    'In row: 0, there is error: Operation currency amount is not a number',
                    '0.00'
                ]
            ],
            'shouldContinueWhenCurrencyIsLowerThan0' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'withdraw',
                        '-100.00',
                        'EUR'
                    ],
                    [
                        '2016-01-04',
                        '2',
                        'private',
                        'withdraw',
                        '200.00',
                        'EUR'
                    ],
                ],
                [
                    'In row: 0, there is error: Operation currency amount is lower than 0',
                    '0.00'
                ]
            ],
            'shouldContinueWhenCurrencyIsNotCorrect' => [
                [
                    [
                        '2016-01-04',
                        '1',
                        'private',
                        'withdraw',
                        '100.00',
                        'EUR123'
                    ],
                    [
                        '2016-01-04',
                        '2',
                        'private',
                        'withdraw',
                        '200.00',
                        'EUR'
                    ],
                ],
                [
                    "In row: 0, there is error: Value 'EUR123' is not part of the enum App\DepositWithdrawProcessor\Enums\Currency",
                    '0.00'
                ]
            ]
        ];
    }
}