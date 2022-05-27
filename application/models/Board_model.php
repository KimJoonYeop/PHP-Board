<?php 
class Board_model extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }

    public function get_board_list($title_word, $writer_word)
    {
        
        // $this->db->select('bno', 'title', 'content', 'writer', 'regdate');
         // $query = $this->db->get('board');
        $sql ="SELECT bno, title, content, writer, regdate FROM board where delete_check = 'N' and title like '%{$title_word}%' and writer like '%{$writer_word}%' order by bno desc limit 0,5 ";
        return $this->db->query($sql);
     
    }


    public function list_count()
    {
        
        // $this->db->select('bno', 'title', 'content', 'writer', 'regdate');
        //$sql ="SELECT count(*) count FROM board";
        return $this->db->count_all('board');
        // $query = $this->db->get('board');
        // return $query->result_array();
     
    }

    public function get($bno){
        $query = $this->db->get_where('board', array('bno' => $bno));
        return $query;
    }

    public function board_insert($data_arr)   
    {
        $sql = "INSERT INTO board(title, content, writer) 
                values('".$data_arr['title']."', 
                '".$data_arr['content']."', 
                '".$data_arr['writer']."')";

        $this->db->query($sql);
    }

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

    public function board_delete($bno){
        $sql = "UPDATE board set delete_check ='Y' WHERE bno = '".$bno."'";
        $this->db->query($sql);
    }
    
    public function admin_delete($bno){
        $sql = "DELETE FROM board WHERE bno = '".$bno."'";
        $this->db->query($sql);
    }

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
    
    public function reply_insert($data_arr){
        $sql ="INSERT INTO reply(bno, reply, id) 
        VALUES('".$data_arr['bno']."', 
        '".$data_arr['reply']."', 
        '".$data_arr['id']."')";

        $this->db->query($sql);
    }

    public function reply_update($reply, $rno){
        $sql ="UPDATE reply SET reply = '{$reply}', updateDate=now() WHERE rno = '{$rno}'";
        return $this->db->query($sql);
    }

    public function reply_delete($rno){
        $sql ="DELETE FROM reply WHERE rno = '{$rno}'";
        $this->db->query($sql);
    }
}