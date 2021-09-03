<?php
ini_set('memory_limit', '1024M');
/**
 * Created by PhpStorm.
 * User: user
 * Date: 5/2/14
 * Time: 4:57 PM
 *
 * i cant begin to stress how important these functions are
 */
function mssql_escape($str)
{
    $str = stripslashes($str);
    return str_replace("'", "''", $str);
}
function curl_request($url, array $data = null, $method, array $app_auth = null)
{
    $ch = curl_init($url);
    switch ($method) {
        case 'get':
            array_walk($data, function (&$a, $b) {
                $a = "$b=$a";
            });
            $query = join("&", array_values($data));
            curl_setopt($ch, CURLOPT_URL, "$url?$query");
            break;
        default:
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            break;
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $app_auth);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
    //$app_auth can include
    /* array(
            'Content-Type:application/json',
            'App-Key: 123456',
            'App-Secret: 1233'
        )*/
    // $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, "http://localhost/heapp/login");
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('email' => 'megasega91@gmail.com', 'password' => 'Mega1java123!@#')));
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // $server_output = curl_exec($ch);
    // curl_close ($ch);
    // print_array($server_output);
}
function empty_response($mesg = "Empty Response", int $code = 500, array $data = null)
{
    $response = array(
        'code' => $code,
        'msg' => $mesg,
        'data' => $data
    );
    return json_encode($response);
}
function hash_internal_user_password($password, $fasthash = false)
{
    global $CFG;

    // Set the cost factor to 4 for fast hashing, otherwise use default cost.
    $options = ($fasthash) ? array('cost' => 4) : array();

    $generatedhash = password_hash($password, PASSWORD_DEFAULT, $options);

    if ($generatedhash === false || $generatedhash === null) {
        // throw new moodle_exception('Failed to generate password hash.');
    }

    return $generatedhash;
}
// generate seo friendly funtions
function seo_url($string)
{
    // Lower case everything
    $string = strtolower($string);
    // Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    // Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    // Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return strtolower($string);
}

// limit strings to only the first 100
function limit_words($string, $word_count)
{
    if (strlen($string) > $word_count) {

        // truncate string
        $stringCut = substr($string, 0, $word_count);

        // make sure it ends in a word so assassinate doesn't become ass...
        $string = substr($stringCut, 0, strrpos($stringCut, ' ')) . '';
    }
    return $string;
}

// check if an image exists
function check_image_existance($path, $image_name)
{
    // buld the url
    $image_url = $path . $image_name;
    if (file_exists($image_url) !== false) {
        return true;
    }
}

// check if file exists
function check_file_existance($path)
{
    // buld the url
    $image_url = $path;
    if (file_exists($image_url) !== false) {
        return true;
    }
}

// date to seconds
function date_to_seconds($date)
{
    return strtotime($date);
}

// remove dashes between words
function remove_dashes($string)
{
    return str_replace('-', ' ', $string);
}

// validate emails
function validate_mail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }
}

function converttodate($date, $format)
{
    $dateAsUnixTimestamp = strtotime($date);
    return date($format, $dateAsUnixTimestamp);
}

// Returns the select options based on the passed data, id and value fields, and selected value
function get_select_options($select_data_array, $value_field, $display_field, $selected, $show_instr = 'Y', $instr_txt = 'Select One')
{
    $drop_HTML = "";
    // Determine whether to show the instruction option
    if ($show_instr == 'Y') {
        $drop_HTML = "<option value='' ";
        // Select by default if there is no selected option
        if ($selected == '') {
            $drop_HTML .= " selected";
        }

        $drop_HTML .= ">- " . $instr_txt . " -</option>";
    }

    foreach ($select_data_array as $data_row) {
        $drop_HTML .= " <option  value='" . addslashes($data_row[$value_field]) . "' ";

        // Show as selected if value matches the passed value
        // check if passed value is an array
        if (is_array($selected)) {
            if (in_array($data_row[$value_field], $selected)) {
                $drop_HTML .= " selected";
            }
        } elseif (!is_array($selected)) {
            if ($selected == $data_row[$value_field]) {
                $drop_HTML .= " selected";
            }
        }

        $display_array = array();
        // Display all data given based on whether what is passed is an array
        if (is_array($display_field)) {
            $drop_HTML .= ">";

            foreach ($display_field as $display) {
                array_push($display_array, $data_row[$display]);
            }

            $drop_HTML .= implode(' - ', $display_array) . "</option>";
        } else {
            $drop_HTML .= ">" . $data_row[$display_field] . "</option>";
        }
    }

    return $drop_HTML;
}

