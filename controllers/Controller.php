<?php

namespace app\controllers;

use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;

class Controller extends \yii\rest\ActiveController
{
	public
		$public = false,
		$publicActions = [],
		$rateLimiter = true;

	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$hasAuth = (bool) Yii::$app->request->headers->get('Authorization');
		$isPublic = $this->public || in_array(Yii::$app->controller->action->id, $this->publicActions);

		$auth = $hasAuth || !$isPublic;

		if ($auth)
		{
			$behaviors['authenticator'] = [
				'class' => CompositeAuth::className(),
				'authMethods' => [
					HttpBearerAuth::className(),
				],
			];
		}

		$behaviors['contentNegotiator']['formats'] = [
			'application/json' => Response::FORMAT_JSON,
			'application/javascript' => Response::FORMAT_JSONP,
		];

		if ($this->rateLimiter)
		{
			$behaviors['rateLimiter'] = [
				'class' => \ethercreative\ratelimiter\RateLimiter::className(),
				'rateLimit' => Yii::$app->params['rateLimiter']['limit'],
				'timePeriod' => Yii::$app->params['rateLimiter']['period'],
				'separateRates' => Yii::$app->params['rateLimiter']['separate'],
				'enableRateLimitHeaders' => YII_ENV_DEV,
			];
		}

		return $behaviors;
	}

	public function actions()
	{
		$actions = parent::actions();

		$actions['options'] = [
			'class' => '\app\controllers\base\OptionsAction',
			'verbs' => $this->verbs(),
		];

		return $actions;
	}
}
