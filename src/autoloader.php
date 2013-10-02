<?php

spl_autoload_register(function ($className)
{
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $className).".php";

	if (file_exists($fileName)) {
		require_once $fileName;
	}
}
);

require_once join(DIRECTORY_SEPARATOR, array(__DIR__, 'org', 'cakephp', 'basics.php'));
require_once join(DIRECTORY_SEPARATOR, array(__DIR__, 'at', 'externet', 'eps_bank_transfer', 'functions.php'));