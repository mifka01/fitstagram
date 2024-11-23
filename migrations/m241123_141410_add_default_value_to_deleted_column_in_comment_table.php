<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * class m241123_141410_add_default_value_to_deleted_column_in_comment_table
 */
class m241123_141410_add_default_value_to_deleted_column_in_comment_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%comment}}', 'deleted', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%comment}}', 'deleted', $this->boolean());
    }
}
