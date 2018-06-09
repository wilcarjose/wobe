<?php

namespace WJ\Facades;

use Illuminate\Support\Facades\Facade;

class Wobe extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wobe';
    }
}
