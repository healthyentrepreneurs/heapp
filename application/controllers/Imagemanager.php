<!-- Imagemanager.php -->
<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require_once FCPATH . 'vendor/autoload.php';

use \Gumlet\ImageResize;

class Imagemanager extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
    }
    public function index()
    {
        echo "<h1>Imagemanager Icon Manager..</h1>";
    }
    public function couser_subcontent()
    {
        $_link_content = $this->input->get('link');
        $fullname = $this->input->get('fullname');
        $next_link_course = decryptValue($_link_content);
        $server_output = curl_request($next_link_course, array(), "get", array('App-Key: 123456'));
        $courses_content = json_decode($server_output, true);
        // print_array($courses_content);
        if (empty($courses_content)) {
            $subcontents = array();
            // print_array($subcontents);
        } else {
            $subcontents = $courses_content['data'];
            // print_array($subcontents);
            // $this->image_nav($subcontents, 0);
        }
        if ($this->session->userdata('logged_in_lodda')) {
            $data['header'] = 'parts/header';
            $data['coursename'] = $fullname;
            $data['content_admin'] = 'pages/admin/admin_contentsub';
            $data['sidenav'] = 'pages/admin/navadmin';
            $data['courses_sub'] = $subcontents;
            // foreach ($subcontents as $key_fix => $value_fix) {
            //     $ths_id = $value_fix['id'];
            //     $module = $value_fix['modules'];
            //     foreach ($module as $valuemodule) {
            //         $id_one = $valuemodule['id'];
            //         $name = $valuemodule['name'];
            //         $pepe = $this->universal_model->selectzy('*', 'icon_table', 'name', $name, 'couseid', null);
            //         if (!empty($pepe)) {
            //             $_value_to_up = array(
            //                 'couseid' => $ths_id,
            //                 'bookid' => $id_one
            //             );
            //             $_id_array = array_shift($pepe);
            //             // print_array($pepe);
            //             $this->universal_model->updatez('id', $_id_array['id'], 'icon_table', $_value_to_up);
            //         }
            //     }
            // }
            $this->load->view('pages/hometwo', $data);
        } else {
            $data['content'] = 'pages/index';
            $this->load->view('pages/homeone', $data);
        }
    }
    public function upload_image_sub()
    {
        $_link_content = $this->input->get('link');
        $name = $this->input->get('name');
        $type = $this->input->get('type');
        $couseid = $this->input->get('couseid');
        $bookid = $this->input->get('bookid');
        $next_link_course = decryptValue($_link_content);
        // echo $type; Nana
        if ($this->session->userdata('logged_in_lodda')) {
            $data['header'] = 'parts/header';
            $data['name'] = $name;
            $data['type'] = $type;
            $data['couseid'] = $couseid;
            $data['bookid'] = $bookid;
            $data['icon_image'] = $next_link_course;
            $data['content_admin'] = 'pages/admin/upload_icon';
            $data['sidenav'] = 'pages/admin/navadmin';
            $this->load->view('pages/hometwo', $data);
        } else {
            $data['content'] = 'pages/index';
            $this->load->view('pages/homeone', $data);
        }
    }
    public function upload_resize()
    {
        $this->addemployee_subfunc();
        redirect(base_url('imagemanager/upload_image_sub?link=' . encryptValue($this->input->post('original')) . '&name=' . $this->input->post('name') . '&type=' . $this->input->post('type') . '&couseid=' . $this->input->post('couseid') . '&bookid=' . $this->input->post('bookid')));
    }
    function addemployee_subfunc()
    {
        if ($this->validate_image("user_profile_pic" . getToken(3))) {
            $data = array(
                'upload_data' => $this->upload->data()
            );
            $name_file = $data['upload_data'];
            $_POST['user_profile_pic'] = $name_file['file_name'];
            $_POST['original_one'] = $this->input->post('original');
            // $_POST['original'] = $this->input->post('original');
            $this->create_thumbnail(50, 50, './uploadicons/' . "50_" . $this->input->post('user_profile_pic'), './uploadicons/' . $this->input->post('user_profile_pic'));
            $this->create_thumbnail(60, 60, './uploadicons/' . "60_" . $this->input->post('user_profile_pic'), './uploadicons/' . $this->input->post('user_profile_pic'));
            $this->create_thumbnail(600, 600, './uploadicons/' . "600_" . $this->input->post('user_profile_pic'), './uploadicons/' . $this->input->post('user_profile_pic'));
            $_POST['image_small'] = "50_" . $this->input->post('user_profile_pic');
            $_POST['image_medium'] = "60_" . $this->input->post('user_profile_pic');
            $_POST['image_big'] = "600_" . $this->input->post('user_profile_pic');
            $_POST['original'] = base_url("uploadicons/600_" . $this->input->post('user_profile_pic'));
            unlink("uploadicons/" . $name_file['file_name']);
            $value_check = $this->universal_model->select_bytwo_limit('bookid', $this->input->post('bookid'), 'couseid', $this->input->post('type'), 'type', $this->input->post('type'));
            // $value_check = $this->universal_model->selectzx('*', 'icon_table', 'original', $this->input->post('original_one'), 'name', $this->input->post('name'), 'type', $this->input->post('type'));
            if (empty($value_check)) {
                $user_add = array(
                    'name' => $this->input->post('name'),
                    'type' => $this->input->post('type'),
                    'bookid' => $this->input->post('bookid'),
                    'couseid' => $this->input->post('couseid'),
                    'original' => $this->input->post('original'),
                    'original_one' => $this->input->post('original_one'),
                    'image_small' => $this->input->post('image_small'),
                    'image_medium' => $this->input->post('image_medium'),
                    'image_big' => $this->input->post('image_big'),
                );
                // print_array($user_add);
                // print_array("Success");
                $this->go_bookh5picon($user_add);
                $this->universal_model->updateOnDuplicate('icon_table', $user_add);
            } else {
                $_original = array_shift($value_check)['original_one'];
                $user_add = array(
                    'name' => $this->input->post('name'),
                    'type' => $this->input->post('type'),
                    'bookid' => $this->input->post('bookid'),
                    'couseid' => $this->input->post('couseid'),
                    'original' => $this->input->post('original'),
                    'original_one' => $_original,
                    'image_small' => $this->input->post('image_small'),
                    'image_medium' => $this->input->post('image_medium'),
                    'image_big' => $this->input->post('image_big'),
                );
                // print_array($user_add);
                // print_array("Success X");
                $this->go_bookh5picon($user_add);
                $this->universal_model->updateOnDuplicate('icon_table', $user_add);
            }
        } else {
            $_POST['original'] = $this->input->post('original');
            // print_array($_POST);
            # code...
        }
    }

    public function go_bookh5picon($user_add)
    {
        $_REMOTEGO = REMOTE_GO . "bookhfiveiconadded";
        $json_nand = json_encode($user_add);  // convert the user_add array to a JSON string
        curl_request_json($_REMOTEGO, $json_nand);
    }

    public function loadcourseicons()
    {
        // Define an array with all the fields of the 'user_add'
        $array_table_n = array(
            'name',
            'type',
            'bookid',
            'couseid',
            'original',
            'original_one',
            'image_small',
            'image_medium',
            'image_big'
        );

        // Get all records from 'icon_table'
        $records = $this->universal_model->selectall($array_table_n, 'icon_table');

        // Check if any record exists
        if (!empty($records)) {
            // Loop through each record
            foreach ($records as $record) {
                // print_array($record);
                // Call go_bookh5picon with the record
                $this->go_bookh5picon($record);
            }
            // All records have been processed
            // header('Content-Type: application/json');
            echo empty_response("Icons are loaded", 200);
        } else {
            echo empty_response("No records found", 400);
        }
    }


    public function validate_image($generatedname)
    {
        $config['overwrite'] = TRUE;
        // $config['upload_path']          = APPPATH.'datamine/';
        $config['upload_path'] = './uploadicons/';
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
            if (strpos($error['error'], "The upload path does not appear to be valid.") !== FALSE) {
                $this->session->set_flashdata('validate_image', "The upload path does not appear to be valid.");
                // print_array("The upload path does not appear to be valid.");
                $this->form_validation->set_message('validate_image', 'The upload path is not valid');
                return FALSE;
            }
            if (strpos($error['error'], "The upload destination folder does not appear to be writable.") !== FALSE) {
                $this->session->set_flashdata('validate_image', "The upload destination folder does not appear to be writable.");
                // print_array("The upload destination folder does not appear to be writable.");
                $this->form_validation->set_message('validate_image', 'Distination Folder Not writtable');
                return FALSE;
            }
            //The upload destination folder does not appear to be writable
            // The upload path does not appear to be valid.
            // if()
        } else {
            $this->session->set_flashdata('validate_image_success', "Successfully Uploaded");
            // print_array("Mamama");
            return TRUE;
        }
    }

    public function create_thumbnail($width, $height, $new_image, $image_source)
    {
        $image = new ImageResize($image_source);
        $image->resizeToBestFit($width, $height);
        $image->save($new_image);
    }
    public function test_imageupload()
    {
        // $config['upload_path']          = './uploads/';
        $config['upload_path'] = './uploadicons/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('user_profile_pic')) {
            $error = array('error' => $this->upload->display_errors());
            print_array($error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            print_array($data);
        }
        // $this->addemployee_subfunc();
        // redirect(base_url('imagemanager/upload_image_sub?link=' . encryptValue($this->input->post('original')) . '&name=' . $this->input->post('name') . '&type=' . $this->input->post('type'). '&couseid=' . $this->input->post('couseid'). '&bookid=' . $this->input->post('bookid')));
    }
    public function test_icons($state = 0)
    {
        // 'name', "HIV-Okawuka kasiriimu"
        if ($state == 0) {
            $pwapa = $this->universal_model->getmedups();
            print_array($pwapa);
        } else {
            $pwapa = $this->universal_model->deletedubs();
            print_array($pwapa);
        }
    }

    public function downloadicons()
    {
        // Load the url helper
        $this->load->helper('url');

        // Define an array with the 'original' field
        $array_table_n = array('original');

        // Get all records from 'icon_table'
        $records = $this->universal_model->selectall($array_table_n, 'icon_table');

        // Check if any record exists
        if (!empty($records)) {
            // Loop through each record
            foreach ($records as $record) {
                // Get the image URL
                $url = $record['original'];

                // Extract image name from URL
                $image_name = extract_image_name($url);

                // Define the path where the image will be saved
                $img_path = FCPATH . 'uploadicons/' . $image_name;

                // Check if the image data can be fetched
                if ($img_data = @file_get_contents($url)) {
                    // Save the image data to a file, overwriting any existing file
                    file_put_contents($img_path, $img_data);
                } else {
                    // Log a message if the image data couldn't be fetched
                    error_log("Could not fetch image data from URL: $url");
                }
            }

            // All records have been processed
            echo "Images have been downloaded.";
        } else {
            echo "No records found in the 'icon_table'.";
        }
    }
}
