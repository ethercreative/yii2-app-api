<?php

namespace app\models\base;

use Yii;
use yii\helpers\Json;

class Model extends \yii\db\ActiveRecord
{
	public $jsonFields = [];

	public function behaviors()
	{
		return [
			\yii\behaviors\TimestampBehavior::className(),
		];
	}

	public function afterFind()
	{
		$this->decodeJsonFields();

		return parent::afterFind();
	}

	public function beforeSave($insert)
	{
		$this->encodeJsonFields();

		return parent::beforeSave($insert);
	}

	public function afterSave($insert, $changedAttributes)
	{
		$this->decodeJsonFields();

		return parent::afterSave($insert, $changedAttributes);
	}

	public function relatedFields(&$fields)
	{
		$expandFields = explode(',', Yii::$app->request->getQueryParam('expand'));

		$extraFields = [];

		foreach ($expandFields as $field)
		{
			if (!strpos($field, '.')) continue;

			$field = explode('.', $field);

			if (!isset($extraFields[$field[0]]))
				$extraFields[$field[0]] = [];

			$extraFields[$field[0]][] = $field[1];
		}

		foreach ($extraFields as $key => $extra)
		{
			if (in_array($key, $fields))
			{
				$fields[$key] = function() use ($key, $extra)
				{
					$data = $this->{$key};

					if(is_array($data))
					{
						foreach ($data as &$row)
						{
							$row = $row->toArray([], $extra, true);
						}
					}
					else
					{
						$data = $data->toArray([], $extra, true);
					}

					return $data;
				};
			}
		}
	}

	public function encodeJsonFields()
	{
		// loop json fields and encode
		foreach ($this->jsonFields as $field)
		{
			if (
				(!$this->hasProperty($field) && !array_key_exists($field, $this->attributes)) || 
				!is_array($this->{$field})
			)
			{
				if (!$this->{$field})
					$this->{$field} = null;

				continue;
			}

			$this->{$field} = Json::encode($this->{$field});
		}
	}

	public function decodeJsonFields()
	{
		foreach ($this->jsonFields as $field)
		{
			if (
				(!$this->hasProperty($field) && !array_key_exists($field, $this->attributes)) || 
				is_array($this->{$field}) || 
				empty($this->{$field}[0]) || 
				!in_array($this->{$field}[0], ['{', '['])
			)
			{
				continue;
			}

			if (!$this->{$field})
			{
				$this->{$field} = null;
				continue;
			}

			$this->{$field} = Json::decode($this->{$field});
		}
	}
}