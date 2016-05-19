<?php

namespace app\controllers;

class RegisterController extends \app\controllers\Controller
{
	public
		$public = true,
		$modelClass = '\app\models\RegisterForm';

	public function verbs()
	{
		return [
			'create' => ['POST', 'OPTIONS'],
		];
	}
}
