<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241016_173054_create_permitted_user_table
 */
class m241016_173054_create_permitted_user_table extends Migration
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

        $this->createTable('{{%permitted_user}}', [
            'user_id' => $this->integer()->notNull(),
            'permitted_user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // Create a unique primary key for (user_id, permitted_user_id)
        $this->addPrimaryKey('{{%pk-permitted_user}}', '{{%permitted_user}}', ['user_id', 'permitted_user_id']);

        $this->addForeignKey(
            '{{%fk-permitted_user-user_id}}',
            '{{%permitted_user}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-permitted_user-permitted_user_id}}',
            '{{%permitted_user}}',
            'permitted_user_id',
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
        $this->dropForeignKey('{{%fk-permitted_user-permitted_user_id}}', '{{%permitted_user}}');
        $this->dropForeignKey('{{%fk-permitted_user-user_id}}', '{{%permitted_user}}');
        $this->dropTable('{{%permitted_user}}');
    }
}
