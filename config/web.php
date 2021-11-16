<?php /** @noinspection SpellCheckingInspection */

use app\models\Telegram\Telegram;
use yii\log\FileTarget;
use yii\caching\FileCache;
use yii\swiftmailer\Mailer;

$params = require __DIR__ . '/params-local.php';
$db     = require __DIR__ . '/db-local.php';
$rules  = require __DIR__ . '/url-rules.php';

return [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'fYP9jORGCYEshSeZL8-b8P7nsvW4iEH-',
        ],
        'cache' => [
            'class' => FileCache::class,
        ],
        'errorHandler' => [
            'errorAction' => 'request/error',
        ],
        'mailer' => [
            'class' => Mailer::class,
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'   => [
                [
                    'class'  => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'telegram' => [
    	    'class'     => Telegram::class,
    	    'botToken'  => $params['api_key'],
        ],
        'db'            => $db,
        'urlManager' => [
            'enablePrettyUrl'   => true,
            'showScriptName'    => true,
            'rules'     => [
                [
                    'class' => yii\web\GroupUrlRule::class,
                    'rules' => $rules
                ]
            ],
        ],
    ],
    'params' => $params,
];
