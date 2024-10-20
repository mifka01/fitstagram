<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;

class LanguageSelector extends Widget
{
    /**
     * @var array<string, string>
     */
    private array $languages = [];

    public function init(): void
    {
        $this->languages = Yii::$app->params['languages'];
    }

    public function run(): string
    {
        return $this->render('_languageSelector', [
            'languages' => $this->languages,
            'currentLanguage' => Yii::$app->language,
        ]);
    }
}
