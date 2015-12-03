<?php

class Puppy_Model_Core_User extends Puppy_Core_Model
{
    /**
     * @param string $username
     * @param string $password
     * @return mixed
     * @throws Zend_Db_Select_Exception
     */
    public function queryUser($username, $password)
    {
        $select = $this->_db->select()->from(array('a' => $this->_prefix . 'core_user'), null)->join(array('b' =>
            $this->_prefix . 'core_role'), 'a.rolecode=b.rolecode', null)->columns(array('authid',
            'accountname',
            'validflag'), 'a')->columns(array('rolecode',
            'rolename'), 'b')->where('a.authid=?', $username)->where('a.credential=?', md5($password));
        $result = $select->query()->fetch();
        return $result;
    }

    /**
     * @param null|array $where
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Zend_Db_Select_Exception
     */
    public function queryUserList($where = null, $limit = 10, $offset = 0)
    {
        $select = $this->_db->select()->from(array('a' => $this->_prefix . 'core_user'), null)->join(array('b' =>
            $this->_prefix . 'core_role'), 'a.rolecode=b.rolecode', null)->columns(array('authid',
            'accountname',
            'validstatus'), 'a')->columns(array('rolecode',
            'rolename'), 'b');
        if ($where != null && is_array($where)) {
            foreach ($where as $key => $value) {
                $select = $select->where($key, $value);
            }
        }
        $select = $select->limit($limit, $offset);
        $result = $select->query()->fetchAll();
        return $result;
    }

    /**
     * @param null $where
     * @return int
     */
    public function countUser($where = null)
    {
        $select = $this->_db->select()->from(array('a' => $this->_prefix . 'core_user'), array('total' => 'count(*)'));
        if ($where != null && is_array($where)) {
            foreach ($where as $key => $value) {
                $select = $select->where($key, $value);
            }
        }
        $result = $select->query()->fetch();
        return $result->total;
    }

    /**
     * @param $authid
     * @return mixed
     * @throws Zend_Db_Select_Exception
     */
    public function queryUserDetail($authid)
    {
        $select = $this->_db->select()->from(array('a' => $this->_prefix . 'core_user'))->columns(array('authid',
            'accountname',
            'rolecode',
            'validflag'))->where('authid=?', $authid);
        $result = $select->query()->fetch();
        return $result;
    }

    /**
     * @return string
     */
    public function generateAuthId()
    {
        $select = $this->_db->select()->from(array('a' => $this->_prefix .
                                                          'core_user'), array('maxid' => 'max(authid)'));
        $result = $select->query()->fetch();
        $maxid = intval($result->maxid) + 1;
        $maxid = sprintf('%08d', $maxid);
        return $maxid;
    }

    /**
     * @param $user
     * @return int
     * @throws Zend_Db_Adapter_Exception
     */
    public function addUser($user)
    {
        $affectedRows = $this->_db->insert($this->_prefix . 'core_user', $user);
        return $affectedRows;
    }

    /**
     * @param $set
     * @param $where
     * @return int
     * @throws Zend_Db_Adapter_Exception
     */
    public function updateUser($set, $where)
    {
        $affectedRows = $this->_db->update($this->_prefix . 'core_user', $set, $where);
        return $affectedRows;
    }
}