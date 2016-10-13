<?php

namespace app\models\user;

use Yii;

class ChangePasswordForm extends \app\models\user\ResetPasswordForm
{
	public $current_password;

	public function rules()
	{
		$rules = parent::rules();

		$rules[] = ['current_password', 'required'];
		$rules[] = ['current_password', function()
		{
			if (!$this->user->validatePassword($this->current_password))
				$this->addError('current_password', 'Current password is incorrect.');
		}];

		return $rules;
	}
}
