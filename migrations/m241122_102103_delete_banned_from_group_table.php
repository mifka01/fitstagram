<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241122_102103_delete_banned_from_group_table
 */
class m241122_102103_delete_banned_from_group_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->dropColumn('group', 'banned');
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->addColumn('group', 'banned', $this->boolean()->defaultValue(false));
    }
}
