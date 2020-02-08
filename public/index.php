<?php
use Website\Bootstrap\Main;

if (true !== defined('APP_PATH')) {
    define('APP_PATH', dirname(dirname(__FILE__)));
}

define('START_TIME', microtime(true));

date_default_timezone_set('Asia/Bangkok');

try {
    //require_once APP_PATH . '/app/library/Bootstrap/AbstractBootstrap.php';
    require_once APP_PATH . '/vendor/autoload.php';
    require_once APP_PATH . '/app/library/Bootstrap/Main.php';

    (new Main())->run();

} catch (\SVCodebase\Validators\ValidateException $e) {
    $e->render();
} catch (\Phalcon\Mvc\Dispatcher\Exception $e){
    notFound($e);
} catch (PDOException $e) {
    handleError($e);
} catch (\Exception $e) {
    handleError($e);
}