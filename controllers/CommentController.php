<?php

namespace app\controllers;

use app\models\forms\CommentForm;
use app\models\Post;
use app\widgets\PostCommentListView;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Comment controller
 */
class CommentController extends Controller
{
    /**
     * {@inheritDoc}
     *
     * @return array<string, array<string, mixed>>
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Create new comment
     *
     * @return mixed
     */
    public function actionCreate(): mixed
    {
        $model = new CommentForm();

         \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            if ($model->validate() && $model->save()) {
                $model->getComment()?->refresh();
                $post = Post::findOne($model->postId);
                return [
                'success' => true,
                    'html' => PostCommentListView::widget([
                        'post' => $post,
                    ]) .
                    $this->renderPartial('/comment/create', [
                        'model' => new CommentForm(['postId' => $post?->id]),
                    ])

                ];
            } else {
                return [
                    'success' => false,
                    'errors' => $model->errors,
                ];
            }
        }

        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }
}
