<?php

namespace app\widgets;

use yii\base\Widget;

class NavWidget extends Widget
{
    /**
     * @var array<int, array{label: string, route: string|array<string|array<string, string>>, visible?: bool, icon?: string}> $items
     */
    public array $items = [];

    /**
     * @var array<int, array{label: string, route: string|array<string|array<string, string>>, visible?:bool, icon?: string}>|false $actionButton
     */
    public array $actionButton;

    public string|false $brandLabel = false;

    public string|false $brandImage = false;

    /**
     * @var string|array<string>|false $brandUrl
     */
    public string|array|false $brandUrl = false;

    public function init(): void
    {
        parent::init();
    }

    public function run(): string
    {
        return $this->render('_nav', [
            'items' => $this->items,
            'actionButton' => $this->actionButton,
            'brandLabel' => $this->brandLabel,
            'brandImage' => $this->brandImage,
            'brandUrl' => $this->brandUrl,
        ]);
    }
}
