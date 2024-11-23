<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * class m241122_163123_rename_media_path_column_to_path_in_media_file_table
 */
class m241122_163123_rename_media_path_column_to_path_in_media_file_table extends Migration
{
    /**
     * {@inheritDoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%media_file}}', 'media_path', 'path');
    }

    /**
     * {@inheritDoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%media_file}}', 'path', 'media_path');
    }
}
