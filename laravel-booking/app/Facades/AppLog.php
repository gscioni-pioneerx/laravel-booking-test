<?php

namespace App\Facades;

use App\Services\AppLogService;
use Illuminate\Support\Facades\Facade;

class AppLog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AppLogService::class;
    }
}
