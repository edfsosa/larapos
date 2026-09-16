<?php

namespace App\Support;

class Money
{
    /**
     * Formatea un monto entero en guaraníes.
     * Ej: 415000 → "Gs. 415.000"
     */
    public static function format(int|float|string|null $amount): string
    {
        return 'Gs. ' . self::formatNumber($amount);
    }

    /**
     * Sin el prefijo. Ej: 415000 → "415.000"
     */
    public static function formatNumber(int|float|string|null $amount): string
    {
        return number_format((int) $amount, 0, ',', '.');
    }
}
