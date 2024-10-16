<?php

use yii\db\Migration;

/**
 * Class m241016_172145_create_table_group
 */
class m241016_172145_create_table_group extends Migration
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

        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'active' => $this->boolean(),
            'deleted' => $this->boolean(),
            'banned' => $this->boolean(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('{{%idx-group-owner_id}}', '{{%group}}', 'owner_id');

        $this->addForeignKey(
            '{{%fk-group-owner_id}}',
            '{{%group}}',
            'owner_id',
            '{{%user}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-group-owner_id}}', '{{%group}}');
        $this->dropIndex('{{%idx-group-owner_id}}', '{{%group}}');
        $this->dropTable('{{%group}}');
    }
}
