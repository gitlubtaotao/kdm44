<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Block;
use T4\Dbal\QueryBuilder;
use T4\Mvc\Controller;

class Blocks
    extends Controller
{

    public function actionDefault()
    {
        $this->data->sections = $this->app->config->sections;
        $this->data->blocksAvailable = $this->app->config->blocks;

        $installed = Block::findAll(['order' => '`order`']);
        $this->data->blocksInstalledCount = count($installed);
        $this->data->blocksInstalled = [];
        foreach ($installed as &$block) {
            $block->title = $this->app->config->blocks->{$block->path}->title;
            $block->desc = $this->app->config->blocks->{$block->path}->desc;
            $block->options = json_decode($block->options, true);
            $this->data->blocksInstalled[$block->section][] = $block;
        }
    }

    public function actionSetupBlock($sectionId, $blockPath)
    {
        $query = new QueryBuilder();
        $query->select('MAX(`order`)')->from(Block::getTableName())->where('section=:section')->params([':section' => $sectionId]);
        $maxOrder = (int)$this->app->db->default->query($query->getQuery(), $query->getParams())->fetchScalar();

        $block = new Block();
        $block->section = $sectionId;
        $block->path = $blockPath;

        $params = [];
        foreach ($this->app->config->blocks->{$blockPath}->options as $optionName => $options) {
            $params[$optionName] = $options->default;
        }
        $block->options = json_encode($params);
        $block->order = $maxOrder + 10;

        if (false !== $block->save()) {
            $this->data->id = $block->getPK();
            $this->data->result = true;
        } else {
            $this->data->result = false;
        }
    }

    public function actionUninstallBlock($id)
    {
        $block = Block::findByPK($id);
        if (false !== $block->delete()) {
            $this->data->result = true;
        } else {
            $this->data->result = false;
        }
    }

    public function actionSortBlocks($ids)
    {
        $order = 1;
        foreach ($ids as $id) {
            $block = Block::findByPK($id);
            $block->order = $order * 10;
            if (false === $block->save()) {
                $this->data->result = false;
                return;
            };
            $order++;
        }
        $this->data->result = true;
    }

    public function actionUpdateBlockOptions($id, $options)
    {
        $block = Block::findByPK($id);
        if (empty($block)) {
            $this->data->result = false;
            return;
        }
        $opt = [];
        foreach ($options as $option) {
            $opt[$option['name']] = $option['value'];
        }
        $block->options = json_encode($opt);

        if (false !== $block->save()) {
            $this->data->result = true;
        } else {
            $this->data->result = false;
        }
    }

}