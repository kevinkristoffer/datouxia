<?php

class Puppy_Model_Core_Forum extends Puppy_Core_Model
{

    public function queryValidMenu()
    {
        $menuSelect = $this->_db->select()->from($this->_prefix . 'core_menu', array('mid'=>'menuid',
            'mname'=>'menuname'))->order('menuorder');
        $menus = $menuSelect->query()->fetchAll();
        for ($i = 0; $i < count($menus); $i++) {
            $forumSelect = $this->_db->select()->from($this->_prefix . 'core_forum', array('fid'=>'forumid',
                'fname'=>'forumname',
                'pid'=>'parentid',
                'url'))->where('menuid=?', $menus[$i]->mid)->order('forumorder');
            $forums = $forumSelect->query()->fetchAll();
            $menus[$i]->forums = $forums;
        }
        return $menus;
    }


}