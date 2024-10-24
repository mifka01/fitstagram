<?php

namespace app\components;

use Yii;

/**
 * Modified ReCaptchaValidator3 class to prevent validation on Ajax requests.
 */
class ReCaptcha3Validator extends \himiklab\yii2\recaptcha\ReCaptchaValidator3
{
    /**
     * @param string|array<mixed> $value
     * @return array<mixed>|null
     */
    protected function validateValue($value)
    {
        if (Yii::$app->request->isAjax) {
            return null;
        }
        return parent::validateValue($value);
    }
}
