<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Response;

class Controller extends \yii\rest\ActiveController
{
	public
		$public = false,
		$publicActions = ['options'],
		$rateLimiter = true,
		$filters = [];

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

		$actions['index']['class'] = '\app\controllers\base\IndexAction';
		$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

		$actions['view']['class'] = '\app\controllers\base\ViewAction';

		$actions['create']['class'] = '\app\controllers\base\CreateAction';

		$actions['update']['class'] = '\app\controllers\base\UpdateAction';

		$actions['delete']['class'] = '\app\controllers\base\DeleteAction';

		$actions['options']['class'] = '\app\controllers\base\OptionsAction';
		$actions['options']['verbs'] = $this->verbs();
		$actions['options']['modelClass'] = $this->modelClass;

		return $actions;
	}

	public function prepareDataProvider()
	{
		$modelClass = $this->modelClass;

		$query = $modelClass::find();

		foreach ($this->getFilters() as $column => $value)
		{
			if ($value)
				$query->andWhere([$column => $value]);
		}

        return new ActiveDataProvider([
            'query' => $query,
        ]);
	}

	public function getFilters()
	{
		$filters = [];

		foreach ($this->filters as $key => $value)
		{
			if (is_int($key))
				$key = $value;

			$filters[$key] = Yii::$app->request->get($key);
		}

		return $filters;
	}
}
