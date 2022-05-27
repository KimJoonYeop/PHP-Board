<?php 
class Board_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }

    //게시글 목록
    public function get_board_list($type, $search_word, $start, $per_page)
    {
        
        // $this->db->select('bno', 'title', 'content', 'writer', 'regdate');
         // $query = $this->db->get('board');
        $sql ="SELECT bno, title, content, writer, regdate 
                FROM board 
                WHERE delete_check = 'N'";
                
        if(!empty($search_word)){
            $sql .= " AND {$type} like '%{$search_word}%'";
        }

        $sql .= " ORDER BY bno DESC
                LIMIT ".$start.", ".$per_page."";

        return $this->db->query($sql);
     
    }

  //게시글 Total count
  public function board_list_count($type, $search_word)
  {
      // , CAST(COUNT(bno) AS DOUBLE) /'".$per_page."' AS TOTALPAGE
      
    $sql = "SELECT count(bno) TOTALCOUNT 
        FROM board
        WHERE delete_check = 'N'";
    
    if(!empty($search_word)){
        $sql .= " AND {$type} like '%{$search_word}%'";
    }
            
    return $this->db->query($sql)->row()->TOTALCOUNT;

  }

    //게시글 상세보기
    public function get($bno){
        $query = $this->db->get_where('board', array('bno' => $bno));
        return $query;
    }

    //게시글 등록
    public function board_insert($data_arr)   
    {
        $sql = "INSERT INTO board(title, content, writer) 
                values('".$data_arr['title']."', 
                '".$data_arr['content']."', 
                '".$data_arr['writer']."')";

        $this->db->query($sql);
    }

    //게시글 수정
    public function board_update($title, $content, $bno){   // '".$data_arr['title']."' -> '김민우'
        //$today = date("Y-m-d h:i:s");
        $sql = "UPDATE board
                set title = ?, 
                    content = ?,
                    updatedate = now()
                WHERE bno = ?";

        $this->db->query(
            $sql,
            array($title, $content, $bno)
        );
        // $this->db->where('bno', $bno);
        // return $this->db->update('board', $data);
    }

    //게시글 삭제(유저)
    public function board_delete($bno){
        $sql = "UPDATE board set delete_check ='Y' WHERE bno = '".$bno."'";
        $this->db->query($sql);
    }
    
    //게시글 삭제(관리자)
    public function admin_delete($bno){
        $sql = "DELETE FROM board WHERE bno = '".$bno."'";
        $this->db->query($sql);
    }

    //리뷰 목록
    public function reply_list($bno){
        // SELECT r.rno AS 'test',
        $sql = "SELECT r.rno,
                    r.bno,
                    r.reply,
                    r.id,
                    r.replyDate,
                    r.updateDate
                FROM reply r
                WHERE r.bno = '{$bno}'
                ORDER BY r.rno DESC";

        return $this->db->query($sql);
    }
    
    //리뷰 등록
    public function reply_insert($data_arr){
        $sql ="INSERT INTO reply(bno, reply, id) 
        VALUES('".$data_arr['bno']."', 
        '".$data_arr['reply']."', 
        '".$data_arr['id']."')";

        $this->db->query($sql);
    }

    //리뷰 수정
    public function reply_update($reply, $rno){
        $sql ="UPDATE reply SET reply = '{$reply}', updateDate=now() WHERE rno = '{$rno}'";
        return $this->db->query($sql);
    }

    //리뷰 삭제
    public function reply_delete($rno){
        $sql ="DELETE FROM reply WHERE rno = '{$rno}'";
        $this->db->query($sql);
    }
}