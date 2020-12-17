<!-- Downloadable.php -->
<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
class Downloadable extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
    }

    public function index()
    {
        echo "<h1>Downloadable Api ..</h1>";
    }
    public function create_content($user_id)
    {
        // public function selectz($array_table_n, $table_n, $variable_1, $value_1)
        $vara = $this->universal_model->selectz('*', 'user', 'id_id', $user_id);
        if (empty($vara)) {
            echo empty_response("This User Does Not Exit/Shoud Login Once", 200);
            return null;
        }
        $user_creds = array_shift($vara);
        // $nameone =$user_id . '_attendencelog_' . "data.txt";
        // file_put_contents(APPPATH . '/datamine/' . $nameone, '<?php return ' . var_export($vara, true) . ';');
        #Login
        $domainname = base_url();
        $serverurl = $domainname . '/moodle/login';
        $data = array(
            'username' => $user_creds['username'],
            'password' => $user_creds['password'],

        );
        $server_output = curl_request($serverurl, $data, "post", array('App-Key: 123456'));
        $array_of_output = json_decode($server_output, true);
        if (!empty($array_of_output) && array_value_recursive('profileimageurlsmall', $array_of_output)) {
            // mkdir($user_id.'images');
            // mkdir($user_id, 0755, true);
            $mypath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
            $subpath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR;
            //User profile Images
            $imgone_path = $mypath . 'images';
            if (!is_dir($imgone_path)) {
                mkdir($imgone_path, 0755, true);
            }
            //Folder Images Survey
            $img_survey = $mypath . 'images' . DIRECTORY_SEPARATOR . 'survey';
            if (!is_dir($img_survey)) {
                mkdir($img_survey, 0755, true);
            }
            $imgn = $user_id . 'small_loginimage.' . 'png';
            $img_twon = $user_id . 'big_loginimage.' . 'png';
            $img = $imgone_path . '/' . $imgn;
            $img_two = $imgone_path . '/' . $img_twon;
            file_put_contents($img, file_get_contents(array_value_recursive('profileimageurlsmall', $array_of_output)));
            file_put_contents($img_two, file_get_contents(array_value_recursive('profileimageurl', $array_of_output)));
            //loginjson
            $dir = $mypath;
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $array_of_output['data']['profileimageurlsmall'] = '/images' . '/' . $imgn;
            $array_of_output['data']['profileimageurl'] = '/images' . '/' . $img_twon;
            $array_of_output['data']['password'] = $user_creds['password'];
            $file = fopen($dir . '/' . "login.json", "w");
            $server_output_modif = json_encode($array_of_output);
            fwrite($file, $server_output_modif);
            fclose($file);
            //getcourse details 
            $data_course = array(
                'id' => $array_of_output['data']['id'],
            );
            $serverurl_course = $domainname . '/user/get_moodle_courses/' . $array_of_output['data']['token'] . '/' . $array_of_output['data']['id'];
            $server_output_course = curl_request($serverurl_course, $data_course, "post", array('App-Key: 123456'));
            $array_of_output_course = json_decode($server_output_course, true);
            //nextlink folder
            $dir_nextlink = $mypath . "next_link" . DIRECTORY_SEPARATOR;
            if (!is_dir($dir_nextlink)) {
                mkdir($dir_nextlink, 0755, true);
            }
            //survey folder
            $dir_survey = $dir_nextlink . "survey";
            if (!is_dir($dir_survey)) {
                mkdir($dir_survey, 0755, true);
            }
            $modifyied_courses = array();
            foreach ($array_of_output_course as $key => $value_course) {
                if ($value_course['source'] == "originalm") {
                    $server_output_survey = curl_request($value_course['next_link'], $data_course, "post", array('App-Key: 123456'));
                    $file = fopen($dir_survey . '/' . $value_course['id'] . ".json", "w");
                    fwrite($file, $server_output_survey);
                    fclose($file);
                    $value_course['next_link'] = '/next_link/survey/' . $value_course['id'] . ".json";
                    #Handle Images For Survey
                    print_array($value_course['image_url_small']);
                    print_array($value_course['image_url']);
                    $_image_small_arr = explode('/', $value_course['image_url_small']);
                    $_image_big_arr = explode('/', $value_course['image_url']);
                    $imgn_x = $user_id .  $_image_small_arr[count($_image_small_arr) - 1];
                    $img_twon_x = $user_id . $_image_big_arr[count($_image_big_arr) - 1];
                    $img_n = $img_survey . '/' . $imgn_x;
                    $img_two_n = $img_survey . '/' . $img_twon_x;
                    file_put_contents($img_n, file_get_contents($value_course['image_url_small']));
                    file_put_contents($img_two_n, file_get_contents($value_course['image_url']));
                    $value_course['image_url_small'] = '/images/survey/' . $imgn;
                    $value_course['image_url'] = '/images/survey/' . $img_twon;
                }
                array_push($modifyied_courses, $value_course);
            }
            $modifyied_courses_json = json_encode($modifyied_courses);
            $file = fopen($dir . '/' . 'get_moodle_courses' . ".json", "w");
            fwrite($file, $modifyied_courses_json);
            fclose($file);
            //Zip and Download
            $zip = new ZipArchive;
            $tmp_file =  $subpath . $user_id . 'HE Health.zip';
            // Initialize archive object
            $zip = new ZipArchive();
            $zip->open($tmp_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            // Create recursive directory iterator
            /** @var SplFileInfo[] $files */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($mypath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                // Skip directories (they would be added automatically)
                if (!$file->isDir()) {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($mypath));
                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath);
                }
            }
            // Zip archive will be created only after closing object
            $zip->close();
            // echo empty_response("Zip File Successfully Downloaded", 200);
            header('Content-disposition: attachment; filename=HE Health.zip');
            header('Content-type: application/zip');
            readfile($tmp_file);
        }
    }
    // public function testimages()
    // {
    //     $user_id = 3;
    //     $mypath = APPPATH . 'datamine' . DIRECTORY_SEPARATOR . $user_id . DIRECTORY_SEPARATOR;
    //     $img_survey = $mypath . 'images' . DIRECTORY_SEPARATOR . 'survey';
    //     $big_im = "http://helper.healthyentrepreneurs.nl/uploadscustome/600_user_profile_picuKP.png";
    //     $smal_im = "http://helper.healthyentrepreneurs.nl/uploadscustome/50_user_profile_picuKP.png";
    //     $_image_small_arr = explode('/', $smal_im);
    //     $_image_big_arr = explode('/', $big_im);
    //     $imgn_x = $user_id .  $_image_small_arr[count($_image_small_arr) - 1];
    //     $img_twon_x = $user_id . $_image_big_arr[count($_image_big_arr) - 1];
    //     $img_n = $img_survey . '/' . $imgn_x;
    //     $img_two_n = $img_survey . '/' . $img_twon_x;
    //     file_put_contents($img_n, file_get_contents($smal_im));
    //     file_put_contents($img_two_n, file_get_contents($big_im));
    //     // $value_course['image_url_small'] = '/images/survey/' . $imgn;
    //     // $value_course['image_url'] = '/images/survey/' . $img_twon;
    // }
}
