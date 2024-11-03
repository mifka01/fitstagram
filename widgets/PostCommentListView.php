<?php

namespace app\widgets;

use app\models\Post;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

class PostCommentListView extends ListView
{
    public Post|null $post;

    public int|null $page = null;

    public const string PAGE_PARAM = 'page';

    public function run()
    {
        if (!$this->post) {
            return;
        }
        parent::run();
    }

    public function init():void
    {
        $page = $this->page ?? 1;

        $this->id = 'list-view-comments-' . $this->post?->id;
        $this->dataProvider = new ActiveDataProvider([
            'query' => $this->post?->getComments()->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 2,
                'pageParam' => self::PAGE_PARAM,
                'route' => '/post/comments',
                'params' => [
                    'id' => $this->post?->id,
                    self::PAGE_PARAM => $page
                ],
            ],
        ]);
        $this->itemView = function ($model) {
            return $this->render('_comment', [
                'model' => $model,
            ]);
        };
        $this->layout = "{items}\n{pager}";
        $this->pager = [
            'options' => ['class' => 'flex justify-center gap-2 my-4'],
            'linkOptions' => ['class' => 'px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50'],
            'activePageCssClass' => 'border-orange-500 bg-orange-50 text-orange-600',
            'disabledPageCssClass' => 'border-gray-200 text-gray-400 cursor-not-allowed',
        ];

        parent::init();
    }
}
