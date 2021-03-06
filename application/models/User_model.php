<?php
/**
 * @name 生蚝科技RBAC开发框架-M-用户
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-20
 * @version 2019-03-22
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}


	/**
	 * 根据用户名获取用户信息
	 * @param String 用户名
	 * @return Array 用户信息
	 */
	public function getUserInfoByUserName($userName)
	{
		$sql="SELECT * FROM user WHERE user_name=?";
		$query=$this->db->query($sql,[$userName]);

		if($query->num_rows()!=1){
			return array();
		}
		
		$info=$query->result_array();
		return $info[0];
	}


	/**
	 * 用户登录验证
	 * @param String 用户密码
	 * @param String 用户Id
	 * @param String 用户名
	 * @return String 验证状态码
	 */
	public function validateUser($pwd,$userId=0,$userName="")
	{
		$sql1="SELECT salt,password,status FROM user WHERE id=? OR user_name=?";
		$query1=$this->db->query($sql1,[$userId,$userName]);
		
		if($query1->num_rows()!=1){
			return 404;
		}
		
		$info=$query1->result_array();
		$status=$info[0]['status'];
		$salt=$info[0]['salt'];
		$pwd_indb=$info[0]['password'];

		if(sha1(md5($pwd).$salt)==$pwd_indb){
			return 200;
		}elseif($status==0){
			return -1;
		}else{
			return 403;
		}
	}
}
