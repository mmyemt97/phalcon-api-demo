<?php

namespace Website\Bootstrap;

use Composer\EventDispatcher\EventDispatcher;
use Dotenv\Dotenv;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Assets\Manager;
use Phalcon\Cache\Frontend\Data as PhCacheFrontData;
use Phalcon\Cache\Frontend\Output as PhCacheFrontOutput;
use Phalcon\Config as PhConfig;
use Phalcon\Cli\Console as PhCliConsole;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Di as PhDI;
use Phalcon\Di\FactoryDefault as PhFactoryDefault;
use Phalcon\Logger\Adapter\File as PhFileLogger;
use Phalcon\Logger\Formatter\Line as PhLoggerFormatter;
use Phalcon\Mvc\Application as PhApplication;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt as PhVolt;
use Phalcon\Registry as PhRegistry;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Exception;
use SVCodebase\Library\Utils;
use Website\View\Engine\Volt\Extensions\Php;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Http\Response\Cookies;
use Phalcon\Mvc\Model\MetaData\Session;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;

/**
 * AbstractBootstrap
 *
 * @property PhDI $diContainer
 */
abstract class AbstractBootstrap
{
    /**
     * @var null|PhMicro|PhCliConsole
     */
    protected $application = null;

    /**
     * @var null|PhDI
     */
    protected $diContainer = null;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Runs the application
     *
     * @return PhApplication
     */
    public function run()
    {
        $this->initOptions();
        $this->initDi();
        $this->initLoader();
        $this->initRegistry();
        $this->initEnvironment();
        $this->initConfig();
        $this->initApplication();
        $this->initUtils();

//        $this->initCache();
        //$this->initLogger();
        //$this->initErrorHandler();
        $this->initUrl();
        $this->initRoutes();
        $this->initDB();
        $this->initDispatcher();
        //$this->initSession();
        //$this->initCookies();
        $this->initView();
        $this->initDocs();

        $this->initMiddleware();
        $this->initFratal();
        $this->initAuth();

        return $this->runApplication();
    }

    /**
     * Initializes the application
     */
    protected function initApplication()
    {
        $this->application = new PhApplication($this->diContainer);
    }

    /**
     * Initializes the Cache
     */
    protected function initCache()
    {
        /**
         * viewCache
         */
        /** @var \Phalcon\Config $config */
        $config = $this->diContainer->getShared('config');
        $lifetime = $config->get('cache')->get('lifetime', 3600);
        $driver = $config->get('cache')->get('viewDriver', 'file');
        $frontEnd = new PhCacheFrontOutput(['lifetime' => $lifetime]);
        $backEnd = ['cacheDir' => APP_PATH . '/storage/cache/view/'];
        $class = sprintf('\Phalcon\Cache\Backend\%s', ucfirst($driver));
        $cache = new $class($frontEnd, $backEnd);

        $this->diContainer->set('viewCache', $cache);

        /**
         * cacheData
         */
        $driver = $config->get('cache')->get('driver', 'file');
        $frontEnd = new PhCacheFrontData(['lifetime' => $lifetime]);
        $backEnd = ['cacheDir' => APP_PATH . '/storage/cache/data/'];
        $class = sprintf('\Phalcon\Cache\Backend\%s', ucfirst($driver));
        $cache = new $class($frontEnd, $backEnd);

        $this->diContainer->setShared('cacheData', $cache);
    }

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
     * Initializes the Di container
     */
    protected function initDi()
    {
        $this->diContainer = new PhFactoryDefault();
        PhDI::setDefault($this->diContainer);
    }

