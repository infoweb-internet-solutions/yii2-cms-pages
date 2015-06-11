<?php

use yii\db\Schema;
use yii\db\Migration;

class m150528_073125_add_slider_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%pages}}', 'slider_id', Schema::TYPE_INTEGER.' NOT NULL');
        $this->createIndex('slider_id', '{{%pages}}', 'slider_id');
    }

    public function down()
    {
        $this->dropIndex('slider_id', '{{%pages}}');
        $this->dropColumn('{{%pages}}', 'slider_id');    
    }
}
