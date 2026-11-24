<?php

namespace App\Exceptions;

use RuntimeException;

class SaldoTidakCukupException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Saldo nasabah tidak mencukupi untuk penarikan ini.');
    }
}
