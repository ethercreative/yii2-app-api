<?php

namespace app\models\user;

use Yii;

use app\models\user\User;

class Reset extends \app\models\BaseModel
{
	public $password, $confirm_password;

	public static function tableName()
	{
		return 'user_reset';
	}

	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	public function init()
	{
		$this->code = Yii::$app->security->generateRandomString(12);
	}

	public function rules()
	{
		return [
			[['user_id', 'code', 'expires'], 'required'],
			['used', 'safe'],
			[['password', 'confirm_password'], 'required', 'on' => 'reset'],
			['confirm_password', 'compare', 'compareAttribute' => 'password', 'on' => 'reset'],
		];
	}

	public function beforeValidate()
	{
		if(!$this->expires)
			$this->expires = strtotime(Yii::$app->params['user']['reset_timeout']);

		return parent::beforeValidate();
	}

	public function reset()
	{
		$this->user->password = $this->password;
		if ($this->user->save())
		{
			$this->used = 1;
			return $this->save();
		}

		return false;
	}
}