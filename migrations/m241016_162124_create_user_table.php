<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241016_162124_create_user_table
 */
class m241016_162124_create_user_table extends Migration
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

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'verification_token' => $this->string()->notNull(),
            'auth_key' => $this->string()->notNull(),
            'active' => $this->boolean()->notNull()->defaultValue(false),
            'deleted' => $this->boolean()->notNull()->defaultValue(false),
            'banned' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);


        $this->createTable('session', [
            'id' => $this->char(40)->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
            'user_id' => $this->integer()
        ], $tableOptions);

        $this->addPrimaryKey('session_pk', 'session', 'id');


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
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-session-user_id}}', '{{%session}}');
        $this->dropIndex('{{%idx-session-user_id}}', '{{%session}}');
        $this->dropTable('{{%session}}');
        $this->dropTable('{{%user}}');
    }
}
