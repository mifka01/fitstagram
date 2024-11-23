<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * class m241123_161223_add_banned_column_to_group_table
 */
class m241123_161223_add_banned_column_to_group_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->addColumn('group', 'banned', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropColumn('group', 'banned');
    }
}
