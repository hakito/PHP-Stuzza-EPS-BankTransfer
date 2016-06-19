<?php

if (file_exists('vendor/autoload.php'))
    require_once 'vendor/autoload.php';
else if (file_exists('../../autoload.php'))
    require_once '../../autoload.php';
else
    echo "No autoload file found.";
