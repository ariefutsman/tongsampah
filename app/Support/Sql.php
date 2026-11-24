<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class Sql
{
    /**
     * Build a driver-aware date-format SQL expression.
     * $format uses MySQL DATE_FORMAT tokens (%Y, %m, %d, …) — the same tokens
     * strftime() uses for the subset of formats this app needs.
     */
    public static function dateFormat(string $column, string $format): string
    {
        return DB::connection()->getDriverName() === 'turso'
            ? "strftime('{$format}', {$column})"
            : "DATE_FORMAT({$column}, '{$format}')";
    }
}
