<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set("Africa/Nairobi");

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        // $this->load->library('curl');
        // $this->load->model('moodle_model', '', TRUE);
    }
    public function index($var = null)
    {
        echo "<h1>Api Moodle .....</h1>";
    }
    public function getbookcourse_id($course_id)
    {
        header('Content-Type: application/json');
        // public function selectz($array_table_n, $table_n, $variable_1, $value_1)
        $data = $this->universal_model->selectzy(array('id', 'book_id', 'changedat','action'), 'mdl_book_aduit', 'course_id', $course_id);
        if (empty($data))
            echo json_encode(array(
                "code" => '400',
                "message" => "No Books with this Course ID."
            ));
        else
            echo json_encode($data);
    }
    public function get_chapter_id($book_id, $chapter_id)
    {
        header('Content-Type: application/json');
        $data = $this->universal_model->selectzy(array('id', 'changedat', 'action'), 'mdl_book_chapters_audit', 'book_id', $book_id, 'chapter_id', $chapter_id);
        if (empty($data))
            echo json_encode(array(
                "code" => '400',
                "message" => "No Chapters with This Book Id."
            ));
        else
            echo json_encode($data);
    }
    // public function selectzy($array_table_n, $table_n, $variable_1, $value_1, $variable_2, $value_2)
}
