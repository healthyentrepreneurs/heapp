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
			$data['sidenav'] = 'pages/admin/navadmin';
			$server_output = curl_request(base_url('getcourses'), array(), "get", array('App-Key: 123456'));
			$data['courses'] = json_decode($server_output, true);
			// print_array($server_output);
			switch ($var) {
				case 0:
					$data['content_admin'] = 'pages/admin/admin_content';
					$this->load->view('pages/hometwo', $data);
					break;
				case 1:
					$data['icon_image'] = 'https://picsum.photos/200/300';
					$data['content_admin'] = 'pages/admin/admin_quiz';
					$this->load->view('pages/hometwo', $data);
					break;
				case 2:
					// public function selectz($array_table_n, $table_n, $variable_1, $value_1)
					$attempt_n_n = $this->universal_model->selectz('*', 'survey', 'slug', 1);
					$data['content_admin'] = 'pages/admin/surveylist';
					$data['surveydatas'] = $attempt_n_n;
					$this->load->view('pages/hometwo', $data);
					// $this->load->view('pages/homequiz', $data);
					break;
				case 3:
					$id=$this->input->get('id');
					$attempt_n_n_one = $this->universal_model->selectzy('*', 'survey', 'slug', 1,'id',$id);
					// public function selectzy($array_table_n, $table_n, $variable_1, $value_1, $variable_2, $value_2)
					// echo "Hey Hey".$id;
					$data['content_admin'] = 'pages/admin/surveyinstance';
					$data['surveydataone'] = array_shift($attempt_n_n_one);
					$data['id'] = $id;
					$this->load->view('pages/hometwo', $data);
					break;
				case 4:
					echo "Hey Hey 6";
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
