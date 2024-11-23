<?php

namespace app\controllers;

use app\models\Tag;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TagController extends Controller
{
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
            throw new NotFoundHttpException(Yii::t('app/model', 'Tag not found.'));
        }

        if (!$tag->delete()) {
            Yii::$app->session->setFlash('error', Yii::t('app/model', 'Failed to delete tag.'));
            return $this->redirect(['group/view', 'id' => $id]);
        }

        Yii::$app->session->setFlash('success', Yii::t('app/model', 'Tag has been deleted.'));
        return $this->goHome();
    }
}