// Picks out all non-zero data from a URl array to be passed to a form
function assign_to_data($urldata, $passed_defaults = array())
{
    $data_array = array();
    $default_field_values = array(
        'Enter Time',
        'Enter First Name',
        'Enter Last Name',
    );
    if (!empty($passed_defaults)) {
        $default_field_values = array_unique(array_merge($default_field_values, $passed_defaults));
    }

    foreach ($urldata as $key => $value) {
        if (in_array($value, $default_field_values)) {
            $value = '_';
        }
        if ($value !== false && trim($value) != '' && !array_key_exists($value, $urldata)) {
            if ($value == '_') {
                $data_array[$key] = '';
            } else {
                $data_array[$key] = $value;
            }
        }
    }

    return $data_array;
}

// Function to update form data from messages set in session
function add_msg_if_any($obj, $data)
{
    if (!empty($data['m']) && $obj->session->userdata($data['m'])) {
        $data['message'] = $obj->session->userdata($data['m']);
        $obj->session->unset_userdata(array(
            $data['m'] => '',
        ));
    }

    return $data;
}

// get all files in a directory randomly
function get_all_directory_files($directory_path)
{
    $scanned_directory = array_diff(scandir($directory_path), array(
        '..',
        '.',
    ));

    return custom_shuffle($scanned_directory);
}

// to shuffle the elements
function custom_shuffle($my_array = array())
{
    $copy = array();
    while (count($my_array)) {
        // takes a rand array elements by its key
        $element = array_rand($my_array);
        // assign the array and its value to an another array
        $copy[$element] = $my_array[$element];
        // delete the element from source array
        unset($my_array[$element]);
    }
    return $copy;
}

// to formate date for mysql
function mysqldate()
{
    $mysqldate = date("m/d/y g:i A", now());
    $phpdate = strtotime($mysqldate);
    return date('Y-m-d H:i:s', $phpdate);
}

// to clear forrm fields
function clear_form_fields()
{
    $str = '';
    $str .= '<script>
							  $(".form-horizontal")[0].reset();
							  </script>';
    return $str;
}

// hu,am friendly date now
function human_date_today()
{
    /*
     * other options
     *
     * $today = date("F j, Y, g:i a"); // March 10, 2001, 5:16 pm
     * $today = date("m.d.y"); // 03.10.01
     * $today = date("j, n, Y"); // 10, 3, 2001
     * $today = date("Ymd"); // 20010310
     * $today = date('h-i-s, j-m-y, it is w Day'); // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
     * $today = date('\i\t \i\s \t\h\e jS \d\a\y.'); // It is the 10th day (10ème jour du mois).
     * $today = date("D M j G:i:s T Y"); // Sat Mar 10 17:16:18 MST 2001
     * $today = date('H:m:s \m \e\s\t\ \l\e\ \m\o\i\s'); // 17:03:18 m est le mois
     * $today = date("H:i:s"); // 17:16:18
     * $today = date("Y-m-d H:i:s"); // 2001-03-
     */
    return date('l jS F Y');
}

// check if ur on localhost
function check_localhost()
{
    if ($_SERVER["SERVER_ADDR"] == '127.0.0.1') {
        return true;
    } else {
        return false;
    }
}

