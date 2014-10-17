<?php

use yii\db\Schema;
use yii\db\Migration;

class m141001_132609_add_default_permissions extends Migration
{
    public function up()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'showPagesModule',
            'type'          => 2,
            'description'   => 'Show pages module in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        // Create the auth item relation
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showPagesModule'
        ]);
    }

    public function down()
    {
        // Delete the auth item relation
        
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showPagesModule'
        ]);

        // Delete the auth items
        $this->delete('{{%auth_item}}', [
            'name'          => 'showPagesModule',
            'type'          => 2,
        ]);
    }
}
