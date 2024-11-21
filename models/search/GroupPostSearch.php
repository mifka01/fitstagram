<?php

namespace app\models\search;

use app\models\Post;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * GroupPostSearch represents the model behind the search form of `app\models\Post`.
 */
class GroupPostSearch extends Model
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array<string,string> $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = Post::find()->where(['group_id' => $params['id']])->with(['createdBy', 'tags', 'mediaFiles']);

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
}
