<?php

namespace app\models;

use app\models\GroupJoinRequest;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GroupJoinRequestSearch represents the model behind the search form of `app\models\GroupJoinRequest`.
 */
class GroupJoinRequestSearch extends GroupJoinRequest
{
    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'created_by', 'pending', 'declined', 'accepted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = GroupJoinRequest::find();

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
            'id' => $this->id,
            'group_id' => $this->group_id,
            'created_by' => $this->created_by,
            'pending' => $this->pending,
            'declined' => $this->declined,
            'accepted' => $this->accepted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
