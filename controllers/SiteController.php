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
            'index' => [
                'class' => '\app\controllers\site\IndexAction',
                'modelClass' => $this->modelClass,
            ],
            'options' => $actions['options'],
        ];

        $actions['options']['collectionOptions'] = ['GET', 'HEAD', 'OPTIONS'];
        $actions['options']['resourceOptions'] = ['GET', 'HEAD', 'OPTIONS'];

        return $actions;
    }
}