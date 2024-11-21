<?php

namespace app\controllers;

use app\enums\GroupType;
use app\models\forms\UserUpdateForm;
use app\models\query\GroupQuery;
use app\models\search\UserGroupSearch;
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
                        'actions' => ['update'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['profile'],
                    ],
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
        $user = Yii::$app->user;

        if ($model === null || $user == null) {
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

        $isOwnProfile = $model->id === $user->id;

        $posts = $model->getPosts()->deleted(false);

        if (!$isOwnProfile) {
            /** @var GroupQuery $query */
            $query = $ownedProvider->query;
            $query->active();

            $posts->public(true);
        }

        return $this->render('profile', [
            'model' => $model,
            'isOwnProfile' => $model->id === $user->id,
            'ownedGroupsProvider' => $ownedProvider,
            'joinedGroupsProvider' => $joinedProvider,
            'ownedSearchModel' => $ownedSearchModel,
            'joinedSearchModel' => $joinedSearchModel,
            'countOwnedGroups' => $ownedProvider->getTotalCount(),
            'countJoinedGroups' => $model->getJoinedGroups()->active()->count(),
            'countPosts' => $posts->count(),
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
}
