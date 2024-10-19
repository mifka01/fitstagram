<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241016_163039_create_post_table
 */
class m241016_163039_create_post_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        // Set table options for MySQL
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'created_by' => $this->integer()->notNull(),
            'is_private' => $this->boolean()->notNull(),
            'is_group_post' => $this->boolean()->notNull(),
            'upvote_count' => $this->integer()->defaultValue(0),
            'downvote_count' => $this->integer()->defaultValue(0),
            // Use text for larger descriptions
            'description' => $this->text(),
            // Default to false
            'deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull(),
            // Use table options here
        ], $tableOptions);

        // Create index for column `created_by`
        $this->createIndex(
            '{{%idx-post-created_by}}',
            '{{%post}}',
            'created_by'
        );

        // Add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-post-created_by}}',
            '{{%post}}',
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
        // Drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-post-created_by}}',
            '{{%post}}'
        );

        // Drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-post-created_by}}',
            '{{%post}}'
        );

        $this->dropTable('{{%post}}');
    }
}
