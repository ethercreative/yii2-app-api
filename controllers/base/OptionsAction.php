<?php

namespace app\controllers\base;

use Yii;
use yii\helpers\ArrayHelper;

class OptionsAction extends \yii\rest\OptionsAction
{
	public $verbs = [];

	public $modelClass;

	public function run($id = null)
	{
		if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
			Yii::$app->getResponse()->setStatusCode(405);
		}

		$options = $id === null ? $this->collectionOptions : $this->resourceOptions;

		if (!empty($this->verbs))
		{
			$action = trim(str_replace(Yii::$app->controller->id, '', Yii::$app->request->url), '/');

			if (!$action)
			{
				$options = ArrayHelper::merge(
					!empty($this->verbs['index']) ? $this->verbs['index'] : [],
					!empty($this->verbs['create']) ? $this->verbs['create'] : []
				);
			}
			elseif(!empty($this->verbs[$action]))
			{
				$options = $this->verbs[$action];
			}
		}

		if (!in_array('OPTIONS', $options))
			$options[] = 'OPTIONS';

		Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $options));

		$modelClass = $this->modelClass;

		return (new $modelClass)->safeAttributes();
	}
}
