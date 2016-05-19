<?php

namespace app\models;

use Yii;

use app\models\user\User;

class LoginForm extends \yii\base\Model
{
	public
		$email,
		$password;

	private
		$user,
		$refresh_token,
		$access_token;

	public function rules()
	{
		return [
			[['email', 'password'], 'required'],
			['email', 'email'],
			['email', function()
			{
				$this->user = User::find()->where(['email' => $this->email])->one();

				if (!$this->user || !$this->user->validatePassword($this->password))
					$this->addError('password', 'Email or Password incorrect.');
			}, 'when' => function()
			{
				return (bool) $this->email && $this->password;
			}],
		];
	}

	public function fields()
	{
		return [
			'access_token' => function()
			{
				return [
					'token' => $this->access_token->token,
					'expires' => $this->access_token->expires,
				];
			},
			'refresh_token' => function()
			{
				return [
					'token' => $this->refresh_token->token,
					'expires' => $this->refresh_token->expires,
				];
			},
			'user' => function()
			{
				return $this->user;
			},
		];
	}

	public function save()
	{
		if (!$this->validate())
			return false;

		$this->refresh_token = $this->user->generateRefreshToken();
		$this->access_token = $this->refresh_token->generateAccessToken();

		return true;
	}

	public function getPrimaryKey($asArray = false)
	{
		return [];
	}
}
