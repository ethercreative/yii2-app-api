<?php

$urlRule = '\app\components\UrlRule';
$pluralize = false;

return [
	'enablePrettyUrl' => true,
	'showScriptName' => false,
	'enableStrictParsing' => false,
	'suffix' => '/',
	'rules' => [
		[
			'class' => $urlRule,
			'pluralize' => $pluralize,
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
			'class' => $urlRule,
			'pluralize' => $pluralize,
			'controller' => 'profile',
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
			'class' => $urlRule,
			'pluralize' => $pluralize,
			'controller' => 'register',
			'only' => [
				'create',
				'options',
			],
		],
		[
			'class' => $urlRule,
			'pluralize' => $pluralize,
			'controller' => 'site',
			'only' => [
				'index',
				'options'
			],
		],
	],
];
