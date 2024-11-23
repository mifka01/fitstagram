<?php

namespace app\controllers;

use app\models\forms\MediaFileForm;
use app\models\forms\PostForm;
use app\models\forms\PostRemoveForm;
use app\models\forms\PostVoteForm;
use app\models\MediaFile;
use app\models\Post;
use app\models\search\UserPostSearch;
use app\models\sort\PostSort;
use app\services\PostPermissionService;
use app\widgets\PostCommentListView;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Post controller
 */
class PostController extends Controller
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
                'only' => ['create', 'vote'],
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'vote'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['managePost'],
                        'roleParams' => [
                            'postId' => Yii::$app->request->get('id'),
                        ],
                    ],
                    [
                        'actions' => ['comments', 'get-media-file'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $sort = new PostSort();

        $searchModel = new UserPostSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);
        $provider->setSort($sort);

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
            $model->mediaFiles = UploadedFile::getInstances($model, 'mediaFiles');
            if ($model->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save()) {
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

    public function actionUpdate(int $id): string
    {
        return '';
    }

    public function actionVote(): mixed
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PostVoteForm();

        if (($model->load(Yii::$app->request->post()) && $model->validate() && $model->vote())) {
            $post = Post::findOne($model->postId);

            return [
            'success' => true,
            'html' => $this->renderPartial('_vote', [
                'model' => $post,
            ]),
            ];
        }

        Yii::$app->session->setFlash('error', Yii::t('app/post', 'Failed to vote.'));
        return [
            'success' => false,
            'errors' => $model->errors,
        ];
    }

    public function actionComments(int $id): string
    {
        if (!PostPermissionService::checkPostPermission(Yii::$app->user->id, $id)) {
            throw new NotFoundHttpException(Yii::t('app/error', 'Post not found.'));
        }

        return PostCommentListView::widget([
            'post' => Post::findOne($id),
            'page' => Yii::$app->request->getQueryParam(PostCommentListView::PAGE_PARAM, null),
        ]);
    }

    public function actionGetMediaFile(string $path): Response
    {
        $mediaFile = MediaFile::findOne(['path' => $path]);

        if ($mediaFile === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'Media file not found.'));
        }

        if ($mediaFile->path === null) {
            throw new NotFoundHttpException(Yii::t('app/error', 'Post not found.'));
        }

        $post = $mediaFile->post;

        if (!PostPermissionService::checkPostPermission(Yii::$app->user->id, $post->id)) {
            throw new NotFoundHttpException(Yii::t('app/error', 'Post not found.'));
        }

        $path = Yii::getAlias('@app') . DIRECTORY_SEPARATOR . MediaFileForm::UPLOAD_PATH . DIRECTORY_SEPARATOR . $mediaFile->path;

        return Yii::$app->response->sendFile($path, $mediaFile->name);
    }

    public function actionDelete(int $id): Response
    {
        $model = new PostRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app/post', 'Post has been deleted.'));
            return $this->goHome();
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/post', 'Failed to delete post.'));
            return $this->goHome();
        }
    }
}
