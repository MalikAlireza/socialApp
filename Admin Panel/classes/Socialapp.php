<?php

class Socialapp{

	private $_db,
			$_instance;

	public function __construct($mytube = null){

		$this->_db = DB::getInstance();
	}

	public function getPosts ($start,  $perPage){

		$sql = 'SELECT p.id , p.user_id , p.type , p.audience , p.description , p.content , p.isShared , p.shared_id , p.shared_post_id , p.creation, u.username, u.profilePhoto FROM posts p , users u WHERE p.user_id = u.id ORDER BY p.id DESC LIMIT ? , ?';

		$posts = $this->_db->rawQuery($sql, array($start,$perPage));

		$totalPosts =  $this->_db->rawQuery('SELECT count(id) as totalPosts from posts');
		return array(
			'posts' => $posts, 
			'totalPosts' => $totalPosts[0]['totalPosts']
		);
	}

	public function getPost ($id){

		$sql = 'SELECT p.id , p.user_id , p.type , p.audience , p.description , p.content , p.isShared , p.shared_id , p.shared_post_id , p.creation, u.username, u.profilePhoto FROM posts p , users u WHERE p.id = ? AND p.user_id = u.id ORDER BY p.id DESC LIMIT 1';
		
		$post = $this->_db->rawQuery($sql, array($id));
		
		if ($post) {
			return $post[0];
		}
		return false;

	}

	public function getUsers ($start,  $perPage){

		$sql = 'SELECT id, name, username, email, password, registration_id,
		description, api, relationship, gender, isVerified, location, profilePhoto, profileLink, created_At, isDisabled, disableReason FROM users ORDER BY id DESC LIMIT ? , ?';

		$users = $this->_db->rawQuery($sql, array($start,$perPage));

		$totalUsers =  $this->_db->rawQuery('SELECT count(id) as totalUsers from users');
		return array(
			'users' => $users, 
			'totalUsers' => $totalUsers[0]['totalUsers']
		);
	}

	public function getUser ($id){

		$sql = 'SELECT id, name, username, email, password, registration_id,
		description, api, relationship, gender, isVerified, location, profilePhoto, profileLink, created_At, isDisabled, disableReason FROM users WHERE id = ? ORDER BY id DESC LIMIT 1';

		$user = $this->_db->rawQuery($sql, array($id));
		
		if ($user) {
			return $user[0];
		}

		return false;
	}

	public function getReports ($start,  $perPage){

		/*$sql = 'SELECT r.id, r.action, r.postId as reported_post_id,	r.reason,	r.creation,  p.type reported_post_type, p.isShared as reported_post_isShared, p.shared_id as reported_post_shared_id, p.shared_post_id as reported_post_shared_post_id, p.creation as reported_post_creation, p.description as reported_post_description, p.content as reported_post_content, reporter.username as reporter_username, reporter.profilePhoto as reporter_profile_photo, publisher.username as publisher_username, publisher.profilePhoto as publisher_profilePhoto  FROM reports r , posts p, users reporter, users publisher WHERE r.postId = p.id AND reporter.id = r.report_by AND publisher.id = p.user_id ORDER BY r.id DESC LIMIT ? , ?';*/

		$sql = 'SELECT id, action, postId , report_by, 	reason,	creation, ( SELECT u.name FROM users u WHERE u.id = report_by ) as reporter FROM reports r ORDER BY id DESC LIMIT ? , ?';

		$reports = $this->_db->rawQuery($sql, array($start,$perPage));

		$totalReports =  $this->_db->rawQuery('SELECT count(id) as totalReports from reports');
		return array(
			'reports' => $reports, 
			'totalReports' => $totalReports[0]['totalReports']
		);
	}

	public function getMessages ($start,  $perPage){

		$sql = 'SELECT m.id, m.message, m.creation, m.isSent, sender.username as sender_username , sender.profilePhoto as sender_profilePhoto, receiver.username as receiver_username, receiver.profilePhoto as receiver_profilePhoto FROM messages m, users sender, users receiver WHERE receiver.id = m.receiver AND sender.id = m.sender  ORDER BY m.id DESC LIMIT ? , ?';

		$messages = $this->_db->rawQuery($sql, array($start,$perPage));

		$totalMessages =  $this->getTotal('messages');
		return array(
			'messages' => $messages, 
			'totalMessages' => $totalMessages
		);
	}
	
	public function getMessagesById($start,  $perPage, $id){

		$sql = 'SELECT m.id, m.message, m.creation, m.isSent, sender.username as sender_username , sender.profilePhoto as sender_profilePhoto, receiver.username as receiver_username, receiver.profilePhoto as receiver_profilePhoto FROM messages m, users sender, users receiver WHERE receiver.id = m.receiver AND sender.id = m.sender AND sender.id = ?  ORDER BY m.id DESC LIMIT ? , ?';

		$messages = $this->_db->rawQuery($sql, array($id, $start,$perPage));

		$totalMessages =  $this->_db->rawQuery('SELECT count(id) as total from messages WHERE sender = ? ', array($id));
		
		if ($messages) {
			
			return array(
				'messages' => $messages, 
				'totalMessages' => $totalMessages[0]['total']
				);
		}
		
		return false;
		
	
	}

	public function getTotal ($table, $field = NULL , $value = NULL ){
		if ( !is_null($field) and !is_null($value)) {
			$total = $this->_db->where($field, $value)->get($table);
			return count($total);
		}
		else{

			$total =  $this->_db->rawQuery("SELECT count(id) as total FROM $table");
			return $total[0]['total'];
		}

	}

	public function getTotalSuspendedAccounts (){
		$total =  $this->_db->rawQuery("SELECT count(id) as total FROM users WHERE isDisabled = 1 ");
		return $total[0]['total'];
	}
	public function find ($table, $column, $term){
		$term = "%$term%"; 
		$sql = "SELECT * FROM $table WHERE $column like  ? limit 0, 30 ";
		return  $this->_db->rawQuery($sql,array($term));
	}

}