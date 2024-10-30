<?php

namespace app\controllers;

use app\models\Tag;
use yii\web\Controller;

class TagController extends Controller
{
    /**
     * List action for select2
     *
     * @param ?string $q
     * @param ?int $id
     * @return array<string, array<string, string>>
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
}
