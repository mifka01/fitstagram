<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * class m241122_105434_add_banned_column_to_post_table
 */
class m241122_105434_add_banned_column_to_post_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%post}}', 'banned', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%post}}', 'banned');
    }
}
