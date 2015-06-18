<?php

class Puppy_Model_User extends Puppy_Core_Model {
	/**
	 * 
	 * @param int $id
	 * @param array $cols
	 * @return mixed
	 */
	public function getUserDetail($id, $cols)
	{
		$colsUser = array_intersect ( array (
				'userid',
				'accountname',
				'email',
				'ipaddress',
				'regdate' ), $cols );
		$colsRole = array_intersect ( array (
				'rolecode',
				'rolename' ), $cols );
		$colsCompany = array_intersect ( array (
				'comcode',
				'comname' ), $cols );
		$select = $this->_db->select ()
			->from ( array (
				'a' => $this->_prefix . 'user' ), null )
			->join ( array (
				'b' => $this->_prefix . 'role' ), 'a.rolecode=b.rolecode', null )
			->join ( array (
				'c' => $this->_prefix . 'company' ), 'a.comcode=c.comcode', null )
			->columns ( $colsUser, 'a' )
			->columns ( $colsRole, 'b' )
			->columns ( $colsCompany, 'c' )
			->where ( 'a.userid=?', $id );
		echo '<h5>' . $select->__toString () . '</h5>';
		$rs = $select->query ()
			->fetch ();
		return $rs;
	}
	
}