// get real ip address
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { // check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { // to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// seconds to time
function Sec2Time($time)
{
    if (is_numeric($time)) {
        $value = array(
            "years" => 0,
            "days" => 0,
            "hours" => 0,
            "minutes" => 0,
            "seconds" => 0,
        );
        if ($time >= 31556926) {
            $value["years"] = floor($time / 31556926);
            $time = ($time % 31556926);
        }
        if ($time >= 86400) {
            $value["days"] = floor($time / 86400);
            $time = ($time % 86400);
        }
        if ($time >= 3600) {
            $value["hours"] = floor($time / 3600);
            $time = ($time % 3600);
        }
        if ($time >= 60) {
            $value["minutes"] = floor($time / 60);
            $time = ($time % 60);
        }
        $value["seconds"] = floor($time);
        return (array) $value;
    } else {
        return (bool) false;
    }
}

// remove underscors from a string
function remove_underscore($string)
{
    return str_replace('_', ' ', $string);
}

function custom_date_format($format = 'd.F.Y', $date = '')
{
    $date = strtotime($date);
    return date($format, $date);
}

function replace_pipes($string)
{
    return str_replace('|', ',', $string);
}

function last_segment()
{
    $ci = &get_instance();
    // load the profile model
    // to get last segment
    $last = $ci->uri->total_segments();
    $record_num = $ci->uri->segment($last);

    // if its pg
    if ($record_num == 'pg') {
        // to get last segment
        $last = $ci->uri->total_segments() - 1;
        return remove_dashes($ci->uri->segment($last));
    } else {
        return remove_dashes($record_num);
    }
}

// ensure tht jquery is installed even without internet
function load_jquery()
{
?>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script type="text/javascript">
        // Ensure that jQuery is installed even if the Google CDN is down.
        if (typeof jQuery === "undefined") {
            var script = document.createElement('script');
            var attr = document.createAttribute('type');
            attr.nodeValue = 'text/javascript';
            script.setAttributeNode(attr);
            attr = document.createAttribute('src');
            attr.nodeValue = '<?= base_url() ?>assets/js/jquery-3.3.1.min.js';
            script.setAttributeNode(attr);
            document.getElementsByTagName('head')[0].appendChild(script);
        }
    </script>
<?php
}

// function pdf_print($filename, $html_view, $data = NULL) {
// $this->load->helper(array('dompdf', 'file'));
// // page info here, db calls, etc.
// $html = $this->load->view($html_view, $data, true);
// pdf_create($html, &file($filename));
// // or
// // $data = pdf_create($html, '', false);
// // write_file('/home/joash/Documents/SACCO', $data);
// //if you want to write it to disk and/or send it as an attachment
// }
function unset_post(&$post, $name)
{
    unset($post[$name]);
}

// Inspired By dompdf
function change_url_image($data, $url)
{
    $str = $url; // for example "http://localhost/yoursite/";
    $str2 = str_replace($str, "", $data);
    return $str2;
}

function hoursandmins($timetoo)
{
    $values = explode(':', $timetoo);
    return $values[0] . ':' . $values[1];
}

function convertToHoursMins($time, $format = '%02d:%02d')
{
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}

function h2m($hours)
{
    $t = EXPLODE(":", $hours);
    $h = $t[0];
    if (isset($t[1])) {
        $m = $t[1];
    } else {
        $m = "00";
    }
    $mm = ($h * 60) + $m;
    return $mm;
}

// print an array
function print_array($array)
{
    print '<pre>';
    print_r($array);
    print '</pre>';
}

function getWeekday($date)
{
    return date('w', strtotime($date));
}

function getDayWeekFromNumber($number)
{
    $dowMap = array(
        'Sun',
        'Mon',
        'Tue',
        'Wed',
        'Thu',
        'Fri',
        'Sat',
    );
    return $dowMap[$number];
}

function makeacronym($string)
{
    $words = preg_split("/\s+/", $string);
    $acronym = "";
    foreach ($words as $w) {
        $acronym .= $w[0];
    }
    return $acronym;
}

function tem_dateformat($date_initial)
{
    $date = date_create($date_initial);
    return date_format($date, "d/M/y");
}

function json_to_items_commer($array_data)
{
    $_json_string = json_encode($array_data);
    // $_items_by_commar = ;
    $_items_by_commar = str_replace('"', '', str_replace(']', '', str_replace('[', '', $_json_string)));
    return $_items_by_commar;
}

function valid_phone_number_or_empty($value)
{
    $value = trim($value);
    if ($value == '') {
        return true;
    } else {
        if (preg_match('/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/', $value)) {
            return preg_replace('/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/', '($1) $2-$3', $value);
        } else {
            return false;
        }
    }
}

function get_month_combo($selectedmonth, $order, $required)
{
    $allmonths = array(
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December",
    );
    $option_string = '';

    if ($required == 'combo') {
        if (trim($selectedmonth) == '') {
            $option_string .= "<option value='' selected>- Month -</option>";
        }

        if ($order == 'DESC') {
            for ($i = 12; $i > 0; $i--) {
                $option_string .= "<option value='" . $i . "'";
                if ($selectedmonth == $i) {
                    $option_string .= " selected";
                }

                $option_string .= ">" . $allmonths[$i] . "</option>";
            }
        } else {
            for ($i = 1; $i < 13; $i++) {
                $option_string .= "<option value='" . $i . "'";
                if ($selectedmonth == $i) {
                    $option_string .= " selected";
                }

                $option_string .= ">" . $allmonths[$i] . "</option>";
            }
        }
    } else if ($required == 'monthname') {
        if (array_key_exists($selectedmonth, $allmonths)) {
            $option_string = $allmonths[$selectedmonth];
        }
    }

    return $option_string;
}

// function to get the days in a given month
function get_day_combo($selectedday, $month, $year, $required)
{
    $option_string = '';
    // get last day of the month
    if (trim($month) != '' && trim($year) != '') {
        $lastday = date('d', strtotime('last day of ' . $month . ', ' . $year));
    } else {
        $lastday = 31;
    }

    // Returning data for a drop down
    if ($required == 'combo') {
        if (trim($selectedday) == '') {
            $option_string .= "<option value='' selected>- Day -</option>";
        }

        for ($i = 1; $i < ($lastday + 1); $i++) {
            $option_string .= "<option value='" . $i . "'";
            if ($selectedday == $i) {
                $option_string .= " selected";
            }

            $option_string .= ">" . $i . "</option>";
        }
    } else if ($required == 'lastday') {
        $option_string = $lastday;
    }

    return $option_string;
}

// Trim function - cuts text to a certain length
// $string = string to trim; $max_length = longest allowed string before trimming; $append = characters to add on after the trim (typically "...")
function neat_trim($string, $max_length, $append = '')
{
    if (strlen($string) > $max_length) {
        $string = substr($string, 0, $max_length);
        $pos = strrpos($string, ' ');
        if ($pos === false) {
            return substr($string, 0, $max_length) . $append;
        }
        return substr($string, 0, $pos) . $append;
    } else {
        return $string;
    }
}

function objectToArray($object)
{
    if (!is_object($object) && !is_array($object)) {
        return $object;
    }

    return array_map('objectToArray', (array) $object);
}

// Confirm valid integer
function int_check($string)
{
    $pattern = "/^([0-9])+$/";
    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}

// Confirm valid numeric
function numeric_check($string)
{
    $regex = '/^\s*[+\-]?(?:\d+(?:\.\d*)?|\.\d+)\s*$/';
    return preg_match($regex, $string);
}

function jquery_clear_fields()
{
?>
    <script>
        $(".form-horizontal")[0].reset();
        $('textarea').val('');
    </script>
<?php
}

function jquery_redirect($url)
{
?>
    <script>
        // similar behavior as an HTTP redirect
        window.location.replace("<?= $url ?>");
    </script>
<?php
}

function jquery_countdown_redirect($url)
{
?>
    <script>
        var count = 1;
        var countdown = setInterval(function() {
            $("countdown").html(count + " seconds remaining!");
            if (count == 0) {
                clearInterval(countdown);
                window.open("<?= $url ?>", "_self");

            }
            count--;
        }, 1000);
    </script>
<?php
}

function check_live_server()
{
    $whitelist = array(
        '127.0.0.1',
        '::1',
    );

    if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
        return true;
    } else {
        return false;
    }
}

