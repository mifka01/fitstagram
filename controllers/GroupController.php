<?php

namespace app\controllers;

use app\enums\GroupType;
use app\models\forms\GroupForm;
use app\models\Group;
use app\models\search\GroupPostSearch;
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
                'only' => ['create', 'update', 'join', 'view'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'join', 'view'],
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


        $publicProvider = $publicSearchModel->search(
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
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/group', 'Group has been created.'));
                return $this->redirect(['view', 'id' => $model->getGroup()->id]);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/group', 'Failed to create group.'));
            }
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

    /**
     * Profile action
     *
     * @param int $id
     * @return string
     */
    public function actionView(int $id): string|Response
    {
        $model = Group::findOne($id);
        $user = User::findOne(Yii::$app->user->id);

        $searchModel = new GroupPostSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        if ($model === null || $model->active == false) {
            throw new NotFoundHttpException('Group not found');
        }

        $isGroupOwner = false;

        $joinedAt = null;

        $isMember = false;

        $posts = $model->getPosts()->deleted(false);

        $users = $model->getMembers()->active(true);


        if ($user !== null) {
            $isGroupOwner = $model->owner_id === $user->id;
            $joinedAt = $user->getGroupJoinedAt($model->id);
            $isMember = $user->isGroupMember($model->id);
        }

        //TODO check if user is member
        if (!$isMember) {
            return $this->redirect(['group/index']);
        }


        return $this->render('view', [
            'model' => $model,
            'joinedAt' => $joinedAt,
            'ownerUsername' => $model->owner->username,
            'isGroupOwner' => $isGroupOwner,
            'postDataProvider' => $provider,
            'isMember' => $isMember,
            'countUsers' => $users->count(),
            'countPosts' => $posts->count(),
        ]);
    }
}
