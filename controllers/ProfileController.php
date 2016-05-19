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

		$actions['options']['collectionOptions'] = ['GET', 'PUT', 'PATCH', 'HEAD', 'OPTIONS'];
		$actions['options']['resourceOptions'] = ['GET', 'PUT', 'PATCH', 'HEAD', 'OPTIONS'];

		// die('<pre>'.print_r(Yii::$app->controller->module->requestedRoute, 1).'</pre>');

		if (Yii::$app->request->url === '/profile/' && Yii::$app->request->isPost)
			$actions['options']['collectionOptions'] = $this->verbs()['update'];

		if (Yii::$app->request->url === '/profile/password/')
			$actions['options']['collectionOptions'] = $this->verbs()['password'];

		if (Yii::$app->request->url === '/profile/avatar/')
			$actions['options']['collectionOptions'] = $this->verbs()['avatar'];

		return $actions;
	}

	public function verbs()
	{
		$verbs = parent::verbs();

		$verbs['update'] = ['PUT', 'PATCH', 'POST', 'OPTIONS'];
		$verbs['password'] = ['PUT', 'PATCH', 'OPTIONS'];
		$verbs['avatar'] = ['POST', 'OPTIONS'];

		return $verbs;
	}

	public function checkAccess($action, $model = null, $params = [])
	{
		$forbidden = false;

		switch ($action)
		{
			case 'index':
			case 'update':
			case 'view':
			case 'password':
				if ($model && $model->id !== Yii::$app->user->id)
					$forbidden = true;
			break;

			
			case 'avatar':
				if ($model && $model->user_id !== Yii::$app->user->id)
					$forbidden = true;
			break;
		}

		if ($forbidden)
			throw new ForbiddenHttpException('You do not have access to do that.');

		return true;
	}
}
