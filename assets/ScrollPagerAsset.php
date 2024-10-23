<?php

namespace app\assets;

use yii\web\AssetBundle;

class ScrollPagerAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    /**
    * @var array<string> $js
    */
    public $js = [
        'js/scrollPager.js'
    ];

    /**
    * @var array<string> $depends
    */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
