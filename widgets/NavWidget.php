<?php

namespace app\widgets;

use yii\base\Widget;

class NavWidget extends Widget
{
    /**
     * @var array<string, string|array<string>> $items
     */
    public array $items = [];

    public string|bool $brandLabel = false;

    public string|bool $brandImage = false;

    public string|bool $brandUrl = false;

    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
        return $this->render('_nav', [
            'items' => $this->items,
            'brandLabel' => $this->brandLabel,
            'brandImage' => $this->brandImage,
            'brandUrl' => $this->brandUrl,
        ]);
    }
}
