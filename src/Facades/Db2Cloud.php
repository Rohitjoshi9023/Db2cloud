<?php

namespace Db2Cloud\Facades;

use Illuminate\Support\Facades\Facade;

class Db2Cloud extends Facade{

    public static function getFacadeAccessor()
    {
        return "Db2Cloud";
    }

}