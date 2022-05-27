<?php 
class Main_model extends CI_Model {
    
    public function __construct()
    {
    }


    function can_login($id, $password){
        // $this->db->where('id', $id);
        // $this->db->where('password', $password);
        // $query = $this->db->get('user');
        $sql = "SELECT id, password from user WHERE id = '".$id."' AND password = '".$password."'";
        $query = $this->db->query($sql);
        return $query;
        // // if( $query->num_rows() > 0)
        // // {
        // //     return true;
        // // }
        // // else
        // // {
        // //     return false;
        // }
    }
}