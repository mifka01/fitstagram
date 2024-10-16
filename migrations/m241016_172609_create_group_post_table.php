<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241016_172609_create_group_post_table
 */
class m241016_172609_create_group_post_table extends Migration
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

        $this->createTable('{{%group_post}}', [
            'group_id' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // Create a composite primary key for (group_id, post_id)
        $this->addPrimaryKey('{{%pk-group_post}}', '{{%group_post}}', ['group_id', 'post_id']);

        $this->createIndex('{{%idx-group_post-post_id}}', '{{%group_post}}', 'post_id');

        $this->addForeignKey(
            '{{%fk-group_post-group_id}}',
            '{{%group_post}}',
            'group_id',
            '{{%group}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-group_post-post_id}}',
            '{{%group_post}}',
            'post_id',
            '{{%post}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-group_post-post_id}}', '{{%group_post}}');
        $this->dropForeignKey('{{%fk-group_post-group_id}}', '{{%group_post}}');
        $this->dropIndex('{{%idx-group_post-post_id}}', '{{%group_post}}');
        $this->dropTable('{{%group_post}}');
    }
}
