<?php
/**
 * @var yii\web\View $this
 * @var string $id
 * @var app\enums\tailwind\TailwindAlertType $type
 * @var array{alertClass: string, contentClass: string, iconClass: string, timeout: int} $options
 * @var string $body
 */

$timeout = $options['timeout'];

$js = <<<JS
    $(document).ready(function() {
        setTimeout(function() {
            $('#alert-$id').fadeOut('slow');
        }, $timeout);
    });
JS;

$this->registerJs($js);
?>

<div id='alert-<?= $id ?>' class="flex justify-center md:justify-end md:pe-4">
    <div class="<?= $options['alertClass'] ?>" role="alert">
        <div class="border-green-500 <?= $options['contentClass'] ?>">
            <i class="<?= $options['iconClass'] ?>" aria-hidden="true"></i>
            <span class="sr-only"><?= $type->getName() ?></span>
            <div>
                <?= $body ?>
            </div>
        </div>
    </div>
</div>
