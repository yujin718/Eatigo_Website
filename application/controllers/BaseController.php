<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BaseController extends CI_Controller {

    public $database;

    function __construct() {
        parent::__construct();
        $this->connectDB();
        $this->load->helper('url');
    }
    public function connectDB() {
        $this->database = $this->load->database();
    }
    
    public function getViewParameters($pageName='',$role='Customer',$title='Bruped')
    {        
        $data['title'] = $title;
        $data['pageName'] = $pageName;
        $data['role'] = $role;
        return $data;
    }
}

?>