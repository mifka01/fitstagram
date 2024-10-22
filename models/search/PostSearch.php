<?php

namespace app\models\search;

use app\models\Post;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostSearch represents the model behind the search form of `app\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * {@inheritDoc}
     *
     * @return array<int, array<int|string, array<int|string, string>|bool|int|string>>
     */
    public function rules(): array
    {
        return [
            [['id', 'created_by', 'is_private', 'is_group_post', 'upvote_count', 'downvote_count', 'deleted'], 'integer'],
            [['description', 'created_at', 'updated_at', 'place'], 'safe'],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string,string>
     */
    public function scenarios(): array
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
    public function search($params): ActiveDataProvider
    {
        $query = Post::find();

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
            'created_by' => $this->created_by,
            'is_group_post' => $this->is_group_post,
            'is_private' => $this->is_private,
            'upvote_count' => $this->upvote_count,
            'downvote_count' => $this->downvote_count,
            'deleted' => $this->deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])->andFilterWhere(['like', 'place', $this->place]);

        return $dataProvider;
    }
}
