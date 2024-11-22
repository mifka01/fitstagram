<?php

namespace app\models\search;

use app\models\PermittedUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;

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

        $query = PermittedUser::find()
            ->joinWith('permittedUser')
            ->where(['permitted_user.user_id' => $params['id']])->andWhere(['!=','permitted_user.permitted_user_id', $params['id']]);

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
            $query->andWhere(['like', 'user.username', $this->keyword]);
        }

        if ($this->active !== null) {
            $query->andWhere(['user.active' => $this->active]);
        }

        return $dataProvider;
    }
}
