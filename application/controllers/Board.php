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
    public function search ($page = 1){ //url에 param값을 받을 수 있음
        $this->load->library('form_validation');

        $data['id'] = $this->session->userdata('id'); 

        $data['type'] = $this->input->get('type',TRUE); //pagination이 form이 아닌 location으로 데이터를 전송하기 때문에 get으로 받아야함
        $data['search_word'] = $this->input->get('search_word', TRUE);

        // $title_word = '';
        // $writer_word = '';
        
        // if($data['type'] == 'title'){
        //     $title_word = $data['search_word'];
        //     $writer_word = '';
        // }else if($data['type'] == 'writer'){
        //     $title_word = '';
        //     $writer_word = $data['search_word'];
        // }else{
        //     $title_word = '';
        //     $writer_word = '';
        // }

        $per_page = 5;
        $start = ($page-1) * $per_page;
        $data['total_rows'] = $this->Board_model->board_list_count($data['type'], $data['search_word']);
        $data['pagination'] = $this->common_pagination('/board/search', $data['total_rows'], $per_page);

        //log_message('debug', json_encode(array('total_rows' => $total_rows, 'per_page' => $per_page, 'start' => $start, 'page' => $page), JSON_UNESCAPED_UNICODE));
        $data['board'] = $this->Board_model->get_board_list($data['type'], $data['search_word'], $start, $per_page)->result_array();
        
        // echo json_encode($data['board'], JSON_UNESCAPED_UNICODE);
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

    //페이지네이션 테스트
    public function ptest(){
       $data['board_list_count'] = $this->Board_model->board_list_count(5)->row_array();
       var_dump( (int)$data['test']['Tboard_list_count']);
    }
    
    //페이지네이션
    public function common_pagination($base_url='', $total_rows, $per_page){
        $config['base_url']= "$base_url"; //페이징 주소
        $config['num_links'] = 2;//선택된 페이지번호 좌우로 몇개의 “숫자”링크를 보여줄지 설정
        $config['total_rows']=$total_rows;//전체 게시물 수
        $config['use_page_numbers'] = TRUE;//실제 페이지 번호를 보여주고 싶다면, TRUE
        $config['page_query_string'] = FALSE;
        $config['reuse_query_string'] = TRUE;//
        $config['query_string_segment'] ="page";  //쿼리스트링 역할을 해줌 
        $config['per_page']=$per_page; //한 페이지에 표시할 게시물 수
        $config['uri_segment']=3; //페이지 번호가 위치한 세그먼트
        $config['full_tag_open'] = '<p class="pagination">';//감싸는 태그
        $config['full_tag_close'] = '</p>';//감싸는 태그
        $config['cur_tag_open'] = '<span class="page-item"><a href="javascript:void(0);" class="active">';//현재 페이지 링크의 여는 태그
        $config['cur_tag_close'] = '</a></span>';
        $config['num_tag_open'] = '<span class="page-item">';//숫자태그
        $config['num_tag_close'] = '</span>';
        $config['first_tag_open'] = '<span class="page-item">';//감싸는 태그
        $config['first_tag_close'] = '</span>';//감싸는 태그
        $config['last_tag_open'] = '<span class="page-item">';//감싸는 태그
        $config['last_tag_close'] = '</span>';//감싸는 태그
        $config['prev_tag_open'] = '<span class="page-item">';//감싸는 태그
        $config['prev_tag_close'] = '</span>';//감싸는 태그
        $config['next_tag_open'] = '<span class="page-item">';//감싸는 태그
        $config['next_tag_close'] = '</span>';//감싸는 태그
        $config['prev_link'] = '<';
        $config['next_link'] = '>';
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $this->pagination->initialize($config);//페이지네이션 초기화
        return $this->pagination->create_links();       
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