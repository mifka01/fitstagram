<?php

namespace app\controllers;

use app\enums\GroupType;
use app\models\forms\GroupForm;
use app\models\forms\GroupJoinRequestForm;
use app\models\Group;
use app\models\search\UserGroupSearch;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Group controller
 */
class GroupController extends Controller
{
    /**
     * {@inheritDoc}
     *
     * @return array<string, array<string, mixed>>
     */

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update'],
                'rules' => [
                    [
                        'actions' => ['create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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

    /**
     * Create new group
     *
     * @return Response|string
     */
    public function actionCreate(): Response|string
    {
        $model = new GroupForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app/group', 'Group has been created.'));
            return $this->redirect(['view', 'id' => $model->getGroup()->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id): Response|string
    {
        $group = Group::findOne($id);
        if (!$group) {
            throw new NotFoundHttpException('The requested group does not exist.');
        }

     
        /** @var User|null $currentUser */
        $currentUser = Yii::$app->user->identity;
      
        if ($currentUser === null || $group->owner_id !== $currentUser->id) {
            throw new ForbiddenHttpException('You are not allowed to edit this group.');
        }

        $model = new GroupForm();
        $model->setAttributes($group->attributes);
        // Set the existing group object in the form
        $model->group = $group;
    
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app/group', 'Group has been updated.'));
            return $this->redirect(['view', 'id' => $group->id]);
        }
    
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionJoin(): Response|string
    {
        $model = new GroupJoinRequestForm(['group_id' => Yii::$app->request->get('id')]);
    
        if ($model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app/group', 'Request to join group has been sent.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/group', 'Failed to send request to join group.'));
        }
    
        return $this->redirect(['group/index']);
    }
}
