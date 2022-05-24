<?php 
class News_model extends CI_Model {
    
    public function __construct()
    {
    }

    public function get_news()
    {
      
        $query = $this->db->get('news');
        return $query->result_array();
     
    }

    public function get($id){
        $query = $this->db->get_where('news', array('id' => $id));
        return $query->row_array();
    }
}