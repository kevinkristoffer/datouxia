<?php

class Puppy_Model_Core_Forum extends Puppy_Core_Model
{

    public function queryValidForum()
    {
        $sql = "select `forumid` as `id`,`parentid` as `pid`,`forumname` as `text`,`url`,case when forumid in" .
               " (select distinct `parentid` from `" . $this->_prefix . "core_forum` " .
               " where validstatus='1' order by forumorder asc )" . " then 0 else 1 end leaf from `" . $this->_prefix .
               "core_forum` " . " where validstatus='1' order by forumorder asc";
        $result = $this->_db->query($sql)->fetchAll();
        return $result;
    }
}