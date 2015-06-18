<?php

class Puppy_Model_Forum extends Puppy_Core_Model {

	/**
	 *
	 * @param int $limit        	
	 * @param int $offset        	
	 * @return mixed
	 */
	public function getForums($limit, $offset)
	{
		$select = $this->_db->select ()
			->from ( array (
				'a' => $this->_prefix . 'forum' ), array (
				'forumid',
				'forumname' ) )
			->limit ( $limit, $offset );
		$rs = $select->query ()
			->fetchAll ();
		return $rs;
	}
}