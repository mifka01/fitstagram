<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241016_172257_create_group_member_table
 */
class m241016_172257_create_group_member_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%group_member}}', [
            'group_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ], $tableOptions);

        // Create a composite primary key for (user_id, group_id)
        $this->addPrimaryKey('{{%pk-group_member}}', '{{%group_member}}', ['user_id', 'group_id']);

        $this->createIndex('{{%idx-group_member-group_id}}', '{{%group_member}}', 'group_id');

        $this->addForeignKey(
            '{{%fk-group_member-group_id}}',
            '{{%group_member}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-group_member-user_id}}',
            '{{%group_member}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-group_member-user_id}}', '{{%group_member}}');
        $this->dropForeignKey('{{%fk-group_member-group_id}}', '{{%group_member}}');
        $this->dropIndex('{{%idx-group_member-group_id}}', '{{%group_member}}');
        $this->dropTable('{{%group_member}}');
    }
}
