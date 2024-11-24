<?php

namespace app\widgets\tailwind;

use app\enums\tailwind\TailwindAlertType;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class TailwindAlert extends Widget
{
    public int $id;

    public TailwindAlertType $type;

    public string $body;

    /**
     * @var array{alertClass?: string, contentClass?: string, iconClass?: string, timeout?: int} $options
     */
    public array $options = [];

    private const DEFAULT_CLASSES = [
        'absolute',
        'pt-4',
        'z-50',
        'max-w-sm',
        'animate-in',
        'fade-in',
        'slide-in-from-top-1',
        'duration-300'
    ];

    public function init(): void
    {
        parent::init();
        $this->initializeOptions();
    }

    protected function initializeOptions(): void
    {

        $this->options['timeout'] = ArrayHelper::getValue($this->options, 'timeout', 2000);

        $classes = self::DEFAULT_CLASSES;
        $classes[] = ArrayHelper::getValue($this->options, 'class', '');

        $colorClasses = $this->type->getColorClasses();
        
        $this->options['alertClass'] = implode(' ', $classes);
        
        $this->options['contentClass'] = implode(' ', [
            'flex',
            'items-center',
            'p-4',
            'text-sm',
            'rounded-lg',
            'shadow-sm',
            'border',
            $colorClasses['text'],
            $colorClasses['bg'],
            $colorClasses['border'],
        ]);

        $this->options['iconClass'] = implode(' ', [
            $this->type->getIconClass(),
            'flex-shrink-0',
            'inline',
            'w-4',
            'h-4',
            'me-3'
        ]);
    }

    public function run(): string
    {
        return $this->render('_alert', [
            'id' => $this->id,
            'type' => $this->type,
            'body' => $this->body,
            'options' => $this->options,
        ]);
    }
}
