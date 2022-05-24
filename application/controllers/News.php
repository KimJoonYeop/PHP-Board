<?php
class News extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('News_model');
    }

    public function index()
    {
        $data['news'] = $this->News_model->get_news();
        $data['title'] = 'News archive';
        $data['prices'] = ['apple'=>300,'lemon'=>200];
           
        $this->load->view('news/index', $data);
    }

    public function view($id = NULL)
    {
        $data['news_item'] = $this->News_model->get($id);
        $data['news'] = $this->News_model->get_news();

        $this->load->view('news/view' , $data);
    }
}