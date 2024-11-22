<?php

namespace app\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class UserWidget extends Widget
{
    public string $title;

    public ActiveDataProvider $provider;

    /**
     * @var string|array<mixed>|false $itemButtonLabel
     */
    public $itemButtonLabel = false;

    /**
     * @var string|array<mixed> $itemButtonRoute
     */
    public $itemButtonRoute = '';

    public string|false $actionButtonLabel = false;

    /**
     * @var string|array<mixed> $actionButtonRoute
     */
    public $actionButtonRoute = '';

    public string|false $updateButtonLabel = false;

    /**
     * @var string|array<mixed> $updateButtonRoute
     */
    public $updateButtonRoute = '';

    public bool $ajax = false;

    public string $emptyMessage = 'No users found.';

    public Model|false $searchModel;

    public string $searchParam = 'keyword';

    public string $itemView = '';

    public function init(): void
    {
        $this->emptyMessage = Yii::t('app/users', $this->emptyMessage);
        parent::init();
    }

    public function run(): string
    {
        return $this->render('user/_user', [
            'title' => $this->title,
            'provider' => $this->provider,
            'itemButtonLabel' => $this->itemButtonLabel,
            'itemButtonRoute' => $this->itemButtonRoute,
            'updateButtonLabel' => $this->updateButtonLabel,
            'updateButtonRoute' => $this->updateButtonRoute,
            'actionButtonLabel' => $this->actionButtonLabel,
            'actionButtonRoute' => $this->actionButtonRoute,
            'itemView' => $this->itemView,
            'ajax' => $this->ajax,
            'emptyMessage' => $this->emptyMessage,
            'searchModel' => $this->searchModel,
            'searchParam' => $this->searchParam,
        ]);
    }
}
