<?php
class Board extends CI_Controller {
  
public function __construct() {
  parent::__construct();
  $this->load->database();
  $this->load->model('Board_model');
}

//게시글 목록
public function index() {   
  $data['id'] = $this->session->userdata('id'); 

  $this->search(); //분할 안할시 url에 index가 찍혀서 예쁘게 안나옴
}

//키워드 검색
public function search ($page = 1) { //url에 param값을 받을 수 있음\
  if(!is_numeric($page) || strlen($page) > 19) {
      echo "<script charset='utf-8'> alert( '잘못된 요청입니다!', 'required'); location.href='/board'; </script>";
  }
  $data['id'] = $this->session->userdata('id'); 
  $data['type'] = $this->input->get('type',TRUE); //pagination이 form이 아닌 location으로 데이터를 전송하기 때문에 get으로 받아야함
  $data['search_word'] = $this->input->get('search_word', TRUE);
  $per_page = 5;
  $start = ($page-1) * $per_page;
  $data['total_rows'] = $this->Board_model->board_list_count($data['type'], $data['search_word'])->row();
  $data['pagination'] = $this->common_pagination('/board/search', $data['total_rows']->totalcount, $per_page);
  $data['board'] = $this->Board_model->get_board_list($data['type'], $data['search_word'], $start, $per_page)->result_array();

  $this->load->view('board/index', $data);
}

//게시글 상세보기  & 리뷰 출력
public function view($bno = null) {   
  if( strlen(trim($bno)) == 0 || !is_numeric($bno) || $bno == NULL) { //trim으로 공백을 제거 -> strlen으로 문자열 길이 구함. is_numeric -> 숫자인지 아닌지 확인
    show_404();
  }
  $data['id'] = $this->session->userdata('id');
  $data['board_item'] = $this->Board_model->board_view($bno)->row_array();

  if($data['board_item'] == NULL) {
      echo "<script>alert('존재하지 않는 게시글입니다'); location.href='/board'</script>";
  }


  $files = explode(',', $data['board_item']['files'], 3);
  $files1 = '';
  $files2 = '';
  $files3 = '';

  if(!empty($files[0])){$files1 = $files[0];}
  if(!empty($files[1])){$files2 = $files[1];}
  if(!empty($files[2])){$files3 = $files[2];}

  $data['files1'] = $files1;
  $data['files2'] = $files2;
  $data['files3'] = $files3;
  $data['csrf'] = array(
      'name' => $this->security->get_csrf_token_name(),
      'hash' => $this->security->get_csrf_hash()
  );
  $data['reply_list'] = $this->Board_model->reply_list($bno)->result_array();
  
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
public function update($bno = NULL) {
  
    if( strlen(trim($bno)) == 0 || !is_numeric($bno) || $bno == NULL) { //trim으로 공백을 제거 -> strlen으로 문자열 길이 구함. is_numeric -> 숫자인지 아닌지 확인
      show_404();
    }
  
    $this->form_validation->set_rules('title', '게시판 제목을 입력해주세요', 'required');
    $this->form_validation->set_rules('content', '게시판 내용을 입력해주세요', 'required');

    $title = $this->input->post('title', TRUE); //TRUE -> SQL injection 방어. 필수임!!
    $content =  $this->input->post('content', TRUE);

    $data['id'] = $this->session->userdata('id');

    if ( $this->form_validation->run() ) {
        $this->Board_model->board_update($title, $content, $bno);
        $current = current_url();
        redirect('board/view/'.$bno);
    }else{
        redirect($_SERVER['HTTP_REFERER']);
    }

}

//게시글 등록
public function create()
{
    $data['id'] = $this->session->userdata('id');
    $data['title'] = '게시판 등록';
    
    $this->load->view('board/create', $data);
  
    
}


//게시글 등록
public function insert(){
      #$this->load->helper('form');
      $this->load->library('upload');
      $this->form_validation->set_rules('title', 'Title을 입력해주세요', 'required');
      $this->form_validation->set_rules('content', 'text를 입력해주세요', 'required');

      $data['id'] = $this->session->userdata('id');
      $writer = $data['id'];

      if($this->form_validation->run() == FALSE){
        $this->load->view('board/create', $data);
      }else{
        if($_FILES["files"]["name"] != ''){
          $config["upload_path"] = './image/';
          $config['allowed_types'] = 'gif|jpg|jpeg|png';

          
          for($count = 0; $count < count($_FILES["files"]["name"]); $count++){
              $_FILES["file"]["name"] = $_FILES["files"]["name"][$count];
              $_FILES["file"]["type"] = $_FILES["files"]["type"][$count];
              $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$count];
              $_FILES["file"]["error"] = $_FILES["files"]["error"][$count];
              $_FILES["file"]["size"] = $_FILES["files"]["size"][$count];
              $this->upload->initialize($config);
              $this->upload->do_upload('file');
              $img[] = $this->upload->data();
          }
      }
      
      $data['title'] = '게시판 등록';
      $title = $this->input->post('title', TRUE);
      $content = $this->input->post('content', TRUE);
      
      $img1 = '';
      $img2 = '';
      $img3 = '';

      if(!empty($img[0]['file_name'])){
        $img1 = $img[0]['file_name'];
      }
      if(!empty($img[1]['file_name'])){
        $img2 = ',';
        $img2 .= $img[1]['file_name'];
      }
      if(!empty($img[2]['file_name'])){
        $img3 = ',';
        $img3 .= $img[2]['file_name'];
      }      

      $data_arr = array(
          'title' => $title,
          'content' => $content,
          'writer' => $writer,
          'files' => $img1.$img2.$img3
      );

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

//파일 다운로드
public function files_down(){
    $this->load->helper('download');
    force_download($name, $data);
}

//리뷰 등록
public function review_insert($bno){

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
    }else{
        $back = $_SERVER['HTTP_REFERER'];
        echo "<script charset='utf-8'> alert( '리뷰를 입력해주세요!!!', 'required'); location.href='".$back."'; </script>";
    }
}

//리뷰 수정
public function review_update($rno, $bno){

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