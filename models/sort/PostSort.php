<?php

namespace app\models\sort;

use Yii;
use yii\data\Sort;

class PostSort extends Sort
{
    public function __construct()
    {
        parent::__construct(
            [
            'attributes' => [
                'new' => [
                    'asc' => ['created_at' => SORT_DESC],
                    'desc' => ['created_at' => SORT_ASC],
                    'label' => Yii::t('app/post', 'New'),
                    ],
                    'old' => [
                        'asc' => ['created_at' => SORT_ASC],
                        'desc' => ['created_at' => SORT_DESC],
                        'label' => Yii::t('app/post', 'Old'),
                    ],
                    'best' => [
                        'asc' => new \yii\db\Expression('upvote_count - downvote_count DESC'),
                        'desc' => new \yii\db\Expression('upvote_count - downvote_count ASC'),
                        'label' => Yii::t('app/post', 'Best'),
                    ],
                    'worst' => [
                        'asc' => new \yii\db\Expression('upvote_count - downvote_count ASC'),
                        'desc' => new \yii\db\Expression('upvote_count - downvote_count DESC'),
                        'label' => Yii::t('app/post', 'Worst'),
                    ],
                'controversial' => [
                        'asc' => new \yii\db\Expression('ABS(upvote_count - downvote_count) / (upvote_count + downvote_count + 1) ASC'),
                        'desc' => new \yii\db\Expression('ABS(upvote_count - downvote_count) / (upvote_count + downvote_count + 1) DESC'),
                        'label' => Yii::t('app/post', 'Controversial'),
                    ],
                ],
            'defaultOrder' => ['new' => SORT_ASC],
            ]
        );
    }
}
