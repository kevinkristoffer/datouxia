<?php

/**
 * Created by PhpStorm.
 * User: hujianhong05
 * Date: 2015/10/20
 * Time: 15:59
 */
class Puppy_Model_Auto_Model extends Puppy_Core_Model
{
    /**
     * @param null $where
     * @param array $fields
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Zend_Db_Select_Exception
     */
    public function queryModelList($where = null, $fields, $limit = 10, $offset = 0)
    {
        if (null == $fields)
            return null;
        $select = $this->_db->select()->from($this->_prefix . 'vehicle_model', $fields);
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
    public function countModel($where = null)
    {
        $select = $this->_db->select()->from($this->_prefix . 'vehicle_model', array('total' => 'count(*)'));
        if ($where != null && is_array($where)) {
            foreach ($where as $key => $value) {
                $select = $select->where($key, $value);
            }
        }
        $result = $select->query()->fetch();
        return $result->total;
    }

    /**
     * @param $fields
     * @param null $where
     * @return mixed|null
     */
    public function queryModelDetail($fields,$where=null)
    {
        if(null == $fields)
            return null;
        $select=$this->_db->select()->from($this->_prefix.'vehicle_model',$fields);
        if ($where != null && is_array($where)) {
            foreach ($where as $key => $value) {
                $select = $select->where($key, $value);
            }
        }
        $result=$select->query()->fetch();
        return $result;
    }

    /**
     * @param $model
     * @return int
     * @throws Zend_Db_Adapter_Exception
     */
    public function addModel($model)
    {
        $affectedRows = $this->_db->insert($this->_prefix . 'vehicle_model', $model);
        return $affectedRows;
    }

    /**
     * @param $set
     * @param $where
     * @return int
     * @throws Zend_Db_Adapter_Exception
     */
    public function updateModel($set, $where)
    {
        $affectedRows = $this->_db->update($this->_prefix . 'vehicle_model', $set, $where);
        return $affectedRows;
    }

    /**
     * @param string|null $keyword
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function queryVehicleMaker($keyword = null, $limit = 10, $offset = 0)
    {
        /*
         * 查询字段别名
         * id:vehiclemakerid
         * name:vehiclemaker
         */
        $fields['maker'] = 'vehiclemaker';
        $select = $this->_db->select()->distinct()->from($this->_prefix . 'vehicle_model', $fields);
        if (!empty($keyword)) {
            $keywords = explode(' ', $keyword);
            foreach ($keywords as $item) {
                $select = $select->where('vehiclemaker regexp ?', trim($item));
            }
        }
        $select = $select->limit($limit, $offset);
        $result = $select->query()->fetchAll();
        return $result;
    }

    /**
     * @param string|null $keyword
     * @return int
     */
    public function countVehicleMaker($keyword = null)
    {
        $fields['total'] = 'count(distinct vehiclemakerid)';
        $select = $this->_db->select()->from($this->_prefix . 'vehicle_model', $fields);
        if (!empty($keyword)) {
            $keywords = explode(' ', $keyword);
            foreach ($keywords as $item) {
                $select = $select->where('vehiclemaker regexp ?', trim($item));
            }
        }
        $result = $select->query()->fetch();
        return $result->total;
    }
}