<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

use app\models\user\ResetPasswordForm;
use app\models\user\Reset;
use app\models\user\ResetForm;

class ForgotController extends \app\controllers\Controller
{
	public
		$public = true,
		$modelClass = '\app\models\user\ResetForm';

	public function actions()
	{
		$actions = parent::actions();

		$actions = [
			'password' => [
				'class' => 'yii\rest\CreateAction',
				'modelClass' => $this->modelClass,
			],
			'options' => $actions['options'],
		];

		$actions['options']['collectionOptions'] = [];
		$actions['options']['resourceOptions'] = [];

		if (Yii::$app->request->url === '/forgot/password/')
			$actions['options']['collectionOptions'] = $this->verbs()['password'];

		if (Yii::$app->request->url === '/forgot/reset/')
			$actions['options']['collectionOptions'] = $this->verbs()['reset'];

		return $actions;
	}

	public function verbs()
	{
		return [
			'password' => ['POST', 'OPTIONS'],
			'reset' => ['PUT', 'PATCH', 'OPTIONS'],
		];
	}

	public function actionReset($code)
	{
		$code = Reset::find()->where(['code' => $code, 'used' => 0])->andWhere(['>', 'expires', time()])->one();

		if (!$code)
			throw new NotFoundHttpException('The requested page does not exist.');

		$model = new ResetPasswordForm($code->user_id);

		$model->load(Yii::$app->getRequest()->getBodyParams(), '');

		if ($model->save() === false && !$model->hasErrors())
			throw new ServerErrorHttpException('Failed to update the object for unknown reason.');

		$code->used = 1;
		$code->save();

		return $model;
	}
}