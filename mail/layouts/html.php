<?php
use yii\helpers\Html;

/** @var \yii\web\View $this view component instance */
/** @var \yii\mail\MessageInterface $message the message being composed */
/** @var string $content main view render result */

?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <style type="text/css">
        /* General Reset */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            border-collapse: collapse !important;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }
        /* Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9fafb; /* Equivalent of bg-gray-50 */
        }
        /* Header */
        .header {
            font-size: 24px;
            font-weight: bold;
            color: #1F2937; /* Equivalent of text-gray-900 */
            text-align: center;
        }
        .subheader {
            font-size: 14px;
            color: #6B7280; /* Equivalent of text-gray-600 */
            text-align: center;
            margin-top: 8px;
        }
        /* Body */
        .body-content {
            padding: 32px;
            background-color: #ffffff;
            color: #374151; /* Equivalent of text-gray-700 */
        }
        .body-text {
            font-size: 16px;
            color: #374151;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #DD6B20; /* Equivalent of bg-orange-600 */
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            margin-top: 24px;
            border-radius: 4px;
        }

        a.button {
            color: #ffffff;
        }

        .link {
            color: #6B7280;
            font-size: 14px;
            word-wrap: break-word;
        }
        /* Footer */
        .footer {
            font-size: 14px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
            padding-top: 16px;
            margin-top: 32px;
        }
    </style>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
        <div class="container">
            <?= $content ?>
        </div>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
