<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $user */
$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/verify-email', 'token' => $user->verification_token]);
?>

<div>
    <h1 class="header"><?= Yii::t('app/mail', 'Verify Your Email') ?></h1>
    <p class="subheader"><?= Yii::t('app/mail', 'Thanks for signing up!') ?></p>

    <div class="body-content">
        <p class="body-text">
            <?= Yii::t('app/mail', 'Hello') ?> <?= Html::encode($user->username) ?>,
        </p>
        <p class="body-text">
            <?= Yii::t('app/mail', 'Please verify your email address to complete your registration. Click the button below to verify your email:') ?>
        </p>
        <div style="text-align: center;">
            <?= Html::a(
                Yii::t('app/mail', 'Verify Email Address'),
                $verifyLink,
                ['class' => 'button']
            ) ?>
        </div>
        <p class="body-text"><?= Yii::t('app/mail', 'Or copy and paste this link in your browser:') ?></p>
        <p class="link"><?= Html::encode($verifyLink) ?></p>

        <div class="footer">
            <p><?= Yii::t('app/mail', 'If you did not create an account, no further action is required.') ?></p>
            <p><?= Yii::t('app/mail', 'Thanks,') ?><br><?= Yii::t('app/mail', 'The {0} Team', [Yii::$app->name]) ?></p>
        </div>
    </div>
</div>

