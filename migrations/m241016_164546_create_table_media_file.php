<?php

use yii\db\Migration;

/**
 * Class m241016_164546_create_table_media_file
 */
class m241016_164546_create_table_media_file extends Migration
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

        $this->createTable('{{%media_file}}', [
            'id' => $this->primaryKey(),
            'media_path' => $this->string(),
            'name' => $this->string()->notNull(),
            'place' => $this->string(),
            'post_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-media_file-post_id}}', '{{%media_file}}', 'post_id');

        $this->addForeignKey(
            '{{%fk-media_file-post_id}}',
            '{{%media_file}}',
            'post_id',
            '{{%post}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-media_file-post_id}}', '{{%media_file}}');
        $this->dropIndex('{{%idx-media_file-post_id}}', '{{%media_file}}');
        $this->dropTable('{{%media_file}}');
    }
}
