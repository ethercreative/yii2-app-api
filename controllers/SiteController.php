<?php

namespace app\controllers;

class SiteController extends \app\controllers\Controller
{
    public
        $public = true,
        $modelClass = '\app\models\Blank';

    public function actions()
    {
        $actions = parent::actions();

        $actions = [
            'index' => null,
            'options' => $actions['options'],
        ];

        return $actions;
    }

    public function verbs()
    {
        return [
            'index' => ['GET', 'HEAD', 'OPTIONS'],
        ];
    }

    public function actionIndex()
    {
        return ['message' => 'Hello world.'];
    }
}
