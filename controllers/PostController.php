<?php

namespace app\controllers;

use app\models\forms\PostForm;
use app\models\search\UserPostSearch;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Post controller
 */
class PostController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new UserPostSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'postDataProvider' => $provider,
        ]);
    }

    /**
     * Create new post
     *
     * @return Response|string
     */
    public function actionCreate(): Response|string
    {
        $model = new PostForm();

        if ($model->load(Yii::$app->request->post())) {
            // Handle media files upload
            $model->mediaFiles = UploadedFile::getInstances($model, 'mediaFiles');
            if ($model->validate()) {
                // Save the post
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
                        foreach ($model->mediaFiles as $file) {
                            $filePath = 'images/' . $file->baseName . '.' . $file->extension;
                            $file->saveAs($filePath);
                        }
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('app/post', 'Post has been created.'));
                        return $this->goHome();
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('app/post', 'Failed to create post.'));
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
