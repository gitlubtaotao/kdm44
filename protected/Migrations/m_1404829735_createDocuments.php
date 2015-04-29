<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1404829735_createDocuments
    extends Migration
{

    public function up()
    {
        $this->createTable('documents', [
            '__category_id' => ['type'=>'link'],
            'title' => ['type' => 'string', 'length' => 1024,],
            'published' => ['type' => 'datetime',],
            'text' => ['type' => 'text', 'length' => 'big',],
        ], [
            'category'=>['columns'=>['__category_id']]
        ]);
    }

    public function down()
    {
        $this->dropTable('documents');
    }

}