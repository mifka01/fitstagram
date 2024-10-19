<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241016_165502_create_tag_table
 */
class m241016_165502_create_tag_table extends Migration
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

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'created_by' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-tag-created_by}}', '{{%tag}}', 'created_by');
        $this->createIndex('{{%idx-tag-name}}', '{{%tag}}', 'name');

        $this->addForeignKey(
            '{{%fk-tag-created_by}}',
            '{{%tag}}',
            'created_by',
            '{{%user}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-tag-created_by}}', '{{%tag}}');
        $this->dropIndex('{{%idx-tag-created_by}}', '{{%tag}}');
        $this->dropIndex('{{%idx-tag-name}}', '{{%tag}}');
        $this->dropTable('{{%tag}}');
    }
}
