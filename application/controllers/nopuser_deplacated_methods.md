public function get_coursemodule_updateXX($_courseid, $_section)
    {
        $token = get_admin_token()['token'];
        $functionname = 'core_course_get_contents';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'courseid' => $_courseid
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        $array_output = array(); // We have to keep the format consistent, Array format
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            header('Content-Type: application/json');
            echo empty_response("No Existent course and details", 400);
            return;
        } else {
            $section_of_interest = $array_of_courses[$_section];
            array_push($array_output, $section_of_interest);
            // print_array($array_output);

        }
        header('Content-Type: application/json');
        echo empty_response("course sections loaded", 200, $array_output);
        // print_array($content_needs);
    }


 public function get_coursemodule_update($_courseid, $_section)
    {
        $token = get_admin_token()['token'];
        $functionname = 'core_course_get_contents';
        $data = array(
            'wstoken' => $token,
            'wsfunction' => $functionname,
            'moodlewsrestformat' => 'json',
            'courseid' => $_courseid
        );
        $server_output = curl_request(MOODLEAPP_ENDPOINT, $data, "get", array('App-Key: 123456'));
        $array_of_courses = json_decode($server_output, true);
        $array_output = array(); // We have to keep the format consistent, Array format
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            header('Content-Type: application/json');
            echo empty_response("No Existent course and details", 400);
            return;
        } else {
            // array_push($array_output, $section_of_interest);
            // print_array($array_output);
            foreach ($array_of_courses as $key => $value) {
                // print_array($value);
                if ($value['id'] == $_section) {
                    // print_array($value);
                    array_push($array_output, $value);
                    break;
                }
            }
        }
        header('Content-Type: application/json');
        echo empty_response("course sections loaded", 200, $array_output);
    }