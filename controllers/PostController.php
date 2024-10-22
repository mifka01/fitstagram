<?php

namespace app\controllers;

use app\models\search\UserPostSearch;
use Yii;
use yii\web\Controller;

/**
 * Post controller
 */
class PostController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new UserPostSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'postDataProvider' => $provider,
        ]);
    }
}
