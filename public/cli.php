<?php

use Website\Bootstrap\Cli;

if (true !== defined('APP_PATH')) {
    define('APP_PATH', dirname(dirname(__FILE__)));
}

date_default_timezone_set('Asia/Bangkok');

try {
    require_once APP_PATH . '/app/library/Bootstrap/_AbstractBootstrap.php';
    require_once APP_PATH . '/app/library/Bootstrap/Cli.php';

    /**
     * We don't want a global scope variable for this
     */
    (new Cli())->run();

} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL . $e->getTraceAsString();
}
