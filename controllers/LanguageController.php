<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;

/**
 * LanguageController implements actions to change language.
 */
class LanguageController extends Controller
{
    /**
     * {@inheritDoc}
     *
     * @return array<mixed>
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['change'],
                            'allow' => true,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Changes the application language.
     *
     * @param string $language
     * @return \yii\web\Response
     */
    public function actionChange($language)
    {
        $cookie = new Cookie([
            'name' => 'language',
            'value' => $language,
            'expire' => time() + 3600*24*30
        ]);
        Yii::$app->response->getCookies()->add($cookie);
        return $this->goBack(Yii::$app->request->referrer ?: null);
    }
}
