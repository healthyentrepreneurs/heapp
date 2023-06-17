 public function get_chapterto_update($_courseid, $_section_id, $_instance, $_contextid)
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
        if (array_key_exists('exception', $array_of_courses)) {
            // message
            header('Content-Type: application/json');
            echo empty_response("No Existent course and details", 400);
            return;
        } else {
            $generalicons = array();
            $content_needs = array();
            $section_of_interest = array();
            foreach ($array_of_courses as $key => $value) {
                // print_array($value);
                if ($value['id'] == $_section_id) {
                    // print_array($value);
                    $section_of_interest = $value;
                    break;
                }
            }
            // print_array($section_of_interest);
            if (!empty($section_of_interest)) {
                $books_to_search = $section_of_interest['modules'];
                // print_array($books_to_search);
                foreach ($books_to_search as $key => $value) {
                    if ($value['instance'] == $_instance && $value['contextid'] == $_contextid) {
                        // $content_needs = $value['contents'];
                        $arrayicons = array(
                            'type' => 'book',
                            'value' => $value['modicon']
                        );
                        $this->addifempty($generalicons, $arrayicons);
                        $value['modicon'] = $this->onlineUrlReturner($_courseid, $value['modicon'], $value['id'], $value['modname']);
                        $content_needs = $value;
                        break;
                    }
                }
            }
            //Above uni arrays

        }
        header('Content-Type: application/json');
        echo empty_response("course sections loaded", 200, $content_needs);
    }