<?php

use yii\db\Migration;

/**
 * Class m241016_172257_create_table_group_users
 */
class m241016_172257_create_table_group_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%group_users}}', [
            'group_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ], $tableOptions);

        // Create a composite primary key for (user_id, group_id)
        $this->addPrimaryKey('{{%pk-group_users}}', '{{%group_users}}', ['user_id', 'group_id']);

        $this->createIndex('{{%idx-group_users-group_id}}', '{{%group_users}}', 'group_id');

        $this->addForeignKey(
            '{{%fk-group_users-group_id}}',
            '{{%group_users}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-group_users-user_id}}',
            '{{%group_users}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-group_users-user_id}}', '{{%group_users}}');
        $this->dropForeignKey('{{%fk-group_users-group_id}}', '{{%group_users}}');
        $this->dropIndex('{{%idx-group_users-group_id}}', '{{%group_users}}');
        $this->dropTable('{{%group_users}}');
    }
}
