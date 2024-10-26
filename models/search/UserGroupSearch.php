<?php

namespace app\models\search;

use app\models\Group;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;

/**
 * UserGroupSearch represents the model behind the search form of `app\models\Group`.
 */
class UserGroupSearch extends Model
{
    public bool $newest = true;

    public bool $oldest = false;

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['newest', 'oldest'], 'boolean'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array<string,string> $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $user = $this->getCurrentUser();

        if ($user === null) {
            $query = Group::find()
                ->active()
                ->deleted(false)
                ->banned(false)
                ->with('owner');
        } else {
            $query = $this->buildUserGroupsQuery($user);
        }


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

        if ($this->oldest) {
            $query->orderBy(['created_at' => SORT_ASC]);
            return $dataProvider;
        }

        $query->orderBy(['created_at' => SORT_DESC]);
        return $dataProvider;
    }

    /**
     * Builds the query for retrieving user's groups
     *
     * @param User $user
     * @return ActiveQuery
     */
    private function buildUserGroupsQuery(User $user): ActiveQuery
    {
        $joinedGroupsQuery = $user->getJoinedGroups()->andWhere([
            'group.active' => 1,
            'group.deleted' => 0,
            'group.banned' => 0,
        ])->with('owner');

        $createdGroupIds = $user->getCreatedGroups()
            ->select('id')
            ->column();

        return $joinedGroupsQuery->orWhere(['id' => $createdGroupIds, 'group.deleted' => 0, 'group.banned' => 0]);
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
