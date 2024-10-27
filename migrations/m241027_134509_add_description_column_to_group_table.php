<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m241027_134509_add_description_column_to_group_table
 */
class m241027_134509_add_description_column_to_group_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%group}}', 'description', $this->text() . ' AFTER name');
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%group}}', 'description');
    }
}
