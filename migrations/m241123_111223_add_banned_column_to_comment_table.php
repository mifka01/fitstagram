<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * class m241123_111223_add_banned_column_to_comment_table
 */
class m241123_111223_add_banned_column_to_comment_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->addColumn('comment', 'banned', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropColumn('comment', 'banned');
    }
}
