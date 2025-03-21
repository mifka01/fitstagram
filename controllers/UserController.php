<?php

namespace app\controllers;

use app\enums\GroupType;
use app\models\forms\PermittedUserForm;
use app\models\forms\UserRemoveForm;
use app\models\forms\UserUpdateForm;
use app\models\search\PermittedUserSearch;
use app\models\search\UserGroupSearch;
use app\models\search\UserProfilePostSearch;
use app\models\sort\PostSort;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * User controller
 */
class UserController extends Controller
{
    /**
     * {@inheritDoc}
     *
     * @return array<mixed>
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['permitted-users', 'revoke-permitted-user', 'add-permitted-user', 'profile', 'update'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['ban', 'unban'],
                        'roles' => ['moderator'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['profile'],
                        'roles' => ['?'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Profile action
     *
     * @param string $username
     * @return string
     */
    public function actionProfile(string $username): string
    {
        $model = User::findByUsername($username);
        $user = $this->getCurrentUser();

        if ($model === null) {
            throw new NotFoundHttpException('User not found');
        }

        $ownedSearchModel = new UserGroupSearch('ownedGroupSearch', $model);
        $ownedProvider = $ownedSearchModel->search(
            Yii::$app->request->queryParams,
            GroupType::OWNED
        );

        $joinedSearchModel = new UserGroupSearch('joinedSearchModel', $model);
        $joinedProvider = $joinedSearchModel->search(
            Yii::$app->request->queryParams,
            GroupType::JOINED
        );

        $sort = new PostSort();
        $searchModel = new UserProfilePostSearch();
        $searchModel->id = $model->id;
        $provider = $searchModel->search(Yii::$app->request->queryParams);
        $provider->setSort($sort);

        $isOwnProfile = $user !== null && $model->id === $user->id;

        $posts = $model->getPosts()->deleted(false)->banned(false);

        if (!$isOwnProfile) {
            $posts->public(true);
        }

        return $this->render('profile', [
            'model' => $model,
            'isOwnProfile' => $isOwnProfile,
            'ownedGroupsProvider' => $ownedProvider,
            'joinedGroupsProvider' => $joinedProvider,
            'ownedSearchModel' => $ownedSearchModel,
            'joinedSearchModel' => $joinedSearchModel,
            'countOwnedGroups' => $ownedProvider->getTotalCount(),
            'countJoinedGroups' => $model->getJoinedGroups()->count(),
            'countPosts' => $posts->count(),
            'currentUser' => $user,
            'postDataProvider' => $provider,
        ]);
    }

    /**
     * Update action
     *
     * @return string|Response
     */
    public function actionUpdate(): string|Response
    {
        $user = $this->getCurrentUser();

        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }

        $model = new UserUpdateForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'Profile updated successfully.'));
            return $this->redirect(['profile', 'username' => $user->username]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Update action
     *
     * @return string|Response
     */
    public function actionPermittedUsers(): string|Response
    {
        $user = $this->getCurrentUser();
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }

        $model = User::findOne($user->id);
        if ($model === null || $model->active == false) {
            throw new NotFoundHttpException(Yii::t('app/user', 'User not found.'));
        }

        $permittedUserModel = new PermittedUserSearch(['active' => 1]);
        $permittedUserModel->load(Yii::$app->request->queryParams);

        $permittedUserProvider = $permittedUserModel->search(array_merge(
            Yii::$app->request->queryParams,
            ['id' => $user->id]
        ));


        return $this->render('permittedUsers', [
            'permittedUserSearchModel' => $permittedUserModel,
            'permittedUserProvider' => $permittedUserProvider,
            'model' => $model,
        ]);
    }

    /**
     * Action to revoke permitted user
     */
    public function actionRevokePermittedUser(int $id): Response|string
    {
        $model = new PermittedUserForm(['id' => $id]);
        $user = $this->getCurrentUser();
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }

        if ($model->revokePermittedUser()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'Friend removed.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Error removing friend.'));
        }

        return $this->redirect(['permitted-users']);
    }

    /**
     * Action to add permitted user
     */
    public function actionAddPermittedUser(int $id): Response|string
    {
        $model = new PermittedUserForm(['id' => $id]);
        $user = $this->getCurrentUser();
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }
        $permittedUser = User::findOne($id);
        if ($permittedUser === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }

        if ($model->addPermittedUser()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'Friend added.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Error adding friend.'));
        }

        return $this->redirect(['profile', 'username' => $permittedUser->username]);
    }

    /**
     * Get the current user.
     *
     * @return User|null
     * @throws NotFoundHttpException
     */
    private function getCurrentUser(): User|null
    {
        if (Yii::$app->user->isGuest) {
            return null;
        }

        $user = User::findOne(Yii::$app->user->id);
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }
        return $user;
    }

    public function actionDelete(int $id): Response
    {
        $model = new UserRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'User has been deleted.'));
            return $this->goHome();
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Failed to delete user.'));
            return $this->redirect(['group/view', 'id' => $id]);
        }
    }

    public function actionBan(int $id): Response
    {
        $model = new UserRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->ban()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'User has been banned.'));
            return $this->goHome();
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Failed to ban user.'));
            return $this->refresh();
        }
    }

    public function actionUnban(int $id): Response
    {
        $model = new UserRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->unban()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'User has been unbanned.'));
            return $this->goHome();
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Failed to unban user.'));
            return $this->refresh();
        }
    }
}
