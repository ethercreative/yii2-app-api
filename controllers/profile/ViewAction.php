<?php

namespace app\controllers\profile;

use Yii;

class ViewAction extends \yii\rest\Action
{
	public function run()
	{
		$model = $this->findModel(Yii::$app->user->id);
		$model->scenario = 'self';
		if ($this->checkAccess) {
			call_user_func($this->checkAccess, $this->id, $model);
		}
		return $model;
	}
}