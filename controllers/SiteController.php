<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'url' => Url::toRoute('/images/index')
        ]);
    }

}
