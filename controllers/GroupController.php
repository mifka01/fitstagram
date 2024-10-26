<?php

namespace app\controllers;

use app\models\search\UserGroupSearch;
use Yii;
use yii\web\Controller;

/**
 * Group controller
 */
class GroupController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new UserGroupSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'groupDataProvider' => $provider,
        ]);
    }
}
