<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require_once FCPATH . 'vendor/autoload.php';

use \Gumlet\ImageResize;

class Surveyimage extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        echo "<h1>Survey Image Index</h1>";
    }
    public function do_upload()
    {
        $_file_upload = FCPATH . 'uploads_clientapp';
        if (!is_dir($_file_upload)) {
            mkdir($_file_upload, 0755, true);
        }
        $config = array(
            'upload_path' => "./uploads_clientapp/",
            'allowed_types' => "gif|jpg|jpeg|png|iso|dmg|zip|rar|doc|docx|xls|xlsx|ppt|pptx|csv|ods|odt|odp|pdf|rtf|sxc|sxi|txt|exe|avi|mpeg|mp3|mp4|3gp",
            'overwrite' => TRUE,
            'max_size' => "202048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
            'max_height' => "60000",
            'max_width' => "60000"
        );
        $this->load->library('upload', $config);
        if ($this->upload->do_upload()) {
            $data = array('upload_data' => $this->upload->data());
            $name_file = $data['upload_data'];
            // $_POST['image_name'] = $name_file['file_name'];
            $array_image_survey = array(
                'image_name' => $name_file['file_name'],
                'user_id' => $this->input->post('user_id'),
                'survey_id' => $this->input->post('survey_id')
            );
            $this->universal_model->updateOnDuplicate('survey_image', $array_image_survey);
            $array_n = array(
                'code' => "200",
                'msg' => "Successfully Processed and Stored"
            );
            echo json_encode($array_n);
        } else {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        }
    }
}
