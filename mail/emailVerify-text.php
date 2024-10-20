<?php
/** @var yii\web\View $this */
/** @var app\models\User $user */
use Yii;

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/verify-email', 'token' => $user->verification_token]);
?>

<?= Yii::t('app/mail', 'Hello') ?> <?= $user->username ?>

<?= Yii::t('app/mail', 'Thank you for signing up! Please follow the link below to verify your email address:') ?>

<?= $verifyLink ?>

<?= Yii::t('app/mail', 'If you did not create an account, no further action is required.') ?>

<?= Yii::t('app/mail', 'Thanks,') ?>
<?= Yii::t('app/mail', 'The {0} Team', [Yii::$app->name]);
