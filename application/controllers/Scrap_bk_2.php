<?php

header('Access-Control-Allow-Origin: *');

class Scrap extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('universal_model');
        $this->load->model('user_model', '', TRUE);
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
            file_put_contents(FCPATH . 'quizofflinedir/css/' . basename($href), $css);

            // 7. Replace the original href attribute value with the local path in the DOM document
            $link->setAttribute('href', base_url('quizofflinedir/css/') . basename($href));
        }

        // 8. Find all the <script> tags
        $scripts = $xpath->query('//script');

        // 9. Loop through the <script> tags and get the src attribute value
        foreach ($scripts as $script) {
            $src = $script->getAttribute('src');

            // 10. Download the JavaScript file and save it locally
            if (!empty($src)) {
                $js = file_get_contents($src);
                file_put_contents(FCPATH . 'quizofflinedir/js/' . basename($src), $js);

                // 11. Replace the original src attribute value with the local path in the DOM document
                $script->setAttribute('src', base_url('quizofflinedir/js/') . basename($src));
            }
        }

        // 12. Save the updated DOM document as a new HTML file
        $updated_html = $dom->saveHTML();
        file_put_contents(FCPATH . 'quizofflinedir/index.html', $updated_html);
    }
}
