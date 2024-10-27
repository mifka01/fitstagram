<?php
namespace app\enums;

use Yii;

enum GroupType: string
{
    case PUBLIC = 'public';
    case OWNED = 'owned';
    case JOINED = 'joined';

    /**
     * Get the display name for the group type
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::PUBLIC => Yii::t('app/group', 'Public Groups'),
            self::OWNED => Yii::t('app/group', 'Created Groups'),
            self::JOINED => Yii::t('app/group', 'Joined Groups'),
        };
    }

    /**
     * Check if this type requires authentication
     */
    public function requiresAuth(): bool
    {
        return match ($this) {
            self::PUBLIC => false,
            self::OWNED, self::JOINED => true,
        };
    }
}
