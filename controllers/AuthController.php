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
		if (Yii::$app->request->isPost)
		{
			$refresh_token = Yii::$app->request->post('refresh_token');
			$refresh_token = RefreshToken::find()->where(['token' => $refresh_token])->one();

			if (!$refresh_token)
				throw new HttpException(400, 'Incorrect refresh token.');

			return $refresh_token->generateAccessToken();
		}
		else
		{
			$token = Yii::$app->request->headers->get('Authorization');

			if (!$token)
				$token = Yii::$app->request->headers->get('authorization');

			$token = trim(str_replace('Bearer', '', $token));
			$token = AccessToken::find()->where(['token' => $token])->one();
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