function time_ago($date)
{
    if (empty($date)) {
        return "No date provided";
    }

    $periods = array(
        "second",
        "minute",
        "hour",
        "day",
        "week",
        "month",
        "year",
        "decade",
    );

    $lengths = array(
        "60",
        "60",
        "24",
        "7",
        "4.35",
        "12",
        "10",
    );

    $now = time();

    $unix_date = strtotime($date);

    // check validity of date

    if (empty($unix_date)) {
        return "Bad date";
    }

    // is it future date or past date

    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1) {
        $periods[$j] .= "s";
    }

    return "$difference $periods[$j] {$tense}";
}

// Function that encrypts the entered values
function encryptValue($val)
{
    $num = strlen($val);
    $numIndex = $num - 1;
    $val1 = "";

    // Reverse the order of characters
    for ($x = 0; $x < strlen($val); $x++) {
        $val1 .= substr($val, $numIndex, 1);
        $numIndex--;
    }

    // Encode the reversed value
    $val1 = base64_encode($val1);
    return $val1;
}

// Function that decrypts the entered values
function decryptValue($dval)
{
    // Decode value
    $dval = base64_decode($dval);

    $dnum = strlen($dval);
    $dnumIndex1 = $dnum - 1;
    $dval1 = "";

    // Reverse the order of characters
    for ($x = 0; $x < strlen($dval); $x++) {
        $dval1 .= substr($dval, $dnumIndex1, 1);
        $dnumIndex1--;
    }
    return $dval1;
}

