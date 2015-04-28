<?php

/**
 * Description of News for Ajax Call
 *
 * @author marwan
 * @email amazzura.biz@gmail.com
 */
class News extends MY_Ajax {
    
    function __construct() {
        parent::__construct();
    }
    
    function last(){
        $this->load->model('article/article_m');
        $result = array('status'=>0,'message'=>'', 'items'=>array());
        
        $lastPage = $this->input->post('last_page') ? $this->input->post('last_page'): 1;
        $limit = $this->input->post('limit') ? $this->input->post('limit'):10;
        
        $newPage = $lastPage+1;
        $offset = ($newPage-1) * $limit;
        
        $fields = 'title,url_title,image_url,synopsis,date,created_by';
        $where = array('published'=>ARTICLE_PUBLISHED);
        $this->db->order_by('date desc');
        $query_result = $this->article_m->get_offset($fields,$where,$offset,$limit);
        
        if ($query_result){
            $result['status'] = 1;
            $result['last_page'] = $newPage;
            foreach ($query_result as $item){
                //$item->created_by_name = $this->user_m->get_value('full_name',array('id'=>$item->created_by));
                if ($item->image_url){
                    $item->image_url = get_image_thumb($item->image_url, IMAGE_THUMB_SQUARE);
                }
                $item->data_href = site_url('detail/'.$item->url_title);
                $result ['items'] [] = $item;
            }
        }else{
            $result['last_page'] = $lastPage;
        }
        
        $this->send_output($result);
    }
}
    

/*
 * file location: ./application/controllers/ajax/news.php
 */