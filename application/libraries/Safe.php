<?php
/**
* @name 生蚝科技RBAC开发框架-L-安全类
* @author Jerry Cheung <master@xshgzs.com>
* @since 2018-01-18
* @version 2019-07-28
*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Safe {

	protected $_CI;
	public $sessPrefix;

	function __construct(){
		$this->_CI =& get_instance();
		$this->_CI->load->model('Setting_model');
		$this->_CI->load->helper('url');

		$this->sessPrefix=$this->_CI->Setting_model->get('sessionPrefix');
	}

	/**
	 * 获取系统全局Session名称前缀
	 * @return String 全局Session名前缀
	 */
	public function getSessionPrefix()
	{
		return $this->sessPrefix;
	}


	public function checkLogin($isAjax=0)
	{
		if(strlen($this->_CI->session->userdata($this->sessPrefix.'userId'))>=1) return true;
		else{
			if($isAjax==0) logout(true);
			else returnAjaxData(403001,'User not login');
		}
	}


	/**
	 * 判断当前页面是否有权限访问
 	 */
	public function checkAuth($method="",$uri="")
	{
		// 先校验是否已经登录
		if($this->_CI->session->userdata($this->sessPrefix.'userId')<1){
			if($method!='api'){
				logout(true);
			}else{
				return false;
			}
		}

		if($uri=='') $uri=uri_string();
		$menuPermission=array();
		$roleId=$this->_CI->session->userdata($this->sessPrefix.'roleId');

		$this->_CI->db->select('menu_id')
		              ->where('role_id',$roleId);
		$query=$this->_CI->db->get('role_permission');
		$list=$query->result_array();

		foreach($list as $value){
			array_push($menuPermission,$value['menu_id']);
		}

		$query=$this->_CI->db->like('uri',$uri)
		                     ->get('menu');

		if($query->num_rows()!=1){
			return false;
		}else{
			$info=$query->result_array();
			$menuId=$info[0]['id'];

			if(in_array($menuId,$menuPermission)){
				return true;
			}else{
				if($method!='api'){
					die('<script>alert("抱歉！您暂无权限访问此页面！\n请从正常途径访问系统！");window.location.href="'.base_url('/').'";</script>');
				}else{
					return false;
				}
			}
		}
	}
}
