<?php

use yii\db\Schema;
use yii\db\Migration;

class m160727_115328_default_value_slider_field extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%pages}}', 'slider_id', Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%pages}}', 'slider_id', Schema::TYPE_INTEGER.' NOT NULL');
    }
}
