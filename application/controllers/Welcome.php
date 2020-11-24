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
		// njovu
		if ($this->session->userdata('logged_in_lodda')) {
			$data['header'] = 'parts/header';
			$data['sidenav'] = 'pages/admin/navadmin';
			$server_output = curl_request(base_url('getcourses'), array(), "get", array('App-Key: 123456'));
			$courses = json_decode($server_output, true);
			// if (empty($courses)) {
			// 	$courses = array();
			// }
			$data['courses'] = $courses;
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
					$id = $this->input->get('id');
					$attempt_n_n_one = $this->universal_model->selectzy('*', 'survey', 'slug', 1, 'id', $id);
					// print_array($attempt_n_n_one);
					$data['content_admin'] = 'pages/admin/surveyinstance';
					$data['surveydataone'] = array_shift($attempt_n_n_one);
					$data['id'] = $id;
					$this->load->view('pages/hometwo', $data);
					break;
				case 4:
					$data['cohorts'] = $this->getme_chort_details();
					$data['surveys'] = $this->get_surveys();
					$data['survey_cohort'] = $this->universal_model->join_suv_cohot();
					$data['content_admin'] = 'pages/admin/cohorts';
					$this->load->view('pages/hometwo', $data);
					break;
				case 5:
					$id = $this->input->get('id');
					$attempt_n_n_one = $this->universal_model->selectzy('*', 'survey', 'slug', 1, 'id', $id);
					$data['surveydataone'] = array_shift($attempt_n_n_one);
					$data['content_admin'] = 'pages/admin/imgsurveyinstance';
					$data['id'] = $id;
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

	public function getme_chort_details()
	{
		$domainname = 'https://app.healthyentrepreneurs.nl';
		$token = 'f84bf33b56e86a4664284d8a3dfb5280';
		$functionname = 'core_cohort_get_cohorts';
		$serverurl = $domainname . '/webservice/rest/server.php';
		$data = array(
			'wstoken' => $token,
			'wsfunction' => $functionname,
			'moodlewsrestformat' => 'json',
			'cohortids' => array(),

		);
		$server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
		$array_of_output = json_decode($server_output, true);
		return $array_of_output;
		// print_array($array_of_output);
	}
	public function get_surveys()
	{
		$attempt_d_n_n = $this->universal_model->selectz('*', 'survey', 'slug', 1);
		$array_object = array();
		foreach ($attempt_d_n_n as $key => $value) {
			$custome_onw = array(
				'id' => $value['id'],
				'fullname' => $value['name'],
				'categoryid' => 2,
				'source' => $value['type'],
				'summary_custome' => $value['surveydesc'],
				"next_link" => base_url('survey/getnexlink/') . $value['id'],
				'image_url_small' => base_url('uploadscustome/') . $value['image'],
				'image_url' => base_url('uploadscustome/') . $value['image_url_small']
			);
			array_push($array_object, $custome_onw);
		}
		return $array_object;
	}
}
