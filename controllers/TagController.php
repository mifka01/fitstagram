<?php

namespace app\controllers;

use app\models\search\TagPostSearch;
use app\models\sort\PostSort;
use app\models\Tag;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TagController extends Controller
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
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * List action for select2
     *
     * @param ?string $q
     * @param ?int $id
     * @return array<string, array<mixed>>
     */
    public function actionList($q = null, $id = null): array
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $data = Tag::find()->where(['like', 'name', $q])->limit(20)->select('id, name AS text')->asArray()->all();
            $out['results'] = $data;
        } elseif ($id > 0) {
            $tag = Tag::findOne($id);
            if ($tag) {
                $out['results'] = ['id' => $id, 'text' => $tag->name];
            }
        }
        return $out;
    }

    public function actionDelete(int $id): Response
    {
        $tag = Tag::findOne($id);
        if ($tag === null) {
            throw new NotFoundHttpException(Yii::t('app/tag', 'Tag not found.'));
        }

        if (!$tag->delete()) {
            Yii::$app->session->setFlash('error', Yii::t('app/tag', 'Failed to delete tag.'));
            return $this->redirect(['group/view', 'id' => $id]);
        }

        Yii::$app->session->setFlash('success', Yii::t('app/tag', 'Tag has been deleted.'));
        return $this->goHome();
    }

    public function actionView(int $id): string
    {
        $sort = new PostSort();
        $tag = Tag::findOne($id);
        if ($tag === null) {
            throw new NotFoundHttpException(Yii::t('app/tag', 'Tag not found.'));
        }


        $searchModel = new TagPostSearch();
        $searchModel->tagId = $id;
        $provider = $searchModel->search(Yii::$app->request->queryParams);
        $provider->setSort($sort);

        $recentPostTag = (new \yii\db\Query())
            ->select('*')
            ->from('post_tag')
            ->where(['tag_id' => $id])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(1)
            ->one();

        if (!is_array($recentPostTag)) {
            throw new NotFoundHttpException(Yii::t('app/tag', 'Tag not found.'));
        }

        return $this->render('view', [
            'model' => $tag,
            'ownerUsername' => $tag->createdBy->username,
            'postDataProvider' => $provider,
            'lastUse' => $recentPostTag['created_at'],
            'countPosts' => $tag->getPosts()->count(),
        ]);
    }
}
