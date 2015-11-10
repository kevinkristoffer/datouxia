<?php

class Puppy_Model_Core_Privilege extends Puppy_Core_Model
{
    /**
     * @param string $rolecode
     * @param int $forumid
     * @return int
     * @throws Zend_Db_Adapter_Exception
     */
    public function addRolePrivilege($rolecode, $forumid)
    {
        $data = array('rolecode' => $rolecode,
            'forumid' => $forumid);
        $affectedRows = $this->_db->insert($this->_prefix . 'core_role_privilege', $data);
        return $affectedRows;
    }

    /**
     * @param string $rolecode
     * @return int
     */
    public function deleteRolePrivilege($rolecode)
    {
        $where['rolecode=?'] = $rolecode;
        $deletedRows = $this->_db->delete($this->_prefix . 'core_role_privilege', $where);
        return $deletedRows;
    }

    public function queryRolePrivilege($rolecode)
    {
        $select=$this->_db->select()->from(array('a'=>$this->_prefix.'core_role_privilege'),null)
            ->join(array('b'=>$this->_prefix.'core_role'),'a.rolecode=b.rolecode', null)
            ->columns(array('rolecode','rolename'),'b')->where('a.rolecode=?',$rolecode);
        $result=$select->query()->fetchAll();
        return $result;
    }

}