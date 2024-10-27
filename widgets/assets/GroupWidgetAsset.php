<?php

namespace app\widgets\assets;

use Yii;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\web\View;

class GroupWidgetAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/assets/src';

    /**
    * @var array<string> $js
    */
    public $js = [
        'js/group-description.js',
    ];
    
    /**
    * @var array<string> $depends
    */
    public $depends = [
        'app\assets\AppAsset',
    ];

    public function init(): void
    {
        parent::init();

        $translations = [
        'showMore' => Yii::t('app/model', 'Show more'),
        'showLess' => Yii::t('app/model', 'Show less'),
        ];

        $js = "window.groupWidgetTranslations = " . Json::encode($translations) . ";";
        Yii::$app->view->registerJs($js, View::POS_HEAD);
    }
}
