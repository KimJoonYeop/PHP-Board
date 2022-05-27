<?php
class Board extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Board_model');
      
    }
    
    //게시글 목록
    public function index()
    {   
        //게시판 목록 출력
        
        $data['id'] = $this->session->userdata('id'); 

        $this->search();
    }

    //키워드 검색
    public function search (){
        $this->load->library('form_validation');

        $data['id'] = $this->session->userdata('id'); 
        $type = $this->input->post('type',TRUE);
        $search_word = $this->input->post('search_word', TRUE);
        $title_word = '';
        $writer_word = '';
        
        if($type == 'title'){
            $title_word = $search_word;
            $writer_word = '';
        }else if($type == 'writer'){
            $title_word = '';
            $writer_word = $search_word;
        }else{
            $title_word = '';
            $writer_word = '';
        }
        var_dump($type);
        var_dump($title_word);
        var_dump($writer_word);
        var_dump($search_word);
        $data['board'] = $this->Board_model->get_board_list($title_word, $writer_word)->result_array(); #컨트롤러 딴에서 데이터 받기
    
        $this->load->view('board/index', $data);
    }

    //게시글 상세보기 및 수정 & 리뷰 출력
    public function view($bno = NULL){   
       
        $data['board_item'] = $this->Board_model->get($bno)->row_array();
        $data['reply_list'] = $this->Board_model->reply_list($bno)->result_array();
        // var_dump($data['reply_list']);
        // exit;
        // $test = $data['board_item']['bno'];
        // var_dump($test);
        // exit;
        $data['id'] = $this->session->userdata('id');

        $this->load->view('board/view' , $data);
        
    }

    //게시글 수정
    public function update($bno = NULL){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', '게시판 제목을 입력해주세요', 'required');
        $this->form_validation->set_rules('content', '게시판 내용을 입력해주세요', 'required');

        $title = $this->input->post('title', TRUE); //TRUE -> SQL injection 방어. 필수임!!
        $content =  $this->input->post('content', TRUE);

        $data['id'] = $this->session->userdata('id');

        // $data_arr = array(
        //     'title' => $title,
        //     'content' => $content,
        // );

        if ( $this->form_validation->run() )
        {
           #$redirect = $this->load->view('board/view' , $data);
           
            $this->Board_model->board_update($title, $content, $bno);
            $current = current_url();
            redirect('board/view/'.$bno);
        }

    }

    //게시글 등록
    public function create()
    {
        #$this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'Title을 입력해주세요', 'required');
        $this->form_validation->set_rules('content', 'text를 입력해주세요', 'required');

        $data['title'] = '게시판 등록';
        $data['id'] = $this->session->userdata('id');

        $title = $this->input->post('title', TRUE);
        $content = $this->input->post('content', TRUE);
        $writer = $data['id'];
        
        $data_arr = array(
            'title' => $title,
            'content' => $content,
            'writer' => $writer
        );

        if ( $this->form_validation->run() === FALSE )
        {
            $this->load->view('board/create', $data);
        }
        else
        {
            $this->Board_model->board_insert($data_arr);
            redirect('/board','refresh');
        }
    }

    //유저 게시글 삭제
    public function delete($bno){
        if( empty($bno) ){
            echo 'bno is null';
        }else{
            $this->Board_model->board_delete($bno);
            #$current = current_url();
            redirect('/board','refresh');
        }
        
    }

    //관리자 게시글 삭제
    public function admin_delete($bno){
        if( empty($bno) ){
            echo 'bno is null';
        }else{
            $this->Board_model->admin_delete($bno);
            redirect('/board','refresh');
        }
    }

    //리뷰 등록
    public function review_insert($bno){
        $this->load->library('form_validation');

        $this->form_validation->set_rules('reply', '리뷰내용을 입력해주세요', 'required');

        $data['id'] = $this->session->userdata('id');

        $reply = $this->input->post('reply', TRUE);
        $id = $data['id'];

        $data_arr = array (
            'reply' => $reply,
            'id' => $id,
            'bno' => $bno
        );
        if( $this->form_validation->run() ){
            $this->Board_model->reply_insert($data_arr);
            // $current = current_url();
            redirect('/board/view/'.$bno, 'refresh');
        }
    }

    //리뷰 수정
    public function review_update($rno, $bno){
        $this->load->library('form_validation');

        // $uri_string = uri_string();
        // var_dump($uri_string);
        // exit;
        $this->form_validation->set_rules('reply_test', '리뷰내용을 입력해주세요', 'required');

        $data['id'] = $this->session->userdata('id');

        $reply = $this->input->post_get('reply_test', TRUE);



        if ( $this->form_validation->run()  )
        {
            $this->Board_model->reply_update($reply, $rno);
            redirect('/board/view/'.$bno, 'refresh');
        }  
    }

    //리뷰 삭제
    public function review_delete($rno, $bno){
        if( empty($rno) ){
            echo 'rno is null';
        }else{
            $this->Board_model->reply_delete($rno);
            #$current = current_url();
            redirect('/board/view/'.$bno);
        }
    }
}