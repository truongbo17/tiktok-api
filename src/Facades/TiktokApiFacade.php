<?php

namespace TruongBo\TiktokApi\Facades;

use Illuminate\Support\Facades\Facade;

class TiktokApiFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tiktok-api';
    }
}
