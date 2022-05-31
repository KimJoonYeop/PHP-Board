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
        $this->load->library('zip');
        $data['id'] = $this->session->userdata('id');
        // $test = $_SERVER['DOCUMENT_ROOT'];
        // var_dump($test);
        // exit;
        // $file_path = $this->input->get('file_path');
        // $file_path = str_replace(' ','_',$file_path);
        $file_name = explode(',',$this->input->get('file_name'), 3);

        //$file = ['./image/'.$file_name[0], './image/'.$file_name[1], './image/'.$file_name[2]];

        for($i = 0; $i < count($file_name); $i++){
            $this->zip->read_file('./image/'.$file_name[$i]);
        }
        $this->zip->download('DownloadFile.zip');
    //     foreach( $file_name as $file){
    //         var_dump($file);
    //         if( !empty($file) ){
    //             $file_data = file_get_contents('./image/'.$file);
    //             force_download(rawurlencode($file), $file_data);
    //         }
    //     }
    //     for($i = 0; $i < count($file_name); $i++){
    //         if( !empty($file_name[$i]) ){
    //             $file = $file_name[$i];
    //             var_dump($file);
    //         }
    //         // }else if( empty($file_name[$i]) ){
    //         //     break;
    //         // }
    //         $file_data = file_get_contents('./image/'.$file);
    //         force_download(rawurlencode($file), $file_data);
    //     }
    //     if( !empty($file_name[0]) ){
    //         $files1 = $file_name[0]; 
    //     }

    //     if( !empty($file_name[1]) ){
    //         $files2 = $file_name[1]; 
    //     }

    //     if( !empty($file_name[2]) ){
    //         $files3 = $file_name[2]; 
    //     }
    //     var_dump($file_name);
    //     var_dump($rows);
    //     var_dump($files1);
    //     var_dump($files2);
    //     var_dump($files3);
    //     exit;

    //     if( empty($file_name[$count]) ) {
    //         $file_name = preg_replace('/^.*\//', '', $file_path[$count]);
    //     }
	// }

    }
}
