<?php

namespace app\assets;

use yii\web\AssetBundle;

class PostListAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    /**
    * @var array<string> $js
    */
    public $js = [
        'js/postListWidgetAjax.js'
    ];

    /**
    * @var array<string> $depends
    */
    public $depends = [
        'app\assets\ScrollPagerAsset',
    ];
}
