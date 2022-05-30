<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Board_model');
        $this->load->library('form_validation');
      
    }

	public function index()
	{
		$this->load->helper('download');
        $data['id'] = $this->session->userdata('id');
        $file_path = $this->input->post('file_path');
        $file_name = $this->input->post('file_name');
        
        // var_dump($file_path);
        // var_dump($file_name);
        //     exit;
        for($count = 0; $count < count($file_path); $count++){
            if( empty($file_name[$count]) ) {
                $file_name = preg_replace('/^.*\//', '', $file_path[$count]);
                
            }
            $file_data = file_get_contents('./image/'.$file_path[$count]);
            var_dump($file_data);
            var_dump($file_name);
            exit;
            force_download(rawurlencode($file_name), $file_data);
        }

	}

   
}
