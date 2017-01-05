<?php

namespace app\components;

class Serializer extends \yii\rest\Serializer
{
	public $preserveKeys = false; // whether to retain the array keys when returning data
}
