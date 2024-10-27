<?php

namespace app\widgets;

use app\widgets\assets\GroupWidgetAsset;
use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class GroupWidget extends Widget
{
    public string $title;

    public ActiveDataProvider $provider;

    public string|false $itemButtonLabel = false;

    /**
     * @var string|array<mixed> $itemButtonRoute
     */
    public $itemButtonRoute = '';

    public string|false $actionButtonLabel = false;

    /**
     * @var string|array<mixed> $actionButtonRoute
     */
    public $actionButtonRoute = '';

    public bool $ajax = false;

    public string $emptyMessage = 'No groups found.';

    public Model|false $searchModel;

    public string $searchParam = 'keyword';

    public function init(): void
    {
        $this->emptyMessage = Yii::t('app/model', $this->emptyMessage);
        parent::init();

        GroupWidgetAsset::register($this->view);
    }

    public function run(): string
    {
        return $this->render('group/_group', [
            'title' => $this->title,
            'provider' => $this->provider,
            'itemButtonLabel' => $this->itemButtonLabel,
            'itemButtonRoute' => $this->itemButtonRoute,
            'actionButtonLabel' => $this->actionButtonLabel,
            'actionButtonRoute' => $this->actionButtonRoute,
            'ajax' => $this->ajax,
            'emptyMessage' => $this->emptyMessage,
            'searchModel' => $this->searchModel,
            'searchParam' => $this->searchParam,
        ]);
    }
}