// function to get thumbnail from photo_name
function get_thumbnail($image_name)
{
    $pieces = explode('.', $image_name);

    return $pieces[0] . '_thumb.' . $pieces[1];
}

function get_current_class()
{
    $ci = &get_instance();
    return $ci->router->fetch_class();
}

function truncate($str, $width)
{
    return strtok(wordwrap($str, $width, "...\n"), "\n");
}

// crypto_rand_secure($min, $max) works as a drop in replacement for rand() or mt_rand. It uses openssl_random_pseudo_bytes to help create a random number between $min and $max.
// getToken($length) creates an alphabet to use within the token and then creates a string of length $length.
function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) {
        return $min;
    }
    // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max)];
    }

    return $token;
}

function get_random_password($chars_min = 6, $chars_max = 8, $use_upper_case = false, $include_numbers = false, $include_special_chars = false)
{
    $length = rand($chars_min, $chars_max);
    $selection = 'aeuoyibcdfghjklmnpqrstvwxz';
    if ($include_numbers) {
        $selection .= "1234567890";
    }
    if ($include_special_chars) {
        $selection .= "!@\"#$%&[]{}?|";
    }

    $password = "";
    for ($i = 0; $i < $length; $i++) {
        $current_letter = $use_upper_case ? (rand(0, 1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];
        $password .= $current_letter;
    }

    return $password;
}

function in_array_r($needle, $haystack, $strict = false)
{
    $array_new = array();
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            array_push($array_new, $item);
            // return true;
        }
    }
    return $array_new;
    // return false;
}

function sub_array(&$passrawarray, array $needle)
{
    //simple update
    $myArray = (array) $passrawarray;
    return array_intersect_key($myArray, array_flip($needle));
}

