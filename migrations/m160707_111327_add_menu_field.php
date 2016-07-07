<?php

use yii\db\Schema;
use yii\db\Migration;

class m160707_111327_add_menu_field extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%pages}}', 'menu_id', Schema::TYPE_INTEGER.' NOT NULL');
        $this->createIndex('menu_id', '{{%pages}}', 'menu_id');
    }

    public function safeDown()
    {
        $this->dropIndex('menu_id', '{{%pages}}');
        $this->dropColumn('{{%pages}}', 'menu_id');    
    }
}