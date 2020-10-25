<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
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
		$data['title'] = ucfirst("HE Enters");
		$this->load->view('home', $data);
	}
	public function index()
	{
		// redirect();
		// echo "<h1>Api Dashboard Comming .....</h1>";
		$this->landing(0);
	}
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
		// print_array($this->session->userdata('logged_in_lodda'));
		if ($this->session->userdata('logged_in_lodda')) {
			$data['header'] = 'parts/header';
			$data['content_admin'] = 'pages/admin/admin_content';
			$data['sidenav'] = 'pages/admin/navadmin';
			$server_output = curl_request(base_url('getcourses'), array(), "get", array('App-Key: 123456'));
			$data['courses'] = json_decode($server_output, true);
			// print_array($server_output);
			switch ($var) {
				case 0:
					$this->load->view('pages/hometwo', $data);
					break;
				case 1:
					$this->load->view('pages/homethree', $data);
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