function getDatesFromRange($start, $end, $format = 'Y-m-d')
{
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach ($period as $date) {
        $array[] = $date->format($format);
    }

    return $array;
}

function isThisDayAWeekend($date)
{
    $timestamp = strtotime($date);
    $weekday = date("l", $timestamp);
    $normalized_weekday = strtolower($weekday);
    //    echo $normalized_weekday;
    if (($normalized_weekday == "saturday") || ($normalized_weekday == "sunday")) {
        return "true";
    } else {
        return "false";
    }
}

function getage($birthDate, $delimeter)
{
    // $birthDate = $birthDate;
    // explode the date to get month, day and year
    $birthDate = explode($delimeter, $birthDate);
    // get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
    return $age;
}

function columnLetter($c)
{
    $c = intval($c);
    if ($c <= 0) {
        return '';
    }

    $letter = '';
    while ($c != 0) {
        $p = ($c - 1) % 26;
        $c = intval(($c - $p) / 26);
        $letter = chr(65 + $p) . $letter;
    }
    return $letter;
}

function removelastchar($stringmine, $delimeter)
{
    $newarraynama = rtrim($stringmine, $delimeter);
    return $newarraynama;
}

// Search Dimentional Array Return Array Values
function searcharray($value, $key, $array)
{
    $arrayk = array();
    foreach ($array as $k => $val) {
        if ($val[$key] == $value) {
            array_push($arrayk, $val);
        }
    }
    return $arrayk;
}

function array_diff_assoc_recursive($array1, $array2)
{
    foreach ($array1 as $key => $value) {
        if (is_array($value)) {
            if (!isset($array2[$key])) {
                $difference[$key] = $value;
            } elseif (!is_array($array2[$key])) {
                $difference[$key] = $value;
            } else {
                $new_diff = array_diff_assoc_recursive($value, $array2[$key]);
                if ($new_diff != false) {
                    $difference[$key] = $new_diff;
                }
            }
        } elseif (!isset($array2[$key]) || $array2[$key] != $value) {
            $difference[$key] = $value;
        }
    }
    return !isset($difference) ? 0 : $difference;
}

function array_insert(&$array, $value, $index)
{
    return $array = array_merge(array_splice($array, max(0, $index - 1)), array(
        $value,
    ), $array);
}

function array_slice_keys($array, $keys = null)
{
    if (empty($keys)) {
        $keys = array_keys($array);
    }
    if (!is_array($keys)) {
        $keys = array(
            $keys,
        );
    }
    if (!is_array($array)) {
        return array();
    } else {
        return array_intersect_key($array, array_fill_keys($keys, '1'));
    }
}

function aasort(&$array, $key)
{
    $sorter = array();
    $ret = array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii] = $va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii] = $array[$ii];
    }
    $array = $ret;
}
function recursive_array_search($needle, $haystack)
{
    foreach ($haystack as $key => $value) {
        if ($needle === $value) {
            return array($key);
        } else if (is_array($value) && $subkey = recursive_array_search($needle, $value)) {
            array_unshift($subkey, $key);
            return $subkey;
        }
    }
}
function humanreadable_date($date)
{
    return date("F jS, Y", strtotime($date));
}
function rowwidths($date_from_fot, $date_to_fot)
{
    $my_dates = getDatesFromRange($date_from_fot, $date_to_fot);
    $clomun_headertwo = array();
    foreach ($my_dates as $date_value) {
        array_push($clomun_headertwo, date('D', strtotime($date_value)));
    }
    return $clomun_headertwo;
}
function rowwidthstwo($date_from_fot, $date_to_fot)
{
    $my_dates = getDatesFromRange($date_from_fot, $date_to_fot);
    $clomun_headertwo = array();
    foreach ($my_dates as $date_value) {
        array_push($clomun_headertwo, date('jS', strtotime($date_value)));
    }
    return $clomun_headertwo;
}

