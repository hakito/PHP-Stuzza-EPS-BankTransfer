<?php

spl_autoload_register(function ($className)
{
    $fileName = __DIR__ . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $className).".php";

	if (file_exists($fileName)) {
		require_once $fileName;
	}
}
);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'org' . DIRECTORY_SEPARATOR . 'cakephp' . DIRECTORY_SEPARATOR . 'basics.php';