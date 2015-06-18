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
            $this->_prefix . 'core_role'), 'a.rolecode=b.rolecode', null)->columns(array('authid'=>'id',
            'accountname'=>'uname',
            'validflag'=>'valid'), 'a')->columns(array('rolecode',
            'rolename'=>'usergroup'), 'b');
        if ($where != null && is_array($where)) {
            foreach ($where as $key => $value) {
                $select = $select->where($key, $value);
            }
        }
        $select = $select->limit($limit, $offset);
        $result = $select->query()->fetchAll();
        return $result;
    }

    public function queryUserDetail()
    {

    }

    public function addUser()
    {

    }

    public function updateUser()
    {

    }
}