function printvalues($key, $arrayn)
{
    if (empty($arrayn)) {
        return '';
    }
    if (!array_key_exists($key, $arrayn)) {
        $arrayn[$key] = '';
    }
    return $arrayn[$key];
}

function array_value_recursive($key, array $arr)
{
    $val = array();
    array_walk_recursive($arr, function ($v, $k) use ($key, &$val) {
        if ($k == $key) array_push($val, $v);
    });
    return count($val) > 1 ? $val : array_pop($val);
}
// function delete_value(&$original, $del_val)
// {
//     //make a copy of the original, to avoid problems of modifying an array that is being currently iterated through
//     $copy = $original;
//     foreach ($original as $key => $value) {
//         //for each value evaluate if it is equivalent to the one to be deleted, and if it is capture its key name.
//         if ($del_val === $value) $del_key[] = $key;
//     };
//     //If there was a value found, delete all its instances
//     if ($del_key !== null) {
//         foreach ($del_key as $dk_i) {
//             unset($original[$dk_i]);
//         };
//         //optional reordering of the keys. WARNING: only use it with arrays with numeric indexes!
//         /*
//     $copy = $original;
//     $original = array();
//     foreach ($copy as $value) {
//         $original[] = $value;
//     };
//     */
//         //the value was found and deleted
//         return true;
//     };
//     //The value was not found, nothing was deleted
//     return false;
// }
function delete_value(&$original, $del_val)
{
    //make a copy of the original, to avoid problems of modifying an array that is being currently iterated through
    $copy = $original;
    foreach ($original as $key => $value) {
        //for each value evaluate if it is equivalent to the one to be deleted, and if it is capture its key name.
        if ($del_val === $value) $del_key[] = $key;
    };
    //If there was a value found, delete all its instances
    if ($del_key !== null) {
        foreach ($del_key as $dk_i) {
            unset($original[$dk_i]);
        };
        //optional reordering of the keys. WARNING: only use it with arrays with numeric indexes!
        /*
    $copy = $original;
    $original = array();
    foreach ($copy as $value) {
        $original[] = $value;
    };
    */
        //the value was found and deleted
        return true;
    };
    //The value was not found, nothing was deleted
    return false;
}
function cleanContent($content)
{
    $content = nl2br($content);
    $content = preg_replace('#(?:<br\s*/?>\s*?){2,}#', ' ', $content);
    return trim(strip_tags($content));
}

function multi_thread_curl($urlArray, $optionArray, $nThreads) {

  //Group your urls into groups/threads.
  $curlArray = array_chunk($urlArray, $nThreads, $preserve_keys = true);

  //Iterate through each batch of urls.
  $ch = 'ch_';
  foreach($curlArray as $threads) {      

      //Create your cURL resources.
      foreach($threads as $thread=>$value) {

      ${$ch . $thread} = curl_init();

        curl_setopt_array(${$ch . $thread}, $optionArray); //Set your main curl options.
        curl_setopt(${$ch . $thread}, CURLOPT_URL, $value); //Set url.

        }

      //Create the multiple cURL handler.
      $mh = curl_multi_init();

      //Add the handles.
      foreach($threads as $thread=>$value) {

      curl_multi_add_handle($mh, ${$ch . $thread});

      }

      $active = null;

      //execute the handles.
      do {

      $mrc = curl_multi_exec($mh, $active);

      } while ($mrc == CURLM_CALL_MULTI_PERFORM);

      while ($active && $mrc == CURLM_OK) {

          if (curl_multi_select($mh) != -1) {
              do {

                  $mrc = curl_multi_exec($mh, $active);

              } while ($mrc == CURLM_CALL_MULTI_PERFORM);
          }

      }

      //Get your data and close the handles.
      foreach($threads as $thread=>$value) {

      $results[$thread] = curl_multi_getcontent(${$ch . $thread});

      curl_multi_remove_handle($mh, ${$ch . $thread});

      }
      //Close the multi handle exec.
      curl_multi_close($mh);

  }
  return $results;

} 
