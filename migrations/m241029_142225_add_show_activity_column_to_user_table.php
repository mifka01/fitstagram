<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241029_142225_add_show_activity_column_to_user_table
 */
class m241029_142225_add_show_activity_column_to_user_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'show_activity', $this->integer()->defaultValue(1) . ' AFTER banned');

        $this->createIndex(
            '{{%idx-user-show_activity}}',
            '{{%user}}',
            'show_activity'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-user-show_activity}}',
            '{{%user}}'
        );

        $this->dropColumn('{{%user}}', 'show_activity');
    }
}
