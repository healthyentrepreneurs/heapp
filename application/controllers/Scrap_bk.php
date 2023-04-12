<?php
header('Access-Control-Allow-Origin: *');


class Scrap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
        // $this->load->library('curl');
        // $this->load->model('moodle_model', '', TRUE);
    }

    public function index($var = null)
    {
        echo "<h1>Scrap Moodle .....</h1>";
        
        // 1. Get the HTML content of the Moodle page
        $url = 'http://192.168.100.4/moodle/mod/quiz/attempt.php?attempt=894&cmid=9';
        $html = file_get_contents($url);

        // 2. Create a DOM document from the HTML content
        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        // 3. Create a DOMXPath object to query the DOM document
        $xpath = new DOMXPath($dom);

        // 4. Find all the <link> tags with a "stylesheet" rel attribute
        $links = $xpath->query('//link[@rel="stylesheet"]');

        // 5. Loop through the <link> tags and get the href attribute value
        foreach ($links as $link) {
            $href = $link->getAttribute('href');

            // 6. Download the CSS file and save it locally
            $css = file_get_contents($href);
            file_put_contents('quizofflinedir/css/' . basename($href), $css);

            // 7. Replace the original href attribute value with the local path in the DOM document
            $link->setAttribute('href', 'quizofflinedir/css/' . basename($href));
        }

        // Save the updated DOM document as a new HTML file
        $updated_html = $dom->saveHTML();
        file_put_contents('quizofflinedir/index.html', $updated_html);
    }
}
