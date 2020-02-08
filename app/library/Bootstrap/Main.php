<?php

namespace Website\Bootstrap;

use Dotenv\Dotenv;
use Phalcon\Config as PhConfig;
use Phalcon\Mvc\Router;
use Phalcon\Config\Adapter\Ini;

/**
 * Bootstrap
 */
class Main extends \SVCodebase\Library\AbstractBootstrap
{

    protected function initOptions()
    {
        parent::initOptions();

        $this->options['init']['ErrorHandler']['error_handler'] = 'handleError';
    }

    /**
     * This class is empty since all the initialization happens in the Abstract
     * class. The CLI/Test bootstrap classes will override the bootstrap
     * sequence
     */
    /**
     * Initializes the Config container
     *
     * @throws Exception
     */
    protected function initConfig()
    {
        $fileName = APP_PATH . '/app/config/config.php';
        if (true !== file_exists($fileName)) {
            throw new \Exception('Configuration file not found');
        }

        $configArray = require_once($fileName);
        $config = new PhConfig($configArray);
        $this->diContainer->setShared('config', $config);
    }

    /**
     * Initializes the environment
     */
    protected function initEnvironment()
    {
        /** @var \Phalcon\Registry $registry */
        $registry = $this->diContainer->getShared('registry');
        $registry->memory = memory_get_usage();
        $registry->executionTime = microtime(true);

        (new Dotenv(APP_PATH))->load();
        $mode = getenv('APP_ENV');
        $mode = (false !== $mode) ? $mode : 'development';

        $registry->mode = $mode;
    }

    /**
     * Initializes the autoloader
     */
    protected function initLoader()
    {
        /**
         * Use the composer autoloader
         */
//        require_once APP_PATH . '/vendor/autoload.php';
    }

    /**
     * Initializes the routes
     */
    protected function initRoutes()
    {
        $fileName = APP_PATH . '/app/config/router.ini';
        if (true !== file_exists($fileName)) {
            throw new \Exception('Router file not found');
        }

        $arrRouter = new Ini($fileName);

        $router = new Router();
        $namespace = 'Website\Controllers';

        $router->notFound(array(
            //'namespace' => $namespace,
            //'module' => 'index',
            'controller' => 'index',
            'action' => 'error404'
        ));
        $router->removeExtraSlashes(true);

        foreach ($arrRouter as $router_name => $item) {
            $controller = strtolower($item->controller);
            $methods = explode(",", str_replace(" ", "", $item->methods));
            foreach ($methods as $method) {
                $method = 'add' . ucfirst(strtolower($method));
                $router->{$method}($item->pattern, array(
                    'namespace' => $namespace,
                    'controller' => $controller,
                    'action' => $item->action,
                    'authorization' => isset($item->authorization) ? $item->authorization : 0
                ))->setName($router_name);
            }
        }

        $this->diContainer->setShared('router', $router);
    }

    protected function initDB()
    {
        $config = $this->diContainer->getShared('config');
        $dbConfig = $config->db->toArray();
        $adapter = $dbConfig['adapter'];
        unset($dbConfig['adapter']);
        $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;
        $this->diContainer->setShared('db', new $class($dbConfig));

        $dbSlaveConfig = $config->db_slave->toArray();
        $adapter = $dbSlaveConfig['adapter'];
        unset($dbSlaveConfig['adapter']);
        $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;
        $this->diContainer->setShared('db_slave', new $class($dbConfig));
    }

    /**
     * Configure Swagger
     */
    protected function initDocs()
    {
        /** @var PhConfig $config */
        $config = $this->diContainer->getShared('config');
        $this->diContainer->setShared(
            'swagger',
            function () use ($config) {
                return $config->get('swagger')->toArray();
            }

        );
    }

    // protected function initEvents()
    // {
        // if (getenv('APP_DEBUG') == true && class_exists(\SVDebugger\DebuggerManager::class)) {
        //     \SVDebugger\DebuggerManager::build($this->application);
        // }
    // }
}
