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
		$filters = [],
		$searchClass;

	public $serializer = '\app\components\Serializer';

	public $forbiddenMessage = 'You do not have the privileges to perform that action.';

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

		$actions = $this->actions();

		if (!empty($actions['options']['collectionOptions']))
		{
			$behaviors['corsFilter'] = [
				'class' => \yii\filters\Cors::className(),
				'cors' => [
					'Access-Control-Request-Method' => $actions['options']['collectionOptions'],
				],
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

		if (!empty($this->searchClass))
		{
			$actions['search'] = [
				'class' => '\app\controllers\base\SearchAction',
				'modelClass' => $this->searchClass,
			];
		}

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

	public function afterAction($action, $result)
	{
		Yii::$app->response->headers->set('Access-Control-Allow-Origin', '*');
		Yii::$app->response->headers->set('Access-Control-Allow-Headers', join(', ', ['Content-Type', 'Authorization']));
		Yii::$app->response->headers->set('Access-Control-Allow-Methods', Yii::$app->response->headers->get('Allow'));
		Yii::$app->response->headers->set('Access-Control-Expose-Headers', join(', ', ['Link', 'X-Pagination-Current-Page', 'X-Pagination-Page-Count', 'X-Pagination-Per-Page', 'X-Pagination-Total-Count', 'User-Flash']));

		if (!Yii::$app->request->isOptions)
		{
			$flashes = Yii::$app->session->getAllFlashes();

			foreach ($flashes as $key => &$flash)
			{
				switch($key)
				{
					case 'success':
						$title = 'Success';
					break;

					case 'danger':
						$title = 'Error';
					break;

					default:
						$title = 'Info';
					break;
				}

				if (is_array($flash))
				{
					if (!$flash['title'])
						$flash['title'] = $title;
				}
				else
				{

					$flash = ['title' => $title, 'message' => $flash];
				}
			}

			if ($flashes)
				Yii::$app->response->headers->set('User-Flash', json_encode($flashes));
		}

		return parent::afterAction($action, $result);
	}
}
