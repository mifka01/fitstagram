<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */
use Yii;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset-password', 'token' => $user->password_reset_token]);
?>

<?= Yii::t('app/mail', 'Hello') ?> <?= $user->username ?>,

<?= Yii::t('app/mail', 'You can reset your password by following the link below:') ?>

<?= $resetLink ?>

<?= Yii::t('app/mail', 'If you did not request a password reset, no further action is required.') ?>

<?= Yii::t('app/mail', 'Thanks,') ?>
<?= Yii::t('app/mail', 'The {0} Team', [Yii::$app->name]);

