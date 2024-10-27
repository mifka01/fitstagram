<?php

namespace app\controllers;

use app\enums\GroupType;
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
        $publicSearchModel = new UserGroupSearch('publicGroupSearch');
        $ownedSearchModel = new UserGroupSearch('ownedGroupSearch');
        $joinedSearchModel = new UserGroupSearch('joinedGroupSearch');


        $publicProvider = $publicSearchModel ->search(
            Yii::$app->request->queryParams,
            GroupType::PUBLIC
        );

        $ownedProvider = null;
        $joinedProvider = null;

        if (!Yii::$app->user->isGuest) {
            $ownedProvider = $ownedSearchModel->search(
                Yii::$app->request->queryParams,
                GroupType::OWNED
            );
            
            $joinedProvider = $joinedSearchModel->search(
                Yii::$app->request->queryParams,
                GroupType::JOINED
            );
        }

        return $this->render('index', [
            'publicSearchModel' => $publicSearchModel,
            'ownedSearchModel' => $ownedSearchModel,
            'joinedSearchModel' => $joinedSearchModel,
            'publicGroupsProvider' => $publicProvider,
            'ownedGroupsProvider' => $ownedProvider,
            'joinedGroupsProvider' => $joinedProvider,
        ]);
    }
}
