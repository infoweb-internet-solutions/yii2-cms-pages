<?php

use yii\db\Schema;
use yii\db\Migration;

class m160831_081208_add_form_field extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('{{%pages}}', 'form_id', Schema::TYPE_INTEGER.' NOT NULL');
        $this->createIndex('form_id', '{{%pages}}', 'form_id'); 
    }

    public function safeDown()
    {
        $this->dropIndex('form_id', '{{%pages}}');
        $this->dropColumn('{{%pages}}', 'form_id');    
    }
}