    /**
     * Initializes the Dispatcher
     */
    protected function initDispatcher()
    {
//        $this->diContainer->setShared('dispatcher', function() {
//            $dispatcher = new Dispatcher();
//            $dispatcher->setDefaultNamespace('Website\Controllers\\');
//
//            $events_manager = new EventsManager();
//            $events_manager->attach('dispatch', function($event, $dispatcher, $exception) {
//                $debug = false;
//                $type = $event->getType();
//                if ($type == 'beforeException') {
//                    if (!$debug) {
//                        $dispatcher->forward(array(
//                            'module' => 'admin',
//                            'controller' => 'index',
//                            'action' => 'error404'
//                        ));
//                        return false;
//
//                    }
//                }
//            });
//
//            $dispatcher->setEventsManager($events_manager);
//            return $dispatcher;
//        });
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
     * Initializes the error handlers
     */
    protected function initErrorHandler()
    {
        $registry = $this->diContainer->getShared('registry');
        $logger = $this->diContainer->getShared('logger');
        $utils = $this->diContainer->getShared('utils');

        ini_set(
            'display_errors',
            boolval('development' === $registry->mode)
        );
        error_reporting(E_ALL);

        set_error_handler(
            function ($errorNumber, $errorString, $errorFile, $errorLine) use ($logger, $registry) {
                if (0 === $errorNumber & 0 === error_reporting()) {
                    return;
                }

                if('development' === $registry->mode){
                    throw new \Exception($errorString . ' - ' . $errorLine . ' - ' .$errorString);
                }else{
                    $logger->error(
                        sprintf(
                            "[%s] [%s] %s - %s",
                            $errorNumber,
                            $errorLine,
                            $errorString,
                            $errorFile
                        )
                    );
                }
            }
        );

        set_exception_handler(
            function () use ($logger, $registry) {
                if('development' === $registry->mode){
                    throw new \Exception(json_encode(debug_backtrace()));
                }else{
                    $logger->error(json_encode(debug_backtrace()));
                }
            }
        );

        register_shutdown_function(
            function () use ($logger, $utils, $registry) {
                $memory = memory_get_usage() - $registry->memory;
                $execution = microtime(true) - $registry->executionTime;

                if ('development' === $registry->mode) {
                    $logger->info(
                        sprintf(
                            'Shutdown completed [%s] - [%s]',
                            $utils->timeToHuman($execution),
                            $utils->bytesToHuman($memory)
                        )
                    );
                }
            }
        );
    }

    /**
     * Initializes the autoloader
     */
    protected function initLoader()
    {
        /**
         * Use the composer autoloader
         */
        require_once APP_PATH . '/vendor/autoload.php';
    }

    /**
     * Initializes the loggers
     */
    protected function initLogger()
    {
        /** @var \Phalcon\Config $config */
        $config = $this->diContainer->getShared('config');
        $fileName = $config->get('logger')
            ->get('defaultFilename', 'application');
        $format = $config->get('logger')
            ->get('format', '[%date%][%type%] %message%');

        $logFile = sprintf(
            '%s/storage/logs/%s-%s.log',
            APP_PATH,
            date('Ymd'),
            $fileName
        );
        $formatter = new PhLoggerFormatter($format);
        $logger = new PhFileLogger($logFile);
        $logger->setFormatter($formatter);

        $this->diContainer->setShared('logger', $logger);
    }

    /**
     * Initializes the options
     */
    protected function initOptions()
    {
    }

    /**
     * Initializes the registry
     */
    protected function initRegistry()
    {
        /**
         * Fill the registry with elements we will need
         */
        $registry = new PhRegistry();
        $registry->contributors = [];
        $registry->executionTime = 0;
        $registry->language = 'en';
        $registry->imageLanguage = 'en';
        $registry->memory = 0;
        $registry->menuLanguages = [];
        $registry->noindex = false;
        $registry->slug = '';
        $registry->releases = [];
        $registry->version = '3.0.0';
        $registry->view = 'index/index';
        $registry->mode = 'development';

        $this->diContainer->setShared('registry', $registry);
    }

    /**
     * Initializes the url
     */
    protected function initUrl()
    {
        $url = new Url();
        $url->setBaseUri(getenv('APP_URL'));

        $this->diContainer->setShared('url', $url);
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
        foreach ($arrRouter as $router_name => $item) {
            $namespace = 'Website\Controllers';
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
        $router->notFound(array(
            'module' => 'admin',
            'controller' => 'index',
            'action' => 'error404'
        ));
        $router->removeExtraSlashes(true);
        $this->diContainer->setShared('router', $router);
    }

    /**
     * Initializes the utils service and stores it in the DI
     */
    protected function initUtils()
    {
        $this->diContainer->setShared('utils', new Utils());
    }

    /**
     * Initializes the View services and Volt
     */
    protected function initView()
    {
        /** @var \Phalcon\Registry $registry */
        $registry = $this->diContainer->getShared('registry');
        $options = [
            'compiledPath' => APP_PATH . '/storage/cache/volt/',
            'compiledSeparator' => '_',
            'compiledExtension' => '.php',
            'compileAlways' => boolval('development' === $registry->mode),
            'stat' => true,
        ];

        $view = new View();
        $view->setViewsDir(APP_PATH . '/app/views/');
        $view->registerEngines(
            [
                '.phtml' => function ($view) use ($options) {
                    $volt = new PhVolt($view, $this->diContainer);
                    $volt->setOptions($options);

                    /**
                     * Register the PHP extension, to be able to use PHP
                     * functions in Volt
                     */

                    $volt->getCompiler()->addExtension(new Php());

                    return $volt;
                },
            ]
        );

        $this->diContainer->setShared('view', $view);
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

    protected function initSession()
    {
        $flash = new \Phalcon\Flash\Session();
        $this->diContainer->setShared('flash', $flash);

        $session = new SessionAdapter();
        $session->start();
        $this->diContainer->setShared('session', $session);
    }

    protected function initCookies()
    {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        $this->diContainer->setShared('cookies', $cookies);
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


    /**
     * booting middleware to usage in controller
     */
    protected function initMiddleware()
    {
        $this->diContainer->setShared('middleware', function () {
            return new \SVCodebase\Middleware\Manager();
        });
    }

    /**
     * create service fractal to tranform API
     */
    protected function initFratal()
    {
        $this->diContainer->setShared('fractal',function (){
            return new \Spatie\Fractalistic\Fractal(new \League\Fractal\Manager());
        });
    }

    /**
     * create service Auth
     */
    protected function initAuth()
    {
        $this->diContainer->setShared('auth',function (){
            return new \SVCodebase\Library\Acl\Auth();
        });
    }

    /**
     * Runs the main application
     *
     * @return PhApplication
     */
    protected function runApplication()
    {
        return $this->application->handle()->send();
    }
}
