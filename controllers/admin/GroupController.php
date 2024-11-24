<?php

namespace app\controllers\admin;

use app\models\forms\GroupForm;
use app\models\Group;
use app\models\search\GroupMemberSearch;
use app\models\search\GroupSearch;
use app\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * GroupController implements the CRUD actions for Group model.
 */
class GroupController extends Controller
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
     * Lists all Group models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'admin';
        $searchModel = new GroupSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Group model.
     *
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = 'admin';
        $model = Group::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app/group', 'Group not found.'));
        }
        $userSearchModel = new GroupMemberSearch(['active' => 1]);
        $userSearchModel->load(Yii::$app->request->queryParams);

        $userProvider = $userSearchModel->search(array_merge(
            Yii::$app->request->queryParams,
            ['id' => $id]
        ));

        return $this->render('view', [
            'model' => $model,
            'userSearchModel' => $userSearchModel,
            'userProvider' => $userProvider,
        ]);
    }

    /**
     * Restore an existing Group model.
     * If restoration is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRestore($id)
    {
        $model = new GroupForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->restore()) {
            Yii::$app->session->setFlash('success', Yii::t('app/admin', 'Group has been restored.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/admin', 'Failed to restore group.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Group model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = new GroupForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app/admin', 'Group has been deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/admin', 'Failed to delete group.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     * @return Group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne(['id' => $id])) !== null) {
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
