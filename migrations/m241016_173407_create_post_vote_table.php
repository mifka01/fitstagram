<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241016_173407_create_post_vote_table
 */
class m241016_173407_create_post_vote_table extends Migration
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

        $this->createTable('{{%post_vote}}', [
            'up' => $this->boolean()->notNull(),
            'voted_by' => $this->integer()->notNull(),
            'post_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull(),
        ], $tableOptions);

        // Create a unique primary key for (post_id, voted_by)
        $this->addPrimaryKey('{{%pk-post_vote}}', '{{%post_vote}}', ['post_id', 'voted_by']);

        $this->addForeignKey(
            '{{%fk-post_vote-voted_by}}',
            '{{%post_vote}}',
            'voted_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-post_vote-post_id}}',
            '{{%post_vote}}',
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
        $this->dropForeignKey('{{%fk-post_vote-post_id}}', '{{%post_vote}}');
        $this->dropForeignKey('{{%fk-post_vote-voted_by}}', '{{%post_vote}}');
        $this->dropTable('{{%post_vote}}');
    }
}
