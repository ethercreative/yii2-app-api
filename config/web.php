<?php

$config = [
	'id' => 'app-api',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'params' => require(__DIR__ . '/params.php'),
	'components' => [
		'cache' => require(__DIR__ . '/cache.php'),
		'user' => require(__DIR__ . '/user.php'),
		'mailer' => require(__DIR__ . '/mailer.php'),
		'log' => require(__DIR__ . '/log.php'),
		'db' => require(__DIR__ . '/db.php'),
		'urlManager' => require(__DIR__ . '/urlManager.php'),
		'request' => require(__DIR__ . '/request.php'),
		'response' => require(__DIR__ . '/response.php'),
	],
	
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = [
		'class' => 'yii\gii\Module',
	];
}

return $config;
