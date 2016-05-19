<?php

namespace app\models\user;

use Yii;

use app\models\user\RefreshToken;
use app\models\user\User;

class AccessToken extends \app\models\base\Model
{
	public function init()
	{
		$this->generateToken();
		$this->generateExpiry();
	}

	public static function tableName()
	{
		return 'user_access_token';
	}

	public function rules()
	{
		return [
			[['user_id', 'refresh_id', 'token'], 'required'],
		];
	}

	public function fields()
	{
		return [
			'token',
			'expires',
		];
	}

	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	public function getRefreshToken()
	{
		return $this->hasOne(RefreshToken::className(), ['id' => 'refresh_id']);
	}

	public function generateToken($length = 64)
	{
		$this->token = Yii::$app->security->generateRandomString($length);
	}

	public function generateExpiry($length = 1440) // temp
	{
		$this->expires = strtotime("+{$length} minutes", time());
	}
}
