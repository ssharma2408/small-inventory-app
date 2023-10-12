<?php

namespace App\Services;

interface CdnService
{
    public function purge($fileName);
}