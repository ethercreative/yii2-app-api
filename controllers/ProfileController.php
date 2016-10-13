<?php

namespace app\controllers;

use Yii;
use yii\web\ForbiddenHttpException;

class ProfileController extends \app\controllers\Controller
{
	public
		$modelClass = '\app\models\user\User',
		$avatarClass = '\app\models\user\AvatarForm';

	public function actions()
	{
		$actions = parent::actions();

		$actions = [
			'index' => [
				'class' => '\app\controllers\profile\ViewAction',
				'modelClass' => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
			],
			'update' => [
				'class' => '\app\controllers\profile\UpdateAction',
				'modelClass' => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
				'scenario' => $this->updateScenario,
			],
			'password' => [
				'class' => '\app\controllers\profile\PasswordAction',
				'modelClass' => $this->modelClass,
				'checkAccess' => [$this, 'checkAccess'],
			],
			'avatar' => [
				'class' => '\app\controllers\profile\AvatarAction',
				'modelClass' => $this->avatarClass,
				'checkAccess' => [$this, 'checkAccess'],
			],
			'options' => $actions['options'],
		];

		return $actions;
	}

	public function verbs()
	{
		return [
			'index' => ['GET', 'PUT', 'PATCH', 'HEAD', 'OPTIONS'],
			'update' => ['PUT', 'PATCH', 'POST', 'OPTIONS'],
			'password' => ['PUT', 'PATCH', 'OPTIONS'],
			'avatar' => ['POST', 'OPTIONS'],
		];
	}

	public function checkAccess($action, $model = null, $params = [])
	{
		if ($model && $model->id !== Yii::$app->user->id)
			throw new ForbiddenHttpException('You do not have access to do that.');

		return true;
	}
}
