<?php

namespace app\models\user;

use Yii;

use app\models\user\AccessToken;
use app\models\user\User;

class RefreshToken extends \app\models\base\Model
{
	public function init()
	{
		$this->generateToken();
		$this->generateExpiry();
	}

	public static function tableName()
	{
		return 'user_refresh_token';
	}

	public function rules()
	{
		return [
			[['user_id', 'token'], 'required'],
		];
	}

	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	public function getAccessTokens()
	{
		return $this->hasMany(AccessToken::className(), ['refresh_id' => 'id'])
			->orderBy(['created_at' => SORT_DESC]);
	}

	public function generateToken($length = 64)
	{
		$this->token = Yii::$app->security->generateRandomString($length);
	}

	public function generateExpiry($length = 30)
	{
		$this->expires = strtotime("+{$length} days", time());
	}

	public function generateAccessToken($invalidateOld = false)
	{
		$model = new AccessToken;
		$model->user_id = $this->user_id;
		$model->refresh_id = $this->id;
		$model->save();

		if ($invalidateOld)
		{
			// clear all other tokens
		}

		return $model;
	}
}
