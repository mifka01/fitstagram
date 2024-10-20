<?php
// File: app/enums/tailwind/TailwindAlertType.php
namespace app\enums\tailwind;

enum TailwindAlertType: int
{
    case ERROR = 0;
    case SUCCESS = 1;
    case INFO = 2;

    /**
     * @return array{bg: string, border: string, text: string}
     */
    public function getColorClasses(): array
    {
        return match ($this) {
            self::SUCCESS => [
                'text' => 'text-green-800',
                'bg' => 'bg-green-50',
                'border' => 'border-green-500',
            ],
            self::ERROR => [
                'text' => 'text-red-800',
                'bg' => 'bg-red-50',
                'border' => 'border-red-500',
            ],
            self::INFO => [
                'text' => 'text-blue-800',
                'bg' => 'bg-blue-50',
                'border' => 'border-blue-500',
            ]
        };
    }

    public function getName(): string
    {
        return match ($this) {
            self::SUCCESS => 'Success',
            self::ERROR => 'Error',
            self::INFO => 'Info'
        };
    }

    public function getIconClass(): string
    {
        return match ($this) {
            self::SUCCESS => 'fas fa-check-circle',
            self::ERROR => 'fas fa-exclamation-circle',
            self::INFO => 'fas fa-info-circle'
        };
    }
}
