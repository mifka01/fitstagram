<?php

namespace app\models\search;

use app\models\Post;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * UserPostSearch represents the model behind the search form of `app\models\Post`.
 */
class UserPostSearch extends Model
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array<string,string> $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $currentUserGroupIds = $this->getCurrentUserGroupIds();
        $query = Post::find()->public(true)->andWhere(['group_id' => $currentUserGroupIds])->with(['createdBy', 'tags', 'mediaFiles']);

        $permittingUserIds = $this->getPermittingUserIds();
        $query->orWhere(['created_by' => $permittingUserIds]);
        $query->deleted(false);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Get user ids that permit the current user.
     *
     * @return array<int>
     */
    private function getPermittingUserIds(): array
    {
        $user = $this->getCurrentUser();
        if ($user === null) {
            return [];
        }

        $permittingUserIds = User::find()->deleted(false)->banned(false)
        ->innerJoin('permitted_user', 'user.id = permitted_user.user_id')
        ->where(['permitted_user.permitted_user_id' => $user->id])
        ->select('user.id')->column();

        $permittingUserIds[] = $user->id;
        return $permittingUserIds;
    }

    /**
     * Get ids of groups that current user belongs to and own.
     *
     * @return array<int>
     */
    private function getCurrentUserGroupIds(): array
    {
        $user = $this->getCurrentUser();
        if ($user === null) {
            return [];
        }

        return $user->getGroups()->column();
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
