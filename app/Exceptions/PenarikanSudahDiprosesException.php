<?php

namespace App\Exceptions;

use RuntimeException;

class PenarikanSudahDiprosesException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Pengajuan penarikan ini sudah diproses sebelumnya.');
    }
}
