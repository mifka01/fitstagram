<?php

use yii\db\Migration;

/**
 * Class m241016_162124_create_table_user
 */
class m241016_162124_create_table_user extends Migration
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

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'verification_token' => $this->string()->notNull(),
            'auth_key' => $this->string()->notNull(),
            'active' => $this->boolean(),
            'deleted' => $this->boolean(),
            'banned' => $this->boolean(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')->append('ON UPDATE CURRENT_TIMESTAMP'),
        ]);


        $this->createTable('session', [
            'id' => $this->primaryKey(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
            'user_id' => $this->integer()
        ], $tableOptions);


        $this->createIndex(
            '{{%idx-session-user_id}}',
            '{{%session}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-session-user_id}}',
            '{{%session}}',
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
        $this->dropForeignKey('{{%fk-session-user_id}}', '{{%session}}');
        $this->dropIndex('{{%idx-session-user_id}}', '{{%session}}');
        $this->dropTable('{{%session}}');
        $this->dropTable('{{%user}}');
    }
}
