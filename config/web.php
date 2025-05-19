<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$adminUrl = env('ADMIN_URL');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'name' => env('APP_NAME'),
    'homeUrl' => env('HOME_URL'),
    'defaultRoute' => 'site/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'admin' => [
            'class' => \app\modules\admin\Module::class,
            'defaultRoute' => 'admin/auth/login',
        ],
    ],
    'components' => [
        'assetManager' => [
            'converter' => [
                'class' => \yii\web\AssetConverter::class,
                'commands' => [
                    'less' => ['css', 'lessc {from} {to} --no-color'],
                ],
            ],
            'linkAssets' => YII_ENV_DEV,
            'appendTimestamp' => true,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'ru-RU',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'request' => [
            'cookieValidationKey' => env('COOKIE_KEY'),
            'enableCsrfValidation' => false,
            'enableCsrfCookie' => false,
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'user' => [
            'identityClass' => \app\modules\admin\models\User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => [
                        $adminUrl => 'admin/auth',
                        $adminUrl . '/' => 'admin/auth',
                        $adminUrl . '/auth' => 'admin/auth',
                    ],
                    'patterns' => [
                        'GET,POST login' => 'login',
                        'GET,POST logout' => 'logout',
                        'GET' => 'login',
                    ],
                ],
                [
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => [
                        $adminUrl . '/gallery' => 'admin/gallery',
                    ],
                    'patterns' => [
                        'GET,POST edit/{id}' => 'edit',
                        'GET,POST edit' => 'edit',
                        'POST save/{id}' => 'save',
                        'POST save' => 'save',
                        'GET,POST' => 'display',
                        'GET show/{id}' => 'show',
                        'POST delete' => 'delete',
                        'POST order' => 'order',
                        'POST add-item/{id}' => 'add-item',
                        'POST remove-item/{id}' => 'remove-item',
                        'POST publish' => 'publish',
                        'POST unpublish' => 'unpublish',
                    ],
                ],
                [
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => [
                        $adminUrl . '/page' => 'admin/page',
                    ],
                    'patterns' => [
                        'GET,POST edit/{id}' => 'edit',
                        'GET,POST edit' => 'edit',
                        'POST save/{id}' => 'save',
                        'POST save' => 'save',
                        'GET,POST' => 'display',
                        'POST delete' => 'delete',
                        'POST publish' => 'publish',
                        'POST unpublish' => 'unpublish',
                    ],
                ],
                [
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => [
                        $adminUrl . '/post' => 'admin/post',
                    ],
                    'patterns' => [
                        'GET,POST edit/{id}' => 'edit',
                        'GET,POST edit' => 'edit',
                        'POST save/{id}' => 'save',
                        'POST save' => 'save',
                        'GET,POST' => 'display',
                        'POST delete' => 'delete',
                        'POST publish' => 'publish',
                        'POST unpublish' => 'unpublish',
                        'POST order' => 'order',
                    ],
                ],
                [
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => [
                        $adminUrl . '/media' => 'admin/media',
                    ],
                    'patterns' => [
                        'GET edit/{id}' => 'edit',
                        'POST save/{id}' => 'save',
                        'POST' => 'display',
                        'GET show/{id}' => 'show',
                        'POST create/{id}' => 'create',
                        'POST delete' => 'delete',
                    ],
                ],
                [
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => [
                        $adminUrl . '/folder' => 'admin/folder',
                    ],
                    'patterns' => [
                        'GET edit/{id}' => 'edit',
                        'POST save/{id}' => 'save',
                        'GET create/{id}' => 'create',
                        'POST delete' => 'delete',
                    ],
                ],
                [
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => 'gallery',
                    'tokens' => [
                        '{alias}' => '<alias:\w+[\w+-]*>',
                    ],
                    'patterns' => [
                        'GET' => 'display',
                        'GET {alias}' => 'show',
                    ],
                ],
                [
                    'class' => \yii\rest\UrlRule::class,
                    'pluralize' => false,
                    'controller' => 'blog',
                    'tokens' => [
                        '{alias}' => '<alias:\w+[\w+-]*>',
                    ],
                    'patterns' => [
                        'GET' => 'display',
                        'GET {alias}' => 'show',
                    ],
                ],
                '' => 'site/index',
                'contacts' => 'site/contacts',
            ],
        ],
    ],
    'params' => $params,
];

/*
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}
*/
return $config;
