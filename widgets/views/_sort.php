<?php
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var array<string> $links
 * @var array<string, string> $containerOptions
 */


?>

<div <?= Html::renderTagAttributes($containerOptions) ?>>
    <?php foreach ($links as $link): ?>
        <?= $link ?>
    <?php endforeach; ?>
</div>
