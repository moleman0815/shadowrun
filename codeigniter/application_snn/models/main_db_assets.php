<?php

/**
 * App_Data_Model
 * 
 * @package App
 */

class Main_db_assets extends CI_Model
{
	
	var $mydb;


  function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
    
    function getNews($page) {
        $limit = '10';
        $page = ($page) ? $page : '0';        
        $this->db->select('news.*, news.id as nid, category.*');
        $this->db->from('news');
        $this->db->join('category', 'news.category = category.id');
        $this->db->where('news.deleted', '0');
        $this->db->order_by('news.date', 'DESC');
        $this->db->limit($limit, $page);        

        $query = $this->db->get();
        return $query->result_array();        
    }

    function countNews() {
        $this->db->where('deleted', '0');
        return $this->db->count_all('news');
    }  

    function countAds () {
        return $this->db->count_all('banner');
    }  

    function getAllAds () {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('banner');
        return $query->result_array();
    }

    function getAllCategories () {
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get('category');
        return $query->result_array();        
    }

    function getReceiver () {
        $this->db->select('nickname, id');
        $this->db->from('login');
        $this->db->where('rank !=', '0');
        $this->db->order_by("nickname", "ASC"); 
        $query = $this->db->get();
        return $query->result_array();
    }

    function countMessages () {
        $this->db->where('deleted', '0');
        $this->db->where('send_to', $this->session->userdata('id'));
        $this->db->from('messages');
        return  $this->db->count_all_results();        
    }

    function getMessages($page) {
		$limit = '10';
		$page = ($page) ? $page : '0';
		$data = array();
		$avatars = array();
		$tmpAvatar = array();
		
	
        $this->db->select('messages.*');
        $this->db->from('messages');       
        $this->db->where('send_to', $this->session->userdata('id'));
        $this->db->where('send_to', $this->session->userdata('id'));
        #$this->db->or_where('send_to', 2);
        $this->db->or_where('send_from', $this->session->userdata('id'));
        $this->db->where('deleted', '0');
        $this->db->order_by("date", "DESC");
		$this->db->limit($limit, $page);
        $data['messages'] = $this->db->get()->result_array();
        
        foreach ($data['messages'] as $value) {
        	if (!in_array($value['send_to'], $avatars) && $value['send_to'] != $this->session->userdata('id')) {
        		array_push($avatars, $value['send_to']);
        	}
        }
        
        foreach ($avatars as $a) {
        	$this->db->select('id, nickname, avatar');
        	$this->db->from('login');
        	$this->db->where('id', $a);
        	$data['avatar'][$a] = $this->db->get()->result_array();        	
        }
        
        $this->db->select('id, nickname, avatar');
        $this->db->from('login');
        $this->db->where('id', $this->session->userdata('id'));
        $data['avatar'][$this->session->userdata('id')] = $this->db->get()->result_array();
        
        
        
        
        
        
        #$this->db->select('nickname, avatar');
        

        return $data;
    }

    function getColumnMessages () {
        $this->db->select('messages.*, login.nickname');
        $this->db->from('messages');
        $this->db->join('login', 'login.id = messages.send_from');
        $this->db->where('send_to', $this->session->userdata('id'));
        $this->db->where('deleted', '0');
        $this->db->order_by("date", "DESC"); 
        $this->db->limit('5');
        $query = $this->db->get();
        return $query->result_array();        
    }

    function replyMessage($id) {
        $this->db->select('messages.*, login.nickname');
        $this->db->from('messages');
        $this->db->join('login', 'messages.send_from = login.id');
        $this->db->where('messages.id', $id);
        $query = $this->db->get();

        return $query->result_array();        
    }

    function getShoutbox () {
        $this->db->select('shoutbox.*, login.nickname');
        $this->db->from('shoutbox');
        $this->db->join('login', 'login.id = shoutbox.login_id');
        $this->db->where('shoutbox.deleted', '0');
        $this->db->order_by("sb_time", "desc"); 
        $this->db->limit('7');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getShoutboxFull () {
    	$this->db->select('shoutbox.*, login.nickname');
    	$this->db->from('shoutbox');
    	$this->db->join('login', 'login.id = shoutbox.login_id');
    	$this->db->where('shoutbox.deleted', '0');
    	$this->db->order_by("sb_time", "desc");
    	$query = $this->db->get();
    	return $query->result_array();
    }

	function sendShoutbox() {
		$post = $this->input->post(NULL, TRUE);
		$data = array(
					'login_id' => $post['userid'],
					'sb_text' => $post['sb_text'],
					'sb_time' => time(),
			);
		if($this->db->insert('shoutbox', $data)) {
			return true;
		} else {
			return false;
		}
	}

    function deleteShoutbox() {
        $news = array (
                'deleted' => '1',
            );  
        return ($this->db->update('shoutbox', $news, array('sb_id' => $this->input->post('id')))) ? true : false;        
    }

	
	function sendMessage() {
		$post = $this->input->post(NULL, TRUE);
		
		$data = array();
		if (isset($post['reply']) && $post['reply']  == '1') {
			$data = array(
				'title' => $post['replytitle'],
				'msg_text' => $post['reply_text'],
				'send_to' =>  $post['receiverid'],
				'send_from' =>  $post['userid'],
				'parent' => $post['senderid'],
				'date' => time(),			
			);
            if($this->db->insert('messages', $data)) {
                $data = array('child' => $this->db->insert_id());       
                $this->db->where('id', $post['senderid']);
                return ($this->db->update('messages', $data)) ? true : false;
            } else {
                return false;
            }
		} else {
			$senderid = (!empty($post['senderid'])) ? $post['senderid'] : '0';
			$data = array(
				'title' => $post['title'],
				'msg_text' => $post['msg_text'],
				'send_to' =>  $post['receiver'],
				'send_from' =>  $post['userid'],
				'parent' => $senderid,
				'date' => time(),			
			);		
        return ($this->db->insert('messages', $data)) ? true : false;            
		}

	}
	
	function deleteMessage($id) {
		$data = array('deleted' => '1');
		$this->db->where('id', $id);
		$this->db->update('messages', $data);
	}

    function getAds () {
        $this->db->select('*');
        $this->db->from('banner');
        $this->db->order_by("id", "random"); 
        $this->db->limit('1');
        $query = $this->db->get();
        return $query->result_array();
    }


    function getAvatar() {
        $this->db->select('avatar');
        $query = $this->db->get_where('login', array('id' => $this->session->userdata('id')));
        return $query->result_array();        
    }

    function getFeedback () {
        $this->db->order_by('time', 'DESC');
        return $this->db->get('feedback')->result_array();
    }   

    function sendFeedback () {
        $data = array(
            'autor' => $this->input->post('autor'),
            'title' => $this->input->post('title'),
            'bereich' => $this->input->post('bereich'),
            'feedback' => $this->input->post('feedback'),
            'type' => $this->input->post('type'),            
            'time' => time(),        
            'status' => '0',
            'gelesen' => '0',
            );
        return ($this->db->insert('feedback', $data)) ? true : false;
    } 

    function changeFeedbackStatus () {
        $id = $this->input->post('fid');
        $data = array('status' => '1');
        $this->db->where('fid', $id);
        return ($this->db->update('feedback', $data)) ? true : false;
    }
    
}

?>    