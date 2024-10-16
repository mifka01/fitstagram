<?php

use yii\db\Migration;

/**
 * Class m241016_173216_create_table_post_tag
 */
class m241016_173216_create_table_post_tag extends Migration
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

        $this->createTable('{{%post_tag}}', [
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull(),
        ], $tableOptions);

        // Create a unique primary key for (post_id, tag_id)
        $this->addPrimaryKey('{{%pk-post_tag}}', '{{%post_tag}}', ['post_id', 'tag_id']);

        $this->addForeignKey(
            '{{%fk-post_tag-post_id}}',
            '{{%post_tag}}',
            'post_id',
            '{{%post}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-post_tag-tag_id}}',
            '{{%post_tag}}',
            'tag_id',
            '{{%tag}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-post_tag-tag_id}}', '{{%post_tag}}');
        $this->dropForeignKey('{{%fk-post_tag-post_id}}', '{{%post_tag}}');
        $this->dropTable('{{%post_tag}}');
    }
}
