<?php

namespace app\controllers\profile;

use Yii;
use yii\web\ServerErrorHttpException;

use app\models\user\ChangePasswordForm;

class PasswordAction extends \yii\rest\Action
{
	public function run()
	{
		$model = $this->findModel(Yii::$app->user->id);

		if ($this->checkAccess) {
			call_user_func($this->checkAccess, $this->id, $model);
		}

		$form = new ChangePasswordForm(Yii::$app->user->id);
		$form->load(Yii::$app->getRequest()->getBodyParams(), '');

		if ($form->save() === false && !$form->hasErrors())
			throw new ServerErrorHttpException('Failed to update the object for unknown reason.');

		return $form;
	}
}