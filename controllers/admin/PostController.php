<?php

namespace app\controllers\admin;

use app\models\forms\PostRemoveForm;
use app\models\Group;
use app\models\Post;
use app\models\search\PostSearch;
use app\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * @inheritDoc
     * @return array<mixed>
     */
    public function behaviors()
    {
        return
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ];
    }

    /**
     * Lists all Post models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'admin';
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     *
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = 'admin';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Restore an existing Post model.
     * If restoration is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRestore($id)
    {
        $model = new PostRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->restore()) {
            Yii::$app->session->setFlash('success', Yii::t('app/post', 'Post has been restored.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/post', 'Failed to restore post.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = new PostRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app/post', 'Post has been deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/post', 'Failed to delete post.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app/admin', 'The requested page does not exist.'));
    }

    /**
     * @param string|null $q
     * @param int|null $id
     * @return array<mixed>
     */
    public function actionUserList($q = null, $id = null): array
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::findOne($id)?->username];
        }
        if (!is_null($q)) {
            $query = User::find()
                ->select(['id', 'username AS text'])
                ->where(['like', 'username', $q])
                ->limit(20)
                ->asArray();
            $data = $query->all();
            $out['results'] = array_values($data);
        }
        return $out;
    }

    /**
     * @param string|null $q
     * @param int|null $id
     * @return array<mixed>
     */
    public function actionGroupList($q = null, $id = null): array
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::findOne($id)?->username];
        }
        if (!is_null($q)) {
            $query = Group::find()
                ->select(['id', 'name AS text'])
                ->where(['like', 'name', $q])
                ->limit(20)
                ->asArray();
            $data = $query->all();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
