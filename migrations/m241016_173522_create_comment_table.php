<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241016_173522_create_comment_table
 */
class m241016_173522_create_comment_table extends Migration
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

        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'content' => $this->string()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull(),
        ], $tableOptions);

        // Create a unique index for (post_id, created_by)
        $this->createIndex('{{%idx-comment-post_id-created_by}}', '{{%comment}}', ['post_id', 'created_by'], true);

        $this->addForeignKey(
            '{{%fk-comment-created_by}}',
            '{{%comment}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-comment-post_id}}',
            '{{%comment}}',
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
        $this->dropForeignKey('{{%fk-comment-post_id}}', '{{%comment}}');
        $this->dropForeignKey('{{%fk-comment-created_by}}', '{{%comment}}');
        $this->dropIndex('{{%idx-comment-post_id-created_by}}', '{{%comment}}');
        $this->dropTable('{{%comment}}');
    }
}
