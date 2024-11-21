<?php

namespace app\controllers;

use app\models\forms\PostForm;
use app\models\forms\PostVoteForm;
use app\models\Post;
use app\models\search\UserPostSearch;
use app\widgets\PostCommentListView;
use Yii;
use yii\data\Sort;
use yii\web\Controller;
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
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['vote'],
                        'allow' => true,
                        'roles' => ['@'],
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
        $sort = new Sort(
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
        return PostCommentListView::widget([
        'post' => Post::findOne($id),
        'page' => Yii::$app->request->getQueryParam(PostCommentListView::PAGE_PARAM, null),
        ]);
    }
}
