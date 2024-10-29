<?php

namespace app\controllers;

use app\models\forms\CommentForm;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Comment controller
 */
class CommentController extends Controller
{
    /**
     * Create new comment
     *
     * @return mixed
     */
    public function actionCreate(): mixed
    {
        $model = new CommentForm();

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->getIsAjax()) {
            if (!$model->validate() || !$model->save()) {
                return \yii\widgets\ActiveForm::validate($model);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
