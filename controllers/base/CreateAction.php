<?php

namespace app\controllers\base;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

class CreateAction extends \yii\rest\CreateAction
{
	public function run()
	{
		if ($this->checkAccess) {
			call_user_func($this->checkAccess, $this->id);
		}
		/* @var $model \yii\db\ActiveRecord */
		$model = new $this->modelClass([
			'scenario' => $this->scenario,
		]);
		$model->load(Yii::$app->getRequest()->getBodyParams(), '');

		foreach ($this->controller->getFilters() as $column => $value)
		{
			if ($value && $model->hasAttribute($column))
				$model->{$column} = $value;
		}

		if ($model->save()) {
			$response = Yii::$app->getResponse();
			$response->setStatusCode(201);
			$id = implode(',', array_values($model->getPrimaryKey(true)));
			$response->getHeaders()->set('Location', Url::toRoute([$this->viewAction, 'id' => $id], true));
		} elseif (!$model->hasErrors()) {
			throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
		}
		return $model;
	}
}
