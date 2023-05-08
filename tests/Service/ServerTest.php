<?php

namespace App\Tests\Service;

use App\Enum\Currency;
use App\Service\ServerService;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ServerTest extends KernelTestCase
{
    private ServerService $serverService;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        // Create an instance of ServerService
        $this->serverService = static::getContainer()->get(ServerService::class);
    }

    #[NoReturn] public function testCurrencyInfo(): void
    {
        $value = "$387.79";

        $result = $this->serverService->getCurrencyInfo($value);

        // Define the expected result array
        $expectedResult = [
            'status' => true,
            'data' => [
                'price' => "387.79",
                'currency' => Currency::DOLLAR->name,
            ],
            'message' => '',
        ];

        // Assert that the result array is the same as the expected array
        $this->assertSame($expectedResult, $result);
    }

    #[NoReturn] public function testHddInfo(): void
    {
        $value = "2x120GBSSD";

        $result = $this->serverService->getHddInfo($value);

        // Define the expected result array
        $expectedResult = [
            'status' => true,
            'data' => [
                'count' => '2',
                'type' => 'SSD',
                'sizeType' => 'GB',
                'size' => '120',
                'totalSize' => 240,
                'actualSize' => 251658240,
            ],
            'message' => '',
        ];

        // Assert that the result array is the same as the expected array
        $this->assertSame($expectedResult, $result);
    }

    #[NoReturn] public function testRamInfo(): void
    {
        $value = "128GBDDR4";

        $result = $this->serverService->getRamInfo($value);

        // Define the expected result array
        $expectedResult = [
            'status' => true,
            'data' => [
                'size' => '128',
                'actualSize' => 134217728,
                'sizeType' => 'GB',
                'type' => 'DDR4'
            ],
            'message' => '',
        ];

        // Assert that the result array is the same as the expected array
        $this->assertSame($expectedResult, $result);
    }

    #[NoReturn] public function testConvertToBytes(): void
    {
        $value = "5GB";

        $result = $this->serverService->convertToBytes($value);

        // Define the expected result
        $expectedResult = 5242880;

        // Assert that the result is the same as the expected
        $this->assertSame($expectedResult, $result);
    }

    #[NoReturn] public function testGetFormattedFilterParams(): void
    {
        $value = [
            'ram' => '32GB',
            'storage' => '1TB',
            'hddType' => 'SATA',
            'location' => 'Hong KongHKG-10'
        ];

        $result = $this->serverService->getFormattedFilterParams($value);

        // Define the expected result array
        $expectedResult = [
            'status' => true,
            'data' => [
                'storage' => 1073741824,
                'hddType' => 'SATA',
                'location' => 'Hong KongHKG-10',
                'ramValues' => [33554432]
            ],
            'message' => '',
        ];

        // Assert that the result array is the same as the expected array
        $this->assertSame($expectedResult, $result);
    }
}
