<?php

namespace app\controllers\base;

use Yii;
use yii\web\NotFoundHttpException;

class ViewAction extends \yii\rest\ViewAction
{
	public function run($id)
	{
		$model = parent::run($id);

		foreach ($this->controller->getFilters() as $column => $value)
		{
			if ($model->{$column} != $value)
				throw new NotFoundHttpException("Object not found: $id");
		}

		return $model;
	}
}
