<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Main_model');
    }

	function login(){
        
        $data['title'] = 'CodeIgniter Simple Login Form With Sessions';
        $this->load->view("board/login", $data);
    }

    function login_validation()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id', 'Userid', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        $data['id'] = $this->session->userdata('id');
        
        if($this->form_validation->run())
        {   
            $id = $this->input->post('id');
            $password = $this->input->post('password');
            $can_login = $this->Main_model->can_login($id, $password);
            $data['session'] = $can_login->row_array();
            //model_function
            if( $can_login->num_rows() >0 ){
                $session_data = array(
                    'id' => $id,
                );
                $this->session->set_userdata($session_data);
                redirect('/board', 'refresh'); //게시글 목록으로
            }else{
                $this->session->set_flashdata('error', 'Invalid Username and Password');
                redirect('/main/login_validation','refresh');
            }

        }
        else
        {
            $this->load->view("board/login", $data);
        }
    }

    public function enter(){
        if( $this->session->userdata('id') !=''){
            echo '<h2>Welcome -'.$this->session->userdata('id').'</h2>';
            echo '<label><a href="/main/logout">Logout</a></label>'; 
        }else{
            redirect('/main/login_validation', 'refresh');
        }
    }

    public function logout(){
        $this->session->unset_userdata('id');
        redirect('/main/login_validation', 'refresh');
    }
}
