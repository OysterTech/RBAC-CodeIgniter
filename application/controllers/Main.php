<?php
/**
 * @name 生蚝科技RBAC开发框架-C-基本
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2018-02-07
 * @version 2019-03-22
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public $sessPrefix;
	public $nowUserId;
	public $nowUserName;
	public $API_PATH;

	function __construct()
	{
		parent::__construct();

		$this->sessPrefix=$this->safe->getSessionPrefix();

		$this->API_PATH=$this->setting->get('apiPath');
		$this->nowUserId=$this->session->userdata($this->sessPrefix.'userId');
		$this->nowUserName=$this->session->userdata($this->sessPrefix.'userName');
	}


	public function index()
	{
		if($this->nowUserId==NULL){
			header('Location:'.base_url('user/logout'));
		}
		
		$latestNotice=$this->Notice_model->get(0,'index');
		
		$this->load->view('index',['allNotice'=>$latestNotice]);
	}
}
