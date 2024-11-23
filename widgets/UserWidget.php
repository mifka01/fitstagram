<?php

namespace app\widgets;

use app\widgets\GroupWidget;
use Yii;

class UserWidget extends GroupWidget
{
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
