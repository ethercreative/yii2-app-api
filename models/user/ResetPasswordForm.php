<?php

namespace app\models\user;

use Yii;

use app\models\user\User;

class ResetPasswordForm extends \yii\base\Model
{
	public $user;

	public $new_password, $confirm_password;

	public function __construct($id)
	{
		$this->user = User::findOne($id);
	}

	public function rules()
	{
		return [
			[['new_password', 'confirm_password'], 'required'],
			['confirm_password', 'compare', 'compareAttribute' => 'new_password'],
		];
	}

	public function fields()
	{
		return [
			'message' => function()
			{
				return 'Your password has been updated successfully.';
			}
		];
	}

	public function save()
	{
		if (!$this->validate())
			return false;

		$this->user->password = $this->new_password;

		return $this->user->save();
	}
}
