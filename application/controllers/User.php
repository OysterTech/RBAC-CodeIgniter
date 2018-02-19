<?php
/**
 * @name C-用户
 * @author SmallOysyer <master@xshgzs.com>
 * @since 2018-02-19
 * @version V1.0 2018-02-19
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public $allMenu;
	public $sessPrefix;
	public $nowUserID;
	public $nowUserName;
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('string');
		$this->load->library(array('Ajax'));

		$this->allMenu=$this->RBAC_model->getAllMenuByRole("1");
		$this->sessPrefix=$this->safe->getSessionPrefix();

		$this->nowUserID="1";
		$this->nowUserName="super";
	}


	public function updateProfile()
	{
		$this->ajax->makeAjaxToken();

		$sql="SELECT * FROM user WHERE id=?";
		$query=$this->db->query($sql,[$this->nowUserID]);
		$list=$query->result_array();
		$info=$list[0];
		$this->load->view('user/updateProfile',["navData"=>$this->allMenu,'info'=>$info]);
	}


	public function toUpdateProfile()
	{
		$token=$this->input->post('token');
		$this->ajax->checkAjaxToken($token);

		$nickName=$this->input->post('nickName');
		$oldPwd=$this->input->post('oldPwd');
		$newPwd=$this->input->post('newPwd');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');

		$sql="UPDATE user SET nick_name=?,";
		$updateData=array();
		array_push($updateData,$nickName);

		if($oldPwd!=""){
			$validateOldPwd=$this->RBAC_model->validateUser($this->nowUserID,$oldPwd);

			if($validateOldPwd!="200"){
				$ret=$this->ajax->returnData("0","invaildPwd");
				die($ret);
			}else{
				$salt=random_string('alnum');
				$hashSalt=md5($salt);
				$hashPwd=sha1($newPwd.$hashSalt);

				$sql.="password=?,salt=?,";
				array_push($updateData,$hashPwd,$salt);
			}
		}

		$sql.="phone=?,email=? WHERE id=?";
		array_push($updateData,$phone,$email,$this->nowUserID);
		$query=$this->db->query($sql,$updateData);

		if($this->db->affected_rows()==1){
			$ret=$this->ajax->returnData("200","success");
			die($ret);
		}else{
			$ret=$this->ajax->returnData("1","updateFailed");
			die($ret);
		}
	}

}