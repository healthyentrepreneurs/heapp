<!-- core_h5p_get_trusted_h5p_file -->

<?php
header('Access-Control-Allow-Origin: *');
class Hfivep extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        // Load any other necessary models or libraries here
    }

    public function index()
    {
        echo "<h1>Hfivep Api ...</h1>";
    }

    // public function get_trusted_h5p_file($fileid, $token)
    // {
    //     $functionname = 'core_h5p_get_trusted_h5p_file';
    //     $data = array(
    //         'fileid' => $fileid,
    //         'wstoken' => $token,
    //         'wsfunction' => $functionname,
    //         'moodlewsrestformat' => 'json'
    //     );

    //     $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));

    //     echo $server_output;
    // }

    public function get_trusted_h5p_file()
    {
        // Get POST data
        $url = $this->input->post('url');
        $token = $this->input->post('wstoken');
        $frame = $this->input->post('frame');
        $export = $this->input->post('export');
        $copyright = $this->input->post('copyright');
        $embed = $this->input->post('embed');

        // Set defaults if no data provided
        $frame = isset($frame) ? $frame : 0;
        $export = isset($export) ? $export : 0;
        $copyright = isset($copyright) ? $copyright : 0;
        $embed = isset($embed) ? $embed : 0;

        $functionname = 'core_h5p_get_trusted_h5p_file';
        $data = array(
            'url' => $url,
            'frame' => $frame,
            'export' => $export,
            'copyright' => $copyright,
            'embed' => $embed,
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json'
        );

        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "post", array('App-Key: 123456'));
        echo $server_output;
    }
}
// https://appdev.healthyentrepreneurs.nl/pluginfile.php/8833/mod_h5pactivity/package/0/find-the-words-7-7.h5p
// curl 'https://appdev.healthyentrepreneurs.nl/webservice/rest/server.php?moodlewsrestformat=json' --data 'wsfunction=core_h5p_get_trusted_h5p_file&url=https://appdev.healthyentrepreneurs.nl/pluginfile.php/8833/mod_h5pactivity/package/0/find-the-words-7-7.h5p&frame=0&export=0&copyright=0&embed=0&wstoken=01bd8b1e707671384445694d743f6ba8' | python -m "json.tool"
// SELECT id, filename, filepath, filesize, mimetype, timemodified FROM mdl_files WHERE component LIKE 'core_h5p';
?>