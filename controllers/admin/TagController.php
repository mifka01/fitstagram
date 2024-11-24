<?php

namespace app\controllers\admin;

use app\models\search\TagSearch;
use app\models\Tag;
use app\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TagController implements the CRUD actions for Tag model.
 */
class TagController extends Controller
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
     * Lists all Tag models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'admin';
        $searchModel = new TagSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Tag model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app/admin', 'Tag has been deleted.'));
        }
        Yii::$app->session->setFlash('error', Yii::t('app/admin', 'An error occurred while deleting the tag.'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tag model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     * @return Tag the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne(['id' => $id])) !== null) {
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
}
