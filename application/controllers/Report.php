<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
header('Access-Control-Allow-Origin: *');

class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        // https://stackoverflow.com/questions/54786609/phpspreadsheet-how-do-i-place-a-image-from-link-into-my-excel-file
        // https://stackoverflow.com/questions/48947078/phpsreadsheet-create-excel-from-html-table
    }
    public function index()
    {
        echo '<h1>Report Api </h1>';
    }
    public function report_survey()
    {
        // $data['cohorts'] = $this->getme_chort_details();
        // $data['surveys'] = $this->get_surveys();
        $surveyid = $this->input->post('selectclientid');
        $selectclientname = $this->input->post('selectclientname');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');
        $persial_survey = $this->universal_model->join_suv_report($surveyid, $startdate, $enddate);
        if (empty($persial_survey)) {
            $json_return = array(
                'report' => "No Report Found For This Survey Combination",
                'status' => 0,
            );
            echo json_encode($json_return);
        } else {
            $table_data['survey_reportdata'] = $persial_survey;
            $table_data['startdate'] = $startdate;
            $table_data['enddate'] = $enddate;
            $table_data['controller'] = $this;
            $table_data['taskname'] = $selectclientname;
            // $table_data['table_survey'] = $this->load->view('pages/table/survey_table', $table_data, true);
            $table_data['table_survey_url'] = 'pages/table/survey_table';
            $json_return = array(
                'report' => "Report For Survey  in Range" . $selectclientname,
                'status' => 1,
                'data' => $this->load->view('pages/cohort/survey_reportshowtemp', $table_data, true),
                'path'=>FCPATH.'excelfiles/' .$this->session->userdata('logged_in_lodda')['id']. 'write.xls'
            );
            $arrayexcel = array();
            foreach ($persial_survey as $key => $value_excel) {
                $image_url = base_url('uploadscustome/' . $value_excel['image_url_small']);
                $message = '<img src="' . $image_url . '" alt="" width="100" height="100" />';
                $array_one = array(
                    // 'image' => $message,
                    'name' => $value_excel['name'],
                    'date' => $value_excel['dateaddedsurvey'],
                    'user_id' => $value_excel['userid']
                );
                array_push($arrayexcel, $array_one);
            }
            // part/content/report/reporttemps/pertaskperclient
            $htmlString = $this->xxxxtimePerClientReport($arrayexcel);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadsheet = $reader->loadFromString($htmlString);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save(FCPATH.'excelfiles/' .$this->session->userdata('logged_in_lodda')['id']. 'write.xls');
            echo json_encode($json_return);
        }
        // echo json_encode($persial_survey);
    }
    public function xxxxtimePerClientReport($data)
    {
        // $this->table->set_heading('IMAGE', 'SURVEY NAME', 'DATE CREATED', 'WHO SUBMITTED');
        // $this->table->set_heading(array('Name', 'Color', 'Size'));
        $this->table->set_heading('SURVEY NAME', 'DATE CREATED', 'WHO SUBMITTED');
        return $this->table->generate($data);
    }
}
