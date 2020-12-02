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

    function edit_survayimage()
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
            if (file_exists("uploadscustome/" . $this->input->post('image_url_small_old'))) {
                unlink("uploadscustome/" . $this->input->post('image_url_small_old'));
            }
            if (file_exists("uploadscustome/" . $this->input->post('image_old'))) {
                unlink("uploadscustome/" . $this->input->post('image_old'));
            }
            $user_add = array(
                'id' => $this->input->post('survey_id'),
                'image' => $this->input->post('image_big'),
                'image_url_small' => $this->input->post('image_url_small'),
                'createdby' => 1,
            );
            $this->universal_model->updateOnDuplicate('survey', $user_add);
            // redirect(base_url('welcome/admin/1'));
            // echo json_encode($user_add);
            $array_n = array(
                'status' => 1,
                'message' => "Successfully Updated Image Of Survey"
            );
            echo json_encode($array_n);
        } else {
            // $_POST['original'] = $this->input->post('original');
            // redirect(base_url('welcome/admin/1'));
            # code...
            $array_n = array(
                'status' => 0,
                'message' => "Update Image For This Survey"
            );
            echo json_encode($array_n);
        }
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
        $json_en_values = $this->survey_custom_values($attempt_d_n_n);
        $json_en = array_shift($json_en_values);
        $array_objects_pages = $json_en['surveyjson'];
        echo json_encode($array_objects_pages);
        // print_array($attempt_d_n_n);
    }
    public function survey_custom_values($attempt_d_n_n)
    {
        // $attempt_d_n_n = $this->universal_model->selectz('*', 'survey', 'slug', 1);
        $major_enventual = array();
        foreach ($attempt_d_n_n as $key => $value_object) {
            $manior_xxn = array(
                'id' => $value_object['id'],
                'name' => $value_object['name'],
                'surveydesc' => $value_object['surveydesc'],
                // 'surveyjson' => $value_object['surveyjson'],
                'image' => $value_object['image'],
                'image_url_small' => $value_object['image_url_small'],
                'datecreated' => $value_object['datecreated'],
                'createdby' => $value_object['createdby'],
                'type' => $value_object['type'],
                'slug' => $value_object['slug']
            );
            $json_survey = $value_object['surveyjson'];
            $array_survey = json_decode($json_survey, true);
            $pages = $array_survey['pages'];
            unset_post($array_survey, 'pages');
            $survey_level_1 = array();
            foreach ($pages as $key => $page) {
                if (key_exists('elements', $page)) {
                    $elements = $page['elements'];
                    unset_post($page, 'elements');
                } else {
                    $elements = array();
                }
                $new_elementx = array();
                foreach ($elements as $key_element => $element) {
                    if ($element['type'] == "radiogroup") {
                        $choices = $element['choices'];
                        $new_choices = array();
                        $_value = 1;
                        foreach ($choices as $key => $value) {
                            $value['_value'] = $_value;
                            $_value++;
                            array_push($new_choices, $value);
                        }
                        $element['choices'] = $new_choices;
                        array_push($new_elementx, $element);
                    } else if ($element['type'] == "checkbox") {
                        $choices = $element['choices'];
                        $new_choices = array();
                        $_value = false;
                        foreach ($choices as $key => $value) {
                            $value['_value'] = "false";
                            // $_value = false;
                            array_push($new_choices, $value);
                        }
                        $element['choices'] = $new_choices;
                        array_push($new_elementx, $element);
                    } elseif ($element['type'] == "file") {
                        $element['accept'] = "image/*";
                        $element['allowImagesPreview'] = true;
                        $element['capture'] = "capture";
                        array_push($new_elementx, $element);
                    } else {
                        array_push($new_elementx, $element);
                    }
                }
                $page['elements'] = $new_elementx;
                array_push($survey_level_1, $page);
            }
            // print_array($pages);
            // echo "...................................<br>";
            $array_survey['pages'] = $survey_level_1;
            $manior_xxn['surveyjson'] = $array_survey;
            array_push($major_enventual, $manior_xxn);
        }
        // print_array($major_enventual);
        return $major_enventual;
    }
    public function addsurveycohort()
    {
        $this->form_validation->set_rules('survay_object', 'Survey ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('cohort_object', 'Cohort', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('survay_object', form_error('survay_object'));
            $this->session->set_flashdata('cohort_object', form_error('cohort_object'));
            redirect(base_url('welcome/admin/4'));
        } else {
            $cohort_id_obj = $this->input->post('cohort_object');
            $cohort_id_array = explode("@@", $cohort_id_obj);
            $id = isset($_POST['id_n']) ? $_POST['id_n'] : null;
            switch ($id) {
                case null:
                    $message = "Survey Successfully Added to Cohort";
                    $array_chort_su = array(
                        'survey_id' => $this->input->post('survay_object'),
                        'cohort_id' => $cohort_id_array[0],
                        'cohort_name' => $cohort_id_array[1],
                        'idnumber' => $cohort_id_array['2']
                    );
                    break;
                default:
                    $message = "Survey Edited Item Of Id " . $id;
                    $array_chort_su = array(
                        'survey_id' => $this->input->post('survay_object'),
                        'cohort_id' => $cohort_id_array[0],
                        'cohort_name' => $cohort_id_array[1],
                        'idnumber' => $cohort_id_array['2'],
                        'id' => $id
                    );
                    break;
            }
            $this->universal_model->updateOnDuplicate('cohort_survey', $array_chort_su);
            $this->session->set_flashdata('cohort_success', $message);
            redirect(base_url('welcome/admin/4'));
            // $this->addemployee_subfunc();
        }
    }
    public function edit_cosurv()
    {
        $id_ledger = $this->input->get('id');
        // public function selectz($array_table_n, $table_n, $variable_1, $value_1)
        $array_per_led = $this->universal_model->selectz('*', 'cohort_survey', 'id', $id_ledger);
        $ledger = array_shift($array_per_led);
        $arrayledger = array("ledger_per" => $ledger);
        $this->session->set_flashdata($arrayledger);
        redirect(base_url('welcome/admin/4'), 'refresh');
    }
    public function delete_cosurv()
    {
        $surveyid = $this->input->post('coho_survid');
        // public function deletez($table_name, $variable_1, $value_1)
        $this->universal_model->deletez('cohort_survey', 'id', $surveyid);
        echo json_encode($_POST);
    }
    public function saveobject_surv()
    {
        // $_POST['surveyData'] = json_encode($mamma);
        $entityBody = file_get_contents('php://input');
        $array_n = array(
            'status' => 1,
            'message' => "Survey Posted Successfully"
        );
        // $surveyData = $this->input->post('surveyData');
        // $post=$_POST;
        $array_nana = json_decode($entityBody, true);
        $array_on = array(
            'userid' => $array_nana['userId'],
            'surveyobject' => $array_nana['jsondata'],
        );
        $this->universal_model->insertz('survey_report', $array_on);
        echo json_encode($array_n);
    }
    // public function xxxx()
    // {
    //     $nana = '{"userId":3,"jsondata":"{\"used_fp_method\":\"item2\",\"partner_yesno\":\"item2\",\"accept_fp_method\":\"item1\",\"choose_new_fp_method\":\"item10\"}"}';
    //     $array_nana = json_decode($nana, true);
    //     print_array($array_nana['jsondata']);
    // }
}
