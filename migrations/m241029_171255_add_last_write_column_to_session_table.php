<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * class m241029_171255_add_last_write_column_to_session_table
 */
class m241029_171255_add_last_write_column_to_session_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%session}}', 'last_write', $this->integer()->defaultValue(0));

        $this->createIndex(
            '{{%idx-session-last_write}}',
            '{{%session}}',
            'last_write'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-session-last_write}}',
            '{{%session}}'
        );

        $this->dropColumn('{{%session}}', 'last_write');
    }
}
