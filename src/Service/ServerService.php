<?php

namespace App\Service;


use App\Enum\Currency;
use App\Enum\HddType;
use App\Enum\SizeUnit;
use App\Repository\ServerRepository;

class ServerService
{
    public function __construct(private readonly ServerRepository $serverRepository)
    {

    }

    /**
     * @param array $filterParams
     * @return array
     */
    public function getAll(array $filterParams): array
    {
        if (count($filterParams) < 1) {
            return $this->serverRepository->findAll();
        } else {
            return $this->serverRepository->findByFilter($filterParams);
        }
    }

    /**
     * @param string $ramInfo
     * @return array
     *
     * Return Ram values as byte format
     * Example:
     */
    public function getRamInfo(string $ramInfo): array
    {
        foreach (SizeUnit::cases() as $unit) {
            if (str_contains($ramInfo, $unit->name)) {
                $ramData = explode($unit->name, $ramInfo);
                if (!is_numeric($ramData[0])) {
                    return [
                        'status' => false,
                        'data' => [],
                        'message' => 'Ram info data is not valid!'
                    ];
                }
                return [
                    'status' => true,
                    'data' => [
                        'size' => $ramData[0],
                        'actualSize' => $this->convertToBytes($ramData[0] . $unit->name),
                        'sizeType' => $unit->name,
                        'type' => $ramData[1],
                    ],
                    'message' => '',
                ];
            }
        }

        return [
            'status' => false,
            'data' => [],
            'message' => 'Ram info data is not valid!'
        ];
    }

    /**
     * @param string $hddInfo
     * @return array
     */
    public function getHddInfo(string $hddInfo): array
    {
        $hddInfoData = [];
        $hddInfo = explode('x', $hddInfo);
        $hddInfoData['count'] = $hddInfo[0];

        $actualUnit = '';
        foreach (HddType::cases() as $hddType) {
            if (str_contains($hddInfo[1], $hddType->name)) {
                $hddInfoData['type'] = $hddType->name;
            }
        }
        foreach (SizeUnit::cases() as $unit) {
            if (str_contains($hddInfo[1], $unit->name)) {
                $actualUnit = $unit->name;
                $hddInfoData['sizeType'] = $unit->name;
            }
        }

        if (empty($hddInfoData['type'])) {
            return [
                'status' => false,
                'data' => [],
                'message' => 'Hdd info data is not valid!'
            ];
        }
        if (empty($hddInfoData['sizeType'])) {
            return [
                'status' => false,
                'data' => [],
                'message' => 'Hdd info data is not valid!'
            ];
        }

        $sizeInfo = explode($actualUnit, $hddInfo[1]);
        $hddInfoData['size'] = $sizeInfo[0];
        $totalSize = $hddInfo[0] * $sizeInfo[0];
        $hddInfoData['totalSize'] = $totalSize;

        $actualSize = $this->convertToBytes($totalSize . $hddInfoData['sizeType']);
        $hddInfoData['actualSize'] = $actualSize;

        return [
            'status' => true,
            'data' => $hddInfoData,
            'message' => ''
        ];

    }

    /**
     * @param string $currencyInfo
     * @return array
     */
    public function getCurrencyInfo(string $currencyInfo): array
    {
        // Fix a type in server list file
        $currencyInfo = str_replace("S", "", $currencyInfo);

        $currencySymbol = mb_substr($currencyInfo, 0, 1);
        // Continue if it's not valid currency
        if (!Currency::tryFrom($currencySymbol)) {
            return [
                'status' => false,
                'data' => [],
                'message' => 'Currency data is not valid!',
            ];
        }
        $currencyData = explode($currencySymbol, $currencyInfo);
        return [
            'status' => true,
            'data' => [
                'price' => $currencyData[1],
                'currency' => Currency::tryFrom($currencySymbol)->name,
            ],
            'message' => '',
        ];

    }

    /**
     * @param string $from
     * @return int|null
     *
     * Convert the size unit to byte values.
     * Example: from 100MB to 102400
     */
    public function convertToBytes(string $from): ?int
    {
        $units = ['B', SizeUnit::MB->name, SizeUnit::GB->name, SizeUnit::TB->name, SizeUnit::PB->name];
        $number = substr($from, 0, -2);
        $suffix = strtoupper(substr($from, -2));

        //B or no suffix
        if (is_numeric(substr($suffix, 0, 1))) {
            return preg_replace('/[^\d]/', '', $from);
        }

        $exponent = array_flip($units)[$suffix] ?? null;
        if ($exponent === null) {
            return null;
        }

        $result = $number * (1024 ** $exponent);

        //1 TB multiply with 1024 but if value is 1000GB it's multiply with 1000
        return ($suffix == SizeUnit::TB->name) ? ($result / 1024) * 1000 : $result;
    }

    /**
     * @param array $filterParams
     * @return array
     *
     *  the query string params from ServerController.php and prepare them for filtering
     */
    public function getFormattedFilterParams(array $filterParams): array
    {
        // Remove empty query string values from array
        foreach ($filterParams as $field => $filterParam) {
            if (empty($filterParam)) {
                unset($filterParams[$field]);
            }
            if (is_numeric($filterParams)) {
                if ($filterParams < 1) {
                    unset($filterParams[$field]);
                }
            }
        }

        // Format Ram values as multiple choices
        if (isset($filterParams['ram'])) {
            $ramValues = explode(',', $filterParams['ram']);
            unset($filterParams['ram']);
            foreach ($ramValues as $ramValue) {
                // Find actual size unit and convert the Ram value to byte value to query in DB
                foreach (SizeUnit::cases() as $unit) {
                    if (str_contains($ramValue, $unit->name)) {
                        $ramData = explode($unit->name, $ramValue);
                        $filterParams['ramValues'][] = $this->convertToBytes($ramData[0] . $unit->name);
                    }
                }
            }
        }

        // Format storage values to query between condition in DB
        if (isset($filterParams['storage'])) {
            foreach (SizeUnit::cases() as $unit) {
                // Find actual size unit and convert the Storage value to byte value to query in DB
                if (str_contains($filterParams['storage'], $unit->name)) {
                    $storageData = explode($unit->name, $filterParams['storage']);
                    $filterParams['storage'] = $this->convertToBytes($storageData[0] . $unit->name);
                }
            }
        }

        return $filterParams;
    }


}
