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
        $query = Post::find()->public(true);

        $permittedUserIds = $this->getPermittedUserIds();
        $query->orWhere(['created_by' => $permittedUserIds])->with(['createdBy', 'tags', 'mediaFiles']);

        // add conditions that should always apply here

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

        $query->deleted(false);

        return $dataProvider;
    }

    /**
     * Get permitted user IDs for the current user.
     *
     * @return array<int>
     */
    private function getPermittedUserIds(): array
    {
        $user = $this->getCurrentUser();
        if ($user === null) {
            return [];
        }
        $permittedUserIds = $user->getPermittedUsers()->select('id')->column();
        $permittedUserIds[] = $user->id;
        return $permittedUserIds;
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
