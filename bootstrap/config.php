<?php

return  [
    'settings'=>[
            'displayErrorDetails' => (bool)getenv('DISPLAY_ERRORS'),
            'db' => [
                'driver'    => getenv('DB_DRIVER')      ? getenv('DB_DRIVER')       :   'mysql',
                'host'      => getenv('DB_HOST')        ? getenv('DB_HOST')         :   'localhost',
                'database'  => getenv('DB_NAME')        ? getenv('DB_NAME')         :   'test',
                'username'  => getenv('DB_USER')        ? getenv('DB_USER')         :   'root',
                'password'  => getenv('DB_PASS')        ? getenv('DB_PASS')         :   '',
                'charset'   => getenv('DB_CHARSET')     ? getenv('DB_CHARSET')      :   'utf8',
                'collation' => getenv('DB_COLLATION')   ? getenv('DB_COLLATION')    :   'utf8_unicode_ci',
                'prefix'    => getenv('DB_PREFIX')      ? getenv('DB_PREFIX')       :   ''
            ]   
        ]
    ];
