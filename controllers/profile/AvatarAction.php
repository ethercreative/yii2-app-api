<?php

namespace app\controllers\profile;

use Yii;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

class AvatarAction extends \yii\rest\Action
{
	public $scenario = Model::SCENARIO_DEFAULT;

	public function run()
	{
		$modelClass = $this->modelClass;
		$model = new $modelClass(Yii::$app->user->id);
		if ($this->checkAccess) {
			call_user_func($this->checkAccess, $this->id, $model);
		}
		$model->scenario = $this->scenario;

		if ($model->save() === false && !$model->hasErrors()) {
			throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
		}
		
		return $model;
	}
}