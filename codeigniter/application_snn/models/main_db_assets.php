<?php

/**
 * App_Data_Model
 * 
 * @package App
 */

class Main_db_assets extends CI_Model
{
	
	var $mydb;
	var $settings;


  function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('add_functions');
        $this->settings = $this->add_functions->readSettings();
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

        $query = $this->db->get()->result_array();
        for ($x=0; $x<count($query);$x++) {
        	$this->db->select('comments.*, login.nickname');
        	$this->db->from('comments');
        	$this->db->join('login', 'login.id = comments.uid');        	
        	$this->db->where('nid', $query[$x]['nid']);
        	$this->db->where('deleted', '0');
        	$this->db->order_by('date', 'DESC');
        	$query[$x]['comments'] = $this->db->get()->result_array();
        }
#_debugDie($query);
        return $query;        
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
        $this->db->select('nickname, id, rank');
        $this->db->from('login');
        #$this->db->where('rank !=', '0');
        $this->db->order_by("nickname", "ASC"); 
        $query = $this->db->get();
        return $query->result_array();
    }


    function countMessages () {
    	$sendto = "";
    	if ($this->session->userdata('rank') < 1) {
    		$send = $this->db->get_where('login', array('rank' => 4))->result_array();
    		$sendto = $this->db->or_where('send_to', $this->session->userdata('id'));
    		foreach ($send as $q) {
    			$sendto .= $this->db->or_where('send_to', $q['id']);
    		}
    	} else {
    		$sendto = $this->db->where('send_to', $this->session->userdata('id'));
   	}
    	
        $this->db->where('deleted', '0');
        #$this->db->where('send_to', $this->session->userdata('id'));
        $sendto;
        if ($this->settings[0]['show_own_messages'] == 1) {
        	$this->db->or_where('send_from', $this->session->userdata('id'));
        }
        $this->db->from('messages');
        $query = $this->db->get()->result_array();

        for($i=0; $i<count($query);$i++) {
        	if ($query[$i]['send_from'] == $this->session->userdata('id') && preg_match("/freundschaftsanfrage/i", $query[$i]["title"])) {
        		unset($query[$i]);
        	}
        }
        
        return count($query);        
    }

    function countNewMessages () {
    	$this->db->where('gelesen', '0');
    	$this->db->where('deleted', '0');
    	$this->db->where('send_to', $this->session->userdata('id'));
    	if ($this->session->userdata('rank') < 1) {
    			$this->db->where('send_to', '4');
    	}
    	
    	$this->db->from('messages');
    	return  $this->db->count_all_results();
    }
    
    function updateNewMessage () {
    	$news = array (
    			'gelesen' => '1',
    	);
    	return ($this->db->update('messages', $news, array('id' => $this->input->post("id")))) ? true : false;	
    }
    
    function getMessages($page) {
		$limit = '10';
		$page = ($page) ? $page : '0';
		$data = array();
		$avatars = array();
		$tmpAvatar = array();

		$sendto = "";
		if ($this->session->userdata('rank') < 1) {
			$send = $this->db->get_where('login', array('rank' => 1, 'rank' => '4'))->result_array();
			#_debugDie($send);
				$sendto = $this->db->or_where('send_to', $this->session->userdata('id'));
			foreach ($send as $q) {				
				$sendto .= $this->db->or_where('send_to', $q['id']);
			}
		} else {
			$sendto = $this->db->where('send_to', $this->session->userdata('id'));
		}
		
        $this->db->select('messages.*');
        $this->db->from('messages');
        $sendto;
        //$this->db->where('send_to', $this->session->userdata('id'));
        if ($this->settings[0]['show_own_messages'] == 1) {
        	$this->db->or_where('send_from', $this->session->userdata('id'));
        }
        $this->db->where('deleted', '0');
        $this->db->order_by("date", "DESC");
		$this->db->limit($limit, $page);
        $data['messages'] = $this->db->get()->result_array();
        
        #_debugDie($data);
        
        for($i=0; $i<count($data['messages']);$i++) {
        	if ($data['messages'][$i]['send_from'] == $this->session->userdata('id') && preg_match("/freundschaftsanfrage/i", $data['messages'][$i]["title"])) {
        		unset($data['messages'][$i]);
        	}
        }        
        
        foreach ($data['messages'] as $value) {
        	if (!in_array($value['send_from'], $avatars) && $value['send_from'] != $this->session->userdata('id')) {
        		array_push($avatars, $value['send_from']);
        	}
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
       # _debugDie($data);
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
        $data['shoutbox'] = $this->db->get()->result_array();
        
        $data['receiver'] = $this->getReceiver();
        return $data;
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
		if ($post['userid'] == '') {
			$userid = $this->session->userdata('id');
		} else {
			$userid = $post['userid'];
		}
		$data = array(
					'login_id' => $userid,
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
		if ($post['senderid'] == '') {
			$senderid = $this->session->userdata('id');
		} else {
			$senderid = $post['senderid'];
		}
		$data = array();
		if (isset($post['reply']) && $post['reply']  == '1') {
			$data = array(
				'title' => $post['replytitle'],
				'msg_text' => $post['reply_text'],
				'send_to' =>  $post['receiverid'],
				'send_from' =>  $senderid,
				'parent' => $post['senderid'],
				'date' => time(),			
			);
            if($this->db->insert('messages', $data)) {
                $data = array('child' => $this->db->insert_id());       
                $this->db->where('id', $senderid);
                return ($this->db->update('messages', $data)) ? true : false;
            } else {
                return false;
            }
		} else {
			
			$senderid = (!empty($post['senderid'])) ? $post['senderid'] : '0';
			if (count($post['receiver']) == 1) {
				$data = array(
					'title' => $post['title'],
					'msg_text' => $post['msg_text'],
					'send_to' =>  $post['receiver'][0],
					'send_from' =>  $post['userid'],
					'parent' => $senderid,
					'date' => time(),			
				);		
	        	return ($this->db->insert('messages', $data)) ? true : false;
			} else {
				$counter = 0;
				foreach ($post['receiver'] as $r) {
					$data = array(
							'title' => $post['title'],
							'msg_text' => $post['msg_text'],
							'send_to' =>  $r,
							'send_from' =>  $post['userid'],
							'parent' => $senderid,
							'date' => time(),
					);
					if ($this->db->insert('messages', $data)) {
						$counter++;
					}
				}
				return ($counter == count($post['receiver'])) ? true : false;
			}
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
    	$where = array('deleted' => '0');
    	$this->db->where('type != "feature2"');
        $this->db->order_by('time', 'DESC');
        return $this->db->get_where('feedback', $where)->result_array();
    }   

    function sendFeedback () {
        $data = array(
            'autor' => $this->input->post('autor'),
            'title' => $this->input->post('title'),
            'bereich' => $this->input->post('bereich'),
            'feedback' => $this->input->post('feedback'),
            'type' => $this->input->post('type'),            
            'time' => time(),  
        	'uid' => $this->input->post('uid'),
            'status' => '0',
            'gelesen' => '0',
            );
        return ($this->db->insert('feedback', $data)) ? true : false;
    } 
    
    function editFeedback () {
    	#_debugDie($this->input->post());
    	$data = array(
    			'autor' => $this->input->post('autor'),
    			'title' => $this->input->post('title')." (edited)",
    			'bereich' => $this->input->post('bereich'),
    			'feedback' => $this->input->post('feedback'),
    			'type' => $this->input->post('type'),
    			'time' => time(),
    			'uid' => $this->input->post('uid'),
    			'status' => $this->input->post('status'),
    			'gelesen' => '0',
    	);
    	$this->db->where('fid', $this->input->post('fid'));
    	
    	return ($this->db->update('feedback', $data)) ? true : false;
    }
    
    
    function sendFeedbackAnswer () {
    	#_debugDie($this->input->post());
    	$data = array(
    			'autor' => 'Alex',
    			'title' => $this->input->post('feedbacktitle'),
    			'bereich' => '1',
    			'feedback' => $this->input->post('feedbacktext'),
    			'type' => 'answer',
    			'time' => time(),
    			'status' => '0',
    			'gelesen' => '0',
    			'parent' => $this->input->post('feedbackParentid'),
    	);
    	if ($this->db->insert('feedback', $data)) {
    		$lastid = $this->db->insert_id();
    		$query = $this->db->get_where('feedback', array('fid' => $this->input->post('feedbackParentid')))->result_array();

    		
    		$child = $query[0]['child'].";".$lastid;

    		$data = array('child' => $child);
    		$this->db->where('fid', $this->input->post('feedbackParentid'));
    		
    		return ($this->db->update('feedback', $data)) ? true : false;
    	} else {
    		return false;
    	}
    	
    	
    }

    function changeFeedbackStatus () {
        $id = $this->input->post('fid');
        $data = array('status' => '1');
        $this->db->where('fid', $id);
        return ($this->db->update('feedback', $data)) ? true : false;
    }
    
    function deleteFeedback($id) {
    	$data = array('deleted' => '1');
    	$this->db->where('fid', $id);
    	$this->db->update('feedback', $data);
    }
    
    function receiveFeedback () {
    	return $this->db->get_where('feedback', array('fid' => $this->input->post('fid')))->result_array();
    }
    
    function sendFeatures () {	
    	$data = array(
    			'autor' => 'Alex',
    			'title' => '',
    			'bereich' => '',
    			'feedback' => $this->input->post('feature'),
    			'type' => 'feature2',
    			'time' => time(),
    			'uid' => '0',
    			'status' => '0',
    			'gelesen' => '0',
    	);
    	return ($this->db->insert('feedback', $data)) ? true : false;
    }
    
    function getFeatures () {
    	$where = array('deleted' => '0');
    	$this->db->where('type', "feature2");
    	$this->db->order_by('time', 'DESC');
    	return $this->db->get_where('feedback', $where)->result_array();
    }
    
    function sendNewComment () {
    	$data = array(
    			'comment' => $this->input->post('comment'),
    			'nid' => $this->input->post('newsid'),
    			'uid' => $this->input->post('userid'),
    			'deleted' => '0',
    			'date' => time(),
    	);
    	return ($this->db->insert('comments', $data)) ? true : false;
    }
    
    function deleteComment () {
    	$data = array('deleted' => '1');
    	$this->db->where('cid', $this->input->post('cid'));
    	return ($this->db->update('comments', $data)) ? true : false;
    }
    
    function getSystemNews () {
    	return $this->db->get('notes')->result_array();
    }
    
    function toggleSystemNews () {
    	$this->db->where('id', $this->input->post('id'));
    	$query = $this->db->get_where('notes', $data)->result_array();
    	
    	$data = array('online' => '0');
    	$this->db->update('notes', $data);
    	$newOnline = ($query[0]['online'] == 1) ? 0 : 1;
    	
    	$data = array('online' => $newOnline);
    	$this->db->where('id', $this->input->post('id'));
    	$this->db->update('notes', $data);
    	
    }
}

?>    