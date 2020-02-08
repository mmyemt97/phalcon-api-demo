<?php
$isHttps = isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https';

return [
    'app'           => [
        'debug'           => getenv('APP_DEBUG'),
        'env'             => getenv('APP_ENV')
    ],
    'db'            => [
        'adapter'         => getenv('DB_ADAPTER'),
        'host'            => getenv('DB_HOST'),
        'username'        => getenv('DB_USERNAME'),
        'password'        => getenv('DB_PASSWORD'),
        'dbname'          => getenv('DB_NAME'),
        'charset'         => getenv('DB_CHARSET'),
    ],
    'db_slave'            => [
        'adapter'         => getenv('DB_ADAPTER'),
        'host'            => getenv('DB_HOST'),
        'username'        => getenv('DB_USERNAME'),
        'password'        => getenv('DB_PASSWORD'),
        'dbname'          => getenv('DB_NAME'),
        'charset'         => getenv('DB_CHARSET'),
    ],
    'logger'        => [
        'defaultFilename' => getenv('LOGGER_DEFAULT_FILENAME'),
        'format'          => getenv('LOGGER_FORMAT'),
        'level'           => getenv('LOGGER_LEVEL'),
    ],
    'redis'         => [
        'option'          => getenv('REDIS_OPTION'),
        'host'            => [
            getenv('REDIS_HOST_1')
        ],
        'expired'   => getenv('REDIS_EXPIRED_DEFAULT')
    ],
    'swagger' => [
        'path'        => APP_PATH.'/app/',
        'host'        => "",
        'schemes'     => $isHttps ? ['https', 'http'] : ['http', 'https'],
        'basePath'    => '/',
        'version'     => '1.0.0',
        'title'       => 'Plan API',
        'description' => '', // Always set it null plz
        'email'       => '',
        'jsonUri'     => '/plan/api/doc',
    ],
    'version_cache' => getenv('APP_VERSION_CACHE'),
    'cdn' => [
        'vl24h' => getenv('URL_CDN_VL24H'),
        'tvn' => getenv('URL_CDN_TVN'),
        'vtn' => getenv('URL_CDN_VTN'),
    ],

    'jwt_key' => getenv('JWT_SECRET_KEY'),

    'base_front_end_url' => [
        'vl24h' => getenv('VL24H_FRONT_END_BASE_URL'),
        'tvn' => getenv('TVN_FRONT_END_BASE_URL'),
        'vtn' => getenv('VTN_FRONT_END_BASE_URL'),
    ],

    'base_service_url' => [],
    'baseUri' => []
];
