<?php

return [
	'enablePrettyUrl' => true,
	'showScriptName' => false,
	'enableStrictParsing' => false,
	'suffix' => '/',
	'rules' => [
		[
			'class' => 'yii\rest\UrlRule',
			'pluralize' => false,
			'controller' => 'auth',
			'only' => [
				'create',
				'options',
			],
			'patterns' => [
				'POST' => 'create',
				'POST token' => 'token',
				'OPTIONS' => 'options',
				'OPTIONS token' => 'options',
				'DELETE delete' => 'delete',
				'OPTIONS delete' => 'options',
				'' => 'options',
			],
		],
		[
			'class' => 'yii\rest\UrlRule',
			'controller' => 'profile',
			'pluralize' => false,
			'patterns' => [
				'POST avatar' => 'avatar',
				'OPTIONS avatar' => 'options',
				'PUT,PATCH password' => 'password',
				'OPTIONS password' => 'options',
				'PUT,PATCH,POST' => 'update',
				'GET,HEAD' => 'index',
				'' => 'options',
			],
		],
		[
			'class' => 'yii\rest\UrlRule',
			'pluralize' => false,
			'controller' => 'register',
			'only' => [
				'create',
				'options',
			],
		],
		[
			'class' => 'yii\rest\UrlRule',
			'pluralize' => false,
			'controller' => 'site',
			'only' => [
				'index',
				'options'
			],
		],
	],
];