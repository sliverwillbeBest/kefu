<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
    通用model
    对基本sql操作进行了封装，对复杂sql仍然可以调用封装的原生sql方法，原则上db的操作只在model出现
    @author sliver
 */

class Common_model extends CI_Model{

    public $table_prefix;
    public function __construct(){
        parent::__construct();
        $this->table_prefix = $this->db->dbprefix; 
    }
    
    /*
	  *执行原生sql(查询多条)
    */
    public function run_sql_more($db, $sql){
        $query = $db->query($sql);
        return $query->result_array();
    }

    /*
	  *执行原生sql(查询一条)
    */
    public function run_sql_one($db, $sql){
        $query = $db->query($sql);
        return $query->row_array();
    }

    /*
        基本查询，多条数据
        @param where array('name' => $name, 'title' => $title, 'status' => $status)
        @param where array('name !=' => $name, 'id <' => $id, 'date >' => $date)
        @param limit array(10, 20) 查询10个，从20开始
        @param field 'field1, field2, field3'
        @param order_by 'title DESC, name ASC'
        @return array
     */
    public function select($db, $table, $where=array(), $field='*', $limit=array(), $order_by=''){
        if($limit){
            $db->limit($limit[0], $limit[1]);
        }
        return $db->select($field)->where($where)->order_by($order_by)->get($this->table_prefix.$table)->result_array();
    }

    /*
        查询单条数据
     */
    public function select_one($db, $table, $where=array(), $field='*', $order_by=''){
        return $db->select($field)->where($where)->order_by($order_by)->get($this->table_prefix.$table)->row_array();
    }


    /*
        查询单个列的和
        @param field array('num', 'sum') 第一个值为要查询的字段名, 第二个值为重命名的值
     */

    public function select_sum($db, $table, $where=array(), $field=array()){
        return $db->select_sum($field[0], $field[1])->where($where)->get($this->table_prefix.$table)->row_array();
    }

    /*
        插入单条数据
     */
    public function insert($db, $table, $data){
        $in = $db->insert($this->table_prefix.$table, $data);   //插入的sql语句   
        if($in){
          $insertid = $db->insert_id();
        }else{
          $insertid = 0;
        }
        return $insertid;
    }

    /*
        更新数据
     */
    public function update($db, $table, $data, $where){
        return $db->update($this->table_prefix.$table, $data, $where);
    }

    /*
        删除数据
     */
    public function delete($db, $table, $where){
        return $db->delete($this->table_prefix.$table, $where);    
    }

    /*
        获取符合条件的结果的条数
     */
    public function get_num($db, $table, $where=array()){
        return $db->where($where)->from($this->table_prefix.$table)->count_all_results();
    }

    
    /*
        添加多条数据
        $data = array(
            array(
                'title' => 'My title',
                'name' => 'My Name',
                'date' => 'My date'
            ),
            array(
                'title' => 'Another title',
                'name' => 'Another Name',
                'date' => 'Another date'
            )
        );
        INSERT INTO mytable (title, name, date) VALUES ('My title', 'My name', 'My date'),  ('Another title', 'Another name', 'Another date')
     */
  	public function insert_batch($db, $table, $data){
        return $db->insert_batch($this->table_prefix.$table, $data);    //结果为插入的记录条数
  	}



}
