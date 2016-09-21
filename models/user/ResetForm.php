<?php

namespace app\models\user;

use Yii;

use app\models\user\User;
use app\models\user\Reset;

class ResetForm extends \yii\base\Model
{
	private $user;

	public $email;

	public function rules()
	{
		return [
			['email', 'required'],
			['email', 'email'],
		];
	}

	public function fields()
	{
		return [
			'message' => function()
			{
				return 'If there is an account associated with that email address, a recovery email has been sent.';
			},
		];
	}

	public function save()
	{
		if (!$this->validate())
			return false;

		$this->user = User::find()->where(['email' => $this->email])->one();

		if ($this->user)
		{
			$model = new Reset;
			$model->user_id = $this->user->id;

			return $model->save();
		}

		return true;
	}

	public function getPrimaryKey($asArray = false)
	{
		return $this->user ? $this->user->getPrimaryKey($asArray) : [];
	}
}
