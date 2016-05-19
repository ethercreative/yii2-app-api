<?php

namespace app\controllers\profile;

use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

class UpdateAction extends \yii\rest\Action
{
	public $scenario = Model::SCENARIO_DEFAULT;

	public function run()
	{
		$model = $this->findModel(Yii::$app->user->id);
		if ($this->checkAccess) {
			call_user_func($this->checkAccess, $this->id, $model);
		}
		$model->scenario = $this->scenario;
		$model->load(Yii::$app->getRequest()->getBodyParams(), '');
		if ($model->save() === false && !$model->hasErrors()) {
			throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
		}

		$model->scenario = 'self';

		return $model;
	}
}