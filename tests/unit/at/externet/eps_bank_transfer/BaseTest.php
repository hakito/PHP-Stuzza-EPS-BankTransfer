<?php

namespace at\externet\eps_bank_transfer;
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'autoloader.php';

class BaseTest extends \PHPUnit_Framework_TestCase
{
    public function GetEpsData($filename)
    {
        return file_get_contents($this->GetEpsDataPath($filename));
    }

    public function GetEpsDataPath($filename)
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'EpsData' . DIRECTORY_SEPARATOR . $filename;
    }
}