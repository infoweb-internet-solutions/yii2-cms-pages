<?php

use yii\db\Schema;
use yii\db\Migration;

class m141002_082146_create_templates_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        // Create 'page_templates' table
        $this->createTable('{{%page_templates}}', [
            'id'                    => Schema::TYPE_PK,
            'name'                  => Schema::TYPE_STRING . "(255) NOT NULL",
            'layout'                => Schema::TYPE_STRING . "(255) NOT NULL",
            'active'                => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\'',
            'created_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'            => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);
        
        // Insert the default template
        $this->insert('{{%page_templates}}', [
            'name'          => 'Default',
            'layout'        => 'main',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
    }

    public function down()
    {
        $this->dropTable('page_templates');
    }
}
