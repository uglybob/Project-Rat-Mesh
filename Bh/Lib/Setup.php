<?php

namespace Bh\Lib;

class Setup
{
    protected static $settings = [
        'EnableRegistration' => false,
        'DevMode' => true,
        'MapperPath' => 'Bh/Mapper',
        'DbHost' => 'localhost',
        'DbName' => 'prm_database',
        'DbUser' => 'prm_user',
        'DbPass' => 'prm_pass',
    ];

    public static function getSettings()
    {
        return self::$settings;
    }
}
