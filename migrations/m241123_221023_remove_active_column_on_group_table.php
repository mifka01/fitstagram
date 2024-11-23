<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * class m241123_221023_remove_active_column_on_group_table
 */
class m241123_221023_remove_active_column_on_group_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->dropColumn('group', 'active');
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->addColumn('group', 'active', $this->boolean()->defaultValue(false));
    }
}
