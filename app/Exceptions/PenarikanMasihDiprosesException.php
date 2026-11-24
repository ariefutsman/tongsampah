<?php

namespace App\Exceptions;

use RuntimeException;

class PenarikanMasihDiprosesException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Anda masih memiliki pengajuan penarikan yang belum diproses.');
    }
}
