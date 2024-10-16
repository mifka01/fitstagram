<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241016_172729_group_join_request_table
 */
class m241016_172729_group_join_request_table extends Migration
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

        $this->createTable('{{%group_join_request}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'pending' => $this->boolean(),
            'declined' => $this->boolean(),
            'accepted' => $this->boolean(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull(),
        ], $tableOptions);

        // Create a unique index for (created_by, group_id)
        $this->createIndex('{{%idx-group_join_request-created_by-group_id}}', '{{%group_join_request}}', ['created_by', 'group_id'], true);

        $this->addForeignKey(
            '{{%fk-group_join_request-group_id}}',
            '{{%group_join_request}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-group_join_request-created_by}}',
            '{{%group_join_request}}',
            'created_by',
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
        $this->dropForeignKey('{{%fk-group_join_request-created_by}}', '{{%group_join_request}}');
        $this->dropForeignKey('{{%fk-group_join_request-group_id}}', '{{%group_join_request}}');
        $this->dropIndex('{{%idx-group_join_request-created_by-group_id}}', '{{%group_join_request}}');
        $this->dropTable('{{%group_join_request}}');
    }
}
