<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('extract_image_name')) {
    function extract_image_name($url) {
        $parsed_url = parse_url($url);
        $path = $parsed_url['path'];
        $image_name = basename($path);
        return $image_name;
    }
}
