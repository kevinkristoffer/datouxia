<?php

class Puppy_Model_Core_Role extends Puppy_Core_Model
{
    /**
     * @return array
     */
    public function queryAllRoles()
    {
        $select = $this->_db->select()->from(array('a' => $this->_prefix . 'core_role'), array('rolecode',
            'rolename',
            'currentstatus' => 'validflag'));
        $result = $select->query()->fetchAll();
        return $result;
    }

    /**
     * @param string $rolecode
     * @return mixed
     */
    public function queryRoleDetail($rolecode)
    {
        $select = $this->_db->select()
            ->from(array('a' => $this->_prefix . 'core_role'))
            ->where('rolecode=?', $rolecode);
        $result = $select->query()->fetch();
        return $result;
    }

    /**
     * @param array $role
     * @return int
     * @throws Zend_Db_Adapter_Exception
     */
    public function addRole($role)
    {
        $affectedRows = $this->_db->insert($this->_prefix . 'core_role', $role);
        return $affectedRows;
    }

    /**
     * @param array $set
     * @param array $where
     * @return int
     * @throws Zend_Db_Adapter_Exception
     */
    public function updateRole($set, $where)
    {
        $affectedRows = $this->_db->update($this->_prefix . 'core_role', $set, $where);
        return $affectedRows;
    }

    /**
     * @param string $rolecode
     * @return int
     */
    public function countRoleUser($rolecode)
    {
        $select = $this->_db->select()
            ->from(array('a' => $this->_prefix . 'core_user'), array('total' => 'count(*)'))
            ->where('rolecode=?', $rolecode);
        $result = $select->query()->fetch();
        return $result->total;
    }
}
