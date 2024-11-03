<?php

namespace app\controllers;

use app\models\forms\CommentForm;
use Yii;
use yii\web\Controller;

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

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->getIsAjax()) {
            if ($model->validate() && $model->save()) {
                $model = new CommentForm(['postId' => $model->postId]);
            }
        }

        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }
}
