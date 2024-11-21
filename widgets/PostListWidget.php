<?php

namespace app\widgets;

use app\assets\PostListAsset;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class PostListWidget extends Widget
{
    public ActiveDataProvider $dataProvider;

    public bool $showFilter = true;

    public bool $ajaxFilter = true;

    public function run(): string
    {
        PostListAsset::register($this->view);
        if ($this->dataProvider->totalCount === 0) {
            $this->showFilter = false;
        }

        return $this->render('post/_post-list', [
            'dataProvider' => $this->dataProvider,
            'showFilter' => $this->showFilter,
            'ajaxFilter' => $this->ajaxFilter,
        ]);
    }

    public function init(): void
    {
        parent::init();
    }
}
