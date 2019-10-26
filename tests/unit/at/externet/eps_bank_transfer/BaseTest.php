<?php

namespace at\externet\eps_bank_transfer;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    public static function GetEpsData($filename)
    {
        return file_get_contents(self::GetEpsDataPath($filename));
    }

    public static function GetEpsDataPath($filename)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'EpsData' . DIRECTORY_SEPARATOR . $filename;
    }
}
