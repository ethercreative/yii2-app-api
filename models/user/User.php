<?php

namespace app\models\user;

use Yii;
use yii\helpers\Json;

use app\models\user\AccessToken;
use app\models\user\RefreshToken;

class User extends \app\models\base\User
{
	public static function tableName()
	{
		return 'user';
	}

	public function rules()
	{
		return [
			[['display_name', 'email', 'password'], 'required'],
			['email', 'email'],
			['email', 'unique'],
		];
	}

	public function fields()
	{
		return [
			'id',
			'display_name',
			'created_at',
			'updated_at',
		];
	}

	public function getRefreshTokens()
	{
		return $this->hasMany(RefreshToken::className(), ['user_id' => 'id'])
			->orderBy(['created_at' => SORT_DESC]);
	}

	public function getAccessTokens()
	{
		return $this->hasMany(AccessToken::className(), ['user_id' => 'id'])
			->orderBy(['created_at' => SORT_DESC]);
	}

	public function generateRefreshToken($invalidateOld = false)
	{
		$model = new RefreshToken;
		$model->user_id = $this->id;
		$model->save();

		return $model;
	}

	public static function findIdentityByAccessToken($token, $type = null)
	{
		$access_token = AccessToken::find()->where(['token' => $token])->andWhere(['>', 'expires', time()])->one();
		return $access_token && $access_token->user ? $access_token->user : null;
	}
}
