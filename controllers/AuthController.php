<?php

namespace app\controllers;

use Yii;
use yii\web\HttpException;

use app\models\user\AccessToken;

class AuthController extends \app\controllers\Controller
{
	public
		$publicActions = ['create', 'options', 'token'],
		$modelClass = '\app\models\LoginForm';

	public function verbs()
	{
		$verbs = parent::verbs();

		$verbs['index'] = [];
		$verbs['create'] = ['POST', 'OPTIONS'];
		$verbs['token'] = ['GET', 'POST', 'OPTIONS'];
		$verbs['delete'] = ['DELETE', 'OPTIONS'];

		return $verbs;
	}

	public function actionToken()
	{
		$token = Yii::$app->request->headers->get('Authorization');
		$token = trim(str_replace('Bearer', '', $token));
		$token = AccessToken::find()->where(['token' => $token])->one();

		if (Yii::$app->request->isPost)
		{
			$refresh_token = Yii::$app->request->post('refresh_token');

			if ($token->refreshToken->token !== $refresh_token)
				throw new HttpException(400, 'Refresh token mismatch.');

			$newToken = new AccessToken;
			$newToken->refresh_id = $token->refresh_id;
			$newToken->user_id = Yii::$app->user->id;
			$newToken->save();

			return $newToken;
		}
		else
		{
			return $token;
		}
	}

	public function actionDelete()
	{
		return [
			'message' => 'Logged out',
		];
	}
}
