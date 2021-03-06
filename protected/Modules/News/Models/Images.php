<?php
/**
 * Created by PhpStorm.
 * User: barricade
 * Date: 16.04.15
 * Time: 22:29
 */

namespace App\Modules\News\Models;


class Images extends Model
{
    static protected $schema=[
        'table' => 'newsimages',
        'columns' => [
            'image' => ['type'=>'string']
        ],
        'relations' => [
            'story' => ['type'=>self::BELONGS_TO, 'model'=>\App\Modules\News\Models\Story::class],
        ]
    ];
}