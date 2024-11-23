<?php

namespace app\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * PermiedUserSearch represents the model behind the search form for fetching permmited users.
 */
class PermittedUserSearch extends Model
{
    public ?string $keyword = null;

    public ?bool $active = null;

    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['active'], 'boolean'],
            [['keyword'], 'string'],
            [['keyword'], 'trim'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array<string, mixed> $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {

        $user = $this->getCurrentUser();
        if ($user === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'User not found.'));
        }

        $permittedUserIds = $user->getPermittedUsers()->column();

        $query = User::find()->where(['id' => $permittedUserIds])->deleted(false)->banned(false)->andWhere(['!=', 'id', $user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // Return the data provider even if validation fails
            return $dataProvider;
        }

        if (!empty($this->keyword)) {
            $query->andWhere(['like', 'username', $this->keyword]);
        }

        if ($this->active !== null) {
            $query->andWhere(['active' => $this->active]);
        }

        return $dataProvider;
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
