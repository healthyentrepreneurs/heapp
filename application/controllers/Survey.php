<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Survey extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
    }

    public function index()
    {
        // WRAP NJWA
        echo "<h1>Survey Api ..</h1>";
    }
    public function updatesurvey()
    {
        $survey = $this->input->post('surveyobj');
        $name = $this->input->post('surveyname');
        $surveydesc = $this->input->post('surveydesc');
        $id = $this->input->post('surveyid');
        $array_survey = array(
            'surveyjson' => $survey,
            'surveydesc' => $surveydesc,
            'name' => $name,
            'createdby' => 1
        );
        // public function updatez($variable, $value, $table_name, $updated_values)
        $this->universal_model->updatez('id', $id, 'survey', $array_survey);
        echo json_encode($array_survey);
    }
    public function addsurvey()
    {
        // surveydesc
        $this->form_validation->set_rules('surveydesc', 'Survey Description', 'trim|required|xss_clean');
        $this->form_validation->set_rules('surveyname', 'Survey Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('surveyjson', 'Survey Object', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $array_n = array(
                'status' => 0,
                'message' => "Enter and Save Survey Or Survey Name/Description"
            );
            echo json_encode($array_n);
        } else {
            $this->addemployee_subfunc();
        }
        // echo json_encode($_POST);
    }

    function addemployee_subfunc()
    {
        if ($this->validate_image("user_profile_pic" . getToken(3))) {
            $data = array(
                'upload_data' => $this->upload->data()
            );
            $name_file = $data['upload_data'];
            $_POST['user_profile_pic'] = $name_file['file_name'];
            $this->create_thumbnail(600, 600, './uploadscustome/' . "600_" . $this->input->post('user_profile_pic'), './uploadscustome/' . $this->input->post('user_profile_pic'));
            $this->create_thumbnail(50, 50, './uploadscustome/' . "50_" . $this->input->post('user_profile_pic'), './uploadscustome/' . $this->input->post('user_profile_pic'));
            $_POST['image_big'] = "600_" . $this->input->post('user_profile_pic');
            $_POST['image_url_small'] = "50_" . $this->input->post('user_profile_pic');
            $_POST['original'] = base_url("uploadscustome/600_" . $this->input->post('user_profile_pic'));
            unlink("uploadscustome/" . $name_file['file_name']);
            $user_add = array(
                'name' => $this->input->post('surveyname'),
                'surveydesc' => $this->input->post('surveydesc'),
                'surveyjson' => $this->input->post('surveyjson'),
                'image' => $this->input->post('image_big'),
                'image_url_small' => $this->input->post('image_url_small'),
                'createdby' => 1,
            );

            $this->universal_model->insertz('survey', $user_add);
            // $this->universal_model->updateOnDuplicate('survey', $user_add);
            // redirect(base_url('welcome/admin/1'));
            // echo json_encode($user_add);
            $array_n = array(
                'status' => 1,
                'message' => "Successfully Added New Survey"
            );
            echo json_encode($array_n);
        } else {
            // $_POST['original'] = $this->input->post('original');
            // redirect(base_url('welcome/admin/1'));
            # code...
            $array_n = array(
                'status' => 0,
                'message' => "Upload Image For This Survey"
            );
            echo json_encode($array_n);
        }
    }
    public function validate_image($generatedname)
    {
        $config['overwrite'] = TRUE;
        $config['upload_path'] = './uploadscustome/';
        $config['allowed_types'] = 'gif|jpeg|jpg|png';
        $config['max_size'] = '10000';
        $config['max_width'] = '6000';
        $config['max_height'] = '6000';
        $config['file_name'] = $generatedname;
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('user_profile_pic')) {
            $error = array(
                'error' => $this->upload->display_errors()
            );
            // print_array($error);
            if (strpos($error['error'], "You did not select a file to upload.") !== FALSE) {
                $this->form_validation->set_message('validate_image', 'Please Select An Image Icon');
                $this->session->set_flashdata('validate_image', "Please Select An Image Icon");
                // print_array("You did not select a file to uploads");
                return FALSE;
            }
            if (strpos($error['error'], "The filetype you are attempting to upload is not allowed.") !== FALSE) {
                $this->form_validation->set_message('validate_image', 'The filetype you are attempting to upload is not allowed');
                $this->session->set_flashdata('validate_image', "The filetype you are attempting to upload is not allowed");
                // print_array("The filetype you are attempting to upload is not allowed");
                return FALSE;
            }
            if (strpos($error['error'], "The image you are attempting to upload doesn't fit into the allowed dimensions.") !== FALSE) {
                $this->form_validation->set_message('validate_image', 'The image you are attempting to upload doesn\'t fit into the allowed dimensions');
                $this->session->set_flashdata('validate_image', 'The image you are attempting to upload doesn\'t fit into the allowed dimensions');
                // print_array("The filetype you are attempting to upload is not allowed");
                return FALSE;
            }
            if (strpos($error['error'], "The uploaded file exceeds the maximum allowed size in your PHP configuration file.") !== FALSE) {
                $this->session->set_flashdata('validate_image', "The uploaded file exceeds the maximum allowed");
                // print_array("The uploaded file exceeds the maximum allowed size in your");
                $this->form_validation->set_message('validate_image', 'Icon Image exceeds the required image size');
                return FALSE;
            }
        } else {
            $this->session->set_flashdata('validate_image_success', "Successfully Uploaded");
            return TRUE;
        }
    }
    public function create_thumbnail($width, $height, $new_image, $image_source)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_source;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = $new_image;
        $this->load->library('image_lib');
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
    }
    public function deletesurvey()
    {
        // unlink("uploadscustome/" . $name_file['file_name']);
        $surveyid = $this->input->post('surveyid');
        // public function deletez($table_name, $variable_1, $value_1)
        $this->universal_model->deletez('survey', 'id', $surveyid);
        echo json_encode($_POST);
    }
    public function getnexlink($id)
    {
        $attempt_d_n_n = $this->universal_model->selectz('*', 'survey', 'id', $id);
        $json_en = array_shift($attempt_d_n_n);
        $array_objects_pages = json_decode($json_en['surveyjson'], true);
        echo json_encode($array_objects_pages);
        // if (count($array_objects_pages['pages']) > 1) {
        //     $fuck = array_slice($array_objects_pages['pages'], 1, null, true);
        //     // $this->tranves_page($id, $fuck);
        //     $mama = $array_objects_pages['pages'][0];
        //     // $mama['nextlink']=base_url('survey/tranves_page'.$fuckm);
        //     $server_output = curl_request(base_url('survey/tranves_page'), $fuck, "post", array('App-Key: 123456'));
        //     $mama['nextlink'] = $server_output;
        //     print_array($mama);
        // } else {
        //     $gaga=$array_objects_pages['pages'][0];
        //     $gaga['nextlink']="";
        //     echo json_encode($gaga);
        // }
    }
}
