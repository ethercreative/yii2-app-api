<?php

namespace app\controllers;

use Yii;

use app\models\user\User;

class UserController extends \app\controllers\Controller
{
	public
		$public = true,
		$modelClass = '\app\models\user\User';
}
