<?php

namespace app\models;

use Yii;

use app\models\user\User;

class RegisterForm extends \yii\base\Model
{
	public
		$email,
		$password,
		$display_name;

	private
		$user,
		$refresh_token,
		$access_token;

	public function rules()
	{
		return [
			[['email', 'password', 'display_name'], 'required'],
			['email', 'email'],
			['email', 'unique', 'targetClass' => '\app\models\user\User', 'targetAttribute' => 'email'],
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
			}
		];
	}

	public function save()
	{
		if (!$this->validate())
			return false;

		$this->user = new User;

		$transaction = Yii::$app->db->beginTransaction();

		try
		{
			$this->user->email = $this->email;
			$this->user->password = $this->password;
			$this->user->display_name = $this->display_name;
			$this->user->save();

			$this->refresh_token = $this->user->generateRefreshToken();
			$this->access_token = $this->refresh_token->generateAccessToken();

			$transaction->commit();
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw new \yii\web\HttpException(500, 'Error generating user and tokens.');
		}

		return true;
	}

	public function getPrimaryKey($asArray = false)
	{
		return $this->user->getPrimaryKey($asArray);
	}
}
