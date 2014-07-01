<?php
/**
 * Created by PhpStorm.
 * User: Степанцев Альберт
 * Date: 01.07.14
 * Time: 12:57
 */

namespace App\Modules\Admin\Controllers;


use App\Models\Menu as MenuModel;
use T4\Mvc\Controller;

class Menu
    extends Controller
{

    protected  $access = [
        'Default' => ['role.name'=>'admin'],
        'Edit' => ['role.name'=>'admin'],
        'Save' => ['role.name'=>'admin'],
        'Delete' => ['role.name'=>'admin'],
    ];

    public function actionDefault()
    {
        $this->data->items = MenuModel::findAllTree();
    }

    public function actionEdit($id=null)
    {
        if (null === $id || 'new' == $id) {
            $this->data->item = new MenuModel();
        } else {
            $this->data->item = MenuModel::findByPK($id);
        }
    }

    public function actionSave()
    {
        if (!empty($_POST[MenuModel::PK])) {
            $item = MenuModel::findByPK($_POST[MenuModel::PK]);
        } else {
            $item = new MenuModel();
        }
        $item
            ->fill($_POST)
            ->save();
        $this->redirect('/admin/menu/');
    }

    public function actionDelete($id)
    {
        $item = MenuModel::findByPK($id);
        if ($item)
            $item->delete();
        $this->redirect('/admin/menu/');
    }

} 