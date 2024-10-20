<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/reset-password', 'token' => $user->password_reset_token]);
?>

<div>
    <h1 class="header"><?= Yii::t('app/mail', 'Reset Your Password') ?></h1>
    <p class="subheader"><?= Yii::t('app/mail', 'Need to reset your password?') ?></p>

    <div class="body-content">
        <p class="body-text">
            <?= Yii::t('app/mail', 'Hello') ?> <?= Html::encode($user->username) ?>,
        </p>
        <p class="body-text">
            <?= Yii::t('app/mail', 'You can reset your password by clicking the button below:') ?>
        </p>
        <div style="text-align: center;">
            <?= Html::a(
                Yii::t('app/mail', 'Reset Password'),
                $resetLink,
                ['class' => 'button']
            ) ?>
        </div>
        <p class="body-text"><?= Yii::t('app/mail', 'Or copy and paste this link in your browser:') ?></p>
        <p class="link"><?= Html::encode($resetLink) ?></p>

        <div class="footer">
            <p><?= Yii::t('app/mail', 'If you did not request a password reset, no further action is required.') ?></p>
            <p><?= Yii::t('app/mail', 'Thanks,') ?><br><?= Yii::t('app/mail', 'The {0} Team', [Yii::$app->name]) ?></p>
        </div>
    </div>
</div>

