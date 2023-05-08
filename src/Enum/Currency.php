<?php

namespace App\Enum;

enum Currency: string
{
    case POUND = '£';
    case EURO = '€';
    case DOLLAR = '$';

    /**
     * @param string $name
     * @return Currency
     *
     * Find the enum according to the string name
     * Example: $name:"EURO" return: EURO enum
     */
    public static function fromName(string $name): Currency
    {
        foreach (self::cases() as $currency) {
            if($name === $currency->name ) {
                return $currency;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class);
    }
}
