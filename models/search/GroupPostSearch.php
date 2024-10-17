<?php

namespace app\models;

use app\models\GroupPost;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GroupPostSearch represents the model behind the search form of `app\models\GroupPost`.
 */
class GroupPostSearch extends GroupPost
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'post_id'], 'integer'],
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
        $query = GroupPost::find();

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
            'group_id' => $this->group_id,
            'post_id' => $this->post_id,
        ]);

        return $dataProvider;
    }
}
