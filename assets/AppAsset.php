<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    /**
    * @var array<string> $css
    */
    public $css = [
        'css/tailwind.css',
    ];

    /**
    * @var array<string> $js
    */
    public $js = [
    ];

    /**
    * @var array<string> $depends
    */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
