<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('universal_model');
		$this->load->model('user_model', '', TRUE);
	}

	public function index_temp()
	{
		$data['header'] = 'parts/header';
		$data['nav'] = 'parts/nav_home';
		$data['footer'] = 'parts/footer';
		$data['title'] = ucfirst("Loddca");
		$this->load->view('home', $data);
	}
	public function index()
	{
		// redirect();
		echo "<h1>Api Dashboard Comming .....</h1>";
		// $this->landing(0);
	}
	// public function test_q()
	// {
		
	// 	$result=$this->universal_model->selectallarray(array('category'=>1),'user');
	// 	print_array($result);
	// }
	public function landing($id = 0)
	{
		if ($this->session->userdata('logged_in_lodda')) {
			redirect(base_url('welcome/admin'));
		} else {
			$data['header'] = 'parts/header';
			switch ($id) {
				case 0:
					$data['content'] = 'pages/index';
					$this->load->view('pages/homeone', $data);
					break;
				case 1:
					$data['content'] = 'pages/register';
					$this->load->view('pages/homeone', $data);
					break;
				case 2:
					$data['content'] = 'pages/login';
					$this->load->view('pages/homeone', $data);
					break;
				default:
					redirect(base_url());
					break;
			}
		}
	}
	public function admin($var = 0)
	{
		if ($this->session->userdata('logged_in_lodda')) {
			$data['header'] = 'parts/header';
			$category = $this->session->userdata('logged_in_lodda')['category'];
			$array_cat = $this->universal_model->user_with_cat($this->session->userdata('logged_in_lodda')['id'], array('b.name as categoryname'));
			$value_cat = array_shift($array_cat);
			$data['categoryname'] = $value_cat['categoryname'];
			$data['conductors']=$this->universal_model->selectallarray(array('category'=>1),'user');
			$data['drivers']=$this->universal_model->selectallarray(array('category'=>2),'user');
			if ($category == 1 || $category == 2) {
				$data['content_admin'] = 'pages/admin/user_content';
				$data['sidenav'] = 'pages/admin/navmembers';
			}
			if ($category == 3) {
				$data['content_admin'] = 'pages/admin/admin_content';
				$data['sidenav'] = 'pages/admin/navadmin';
			}
			switch ($var) {
				case 0:
					$this->load->view('pages/hometwo', $data);
					break;
				default:
					break;
			}
		} else {
			$data['content'] = 'pages/index';
			$this->load->view('pages/homeone', $data);
		}
	}
}
