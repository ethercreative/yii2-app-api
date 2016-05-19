<?php

namespace app\models\base;

use Yii;

class User extends \app\models\base\Model implements \yii\web\IdentityInterface
{
	private $_password;

	public function init()
	{
		$this->generateAuthKey();
	}

	public function afterFind()
	{
		$this->_password = $this->password;
		$this->password = null;

		return parent::afterFind();
	}

	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id]);
	}
	
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new \yii\base\NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	public function getId()
	{
		return $this->getPrimaryKey();
	}

	public function getAuthKey()
	{
		return $this->auth_key;
	}
	
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	public function validatePassword($password)
	{
		if (!$this->_password)
			return false;
		
		return Yii::$app->security->validatePassword($password, $this->_password);
	}

	public function beforeValidate()
	{
		if (!$this->password && $this->_password)
			$this->password = $this->_password;
		
		return parent::beforeValidate();
	}

	public function beforeSave($insert)
	{
		if ($this->_password && !$this->password)
			$this->password = $this->_password;

		return parent::beforeSave($insert);
	}

	public function setPassword($password)
	{
		$this->password = Yii::$app->security->generatePasswordHash($this->password);
		return $this;
	}
}