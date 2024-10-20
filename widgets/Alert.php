<?php

namespace app\widgets;

use app\enums\tailwind\TailwindAlertType;
use app\widgets\tailwind\TailwindAlert;
use Yii;
use yii\base\Widget;

class Alert extends Widget
{
    /**
     * @var array<string, TailwindAlertType>
     */
    public $alertTypes = [
        'error'   => TailwindAlertType::ERROR,
        'success' => TailwindAlertType::SUCCESS,
        'info'    => TailwindAlertType::INFO,
    ];

    public function run(): void
    {
        $session = Yii::$app->session;

        $id = 0;
        foreach ($this->alertTypes as $type => $class) {
            $flash = $session->getFlash($type);

            foreach ((array) $flash as $message) {
                echo TailwindAlert::widget([
                    'id' => $id,
                    'type' => $class,
                    'body' => $message,
                ]);
                $id++;
            }

            $session->removeFlash($type);
        }
    }
}
