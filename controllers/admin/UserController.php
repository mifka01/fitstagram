<?php

namespace app\controllers\admin;

use app\models\forms\UserRemoveForm;
use app\models\search\UserSearch;
use app\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'admin';
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Restore an existing User model.
     * If restoration is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRestore($id)
    {
        $model = new UserRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->restore()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'User has been restored.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Failed to restore user.'));
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
        $model = new UserRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'User has been deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Failed to delete user.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Ban an existing User model.
     * If ban is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionBan($id): Response
    {
        $model = new UserRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->ban()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'User has been deleted.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Failed to delete user.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Unban an existing User model.
     * If Unban is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUnban($id): Response
    {
        $model = new UserRemoveForm();
        $model->id = $id;
        $model->load(Yii::$app->request->post());

        if ($model->unban()) {
            Yii::$app->session->setFlash('success', Yii::t('app/user', 'User has been unbanned.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Failed to unban user.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Make user a moderator.
     *
     * @param int $id ID
     * @return Response
     */
    public function actionMakeModerator($id)
    {
        $model = User::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app/admin', 'The requested page does not exist.'));
        }
        $role = Yii::$app->authManager?->getRole('moderator');
        if ($role === null) {
            throw new NotFoundHttpException(Yii::t('app/admin', 'The requested page does not exist.'));
        }

        if (Yii::$app->authManager?->assign($role, $model->id)) {
            Yii::$app->session->setFlash('success', Yii::t('app/admin', 'User has been made a moderator.'));
            $model->save(false);
        }
        Yii::$app->session->setFlash('error', Yii::t('app/admin', 'Failed to make user a moderator.'));
        return $this->redirect(['index']);
    }

    /**
     * Remove moderator role from user.
     *
     * @param int $id ID
     * @return Response
     */
    public function actionRemoveModerator($id)
    {
        $model = User::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app/admin', 'The requested page does not exist.'));
        }
        $role = Yii::$app->authManager?->getRole('moderator');
        if ($role === null) {
            throw new NotFoundHttpException(Yii::t('app/admin', 'The requested page does not exist.'));
        }

        if (Yii::$app->authManager?->revoke($role, $model->id)) {
            Yii::$app->session->setFlash('success', Yii::t('app/admin', 'User has been removed from moderators.'));
            $model->save(false);
        }
        Yii::$app->session->setFlash('error', Yii::t('app/admin', 'Failed to remove user from moderators.'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app/admin', 'The requested page does not exist.'));
    }
}
