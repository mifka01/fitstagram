<?php

namespace app\models;

use app\models\PermittedUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PermittedUserSearch represents the model behind the search form of `app\models\PermittedUser`.
 */
class PermittedUserSearch extends PermittedUser
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'permitted_user_id'], 'integer'],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string,string>
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array<string,string> $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PermittedUser::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'permitted_user_id' => $this->permitted_user_id,
        ]);

        return $dataProvider;
    }
}
