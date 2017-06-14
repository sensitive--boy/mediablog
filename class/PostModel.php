
<?php
/************
* folder: tiposts/class
* mvc multipost project
* PostModel.php -> class PostModel
* manages posts
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class PostModel {
	private $sptable;
	private $bptable;
	private $aptable;
	private $vptable;
	private $ctable;
	private $utable;
	private $sortopt = "contribution_date DESC";
	private $wrapper;
	private $posttypes = array('simple', 'book', 'audio', 'video');
	
	public function __construct() {
		$this->sptable  = 'simpleposts';
		$this->bptable  = 'bookposts';
		$this->aptable  = 'audioposts';
		$this->vptable  = 'videoposts';
		$this->ctable  = 'contributions';
		$this->utable = 'tiusers';
		$this->wrapper = Wrapper::getInstance();
	}
	
	public function createPost($type) {
		$post = null;
		switch($type) {
			case 'simple':
				$post = new SimplePost(0, "", "");
			break;
			case 'book':
				$post = new BookPost(0, "", "");
			break;
			case 'audio':
				$post = new AudioPost(0, "", "");
			break;
			case 'video':
				$post = new VideoPost(0, "", "");
			break;
		}
		return $post;
	}
	
	public function saveContribution($type, $post_id, $blog_id, $user_id) {
		$columnvals = array('contributor_id' => $user_id,'blog_id' => $blog_id, $type.'post_id' => $post_id );
		$result = $this->wrapper->insert($this->ctable, $columnvals);
		return $result;
	}
	// returns id of inserted post
	public function savePost($request) {
		$post = $this->createPost($request['type']);
		$columns = $post->getColumnnames();
		$columnvals = array();
		
		foreach($columns as $n){
			$columnvals[$n] = $request[$n];
		}
		unset($columnvals['id']);
		if($request['published'] == 'on') {
			$columnvals['published'] = true;
		} else {
			$columnvals['published'] = false;
		}
		$plangs = array();
		
		$table = $this->getTableName($request['type']);
		$result = $this->wrapper->insert($table, $columnvals);
		echo "<br> Result ist: $result";
		if($result) {
			foreach($request['p-language'] as $l){
				echo "<br>".$request['type']." Post: ".$result." spricht Sprache: $l ";
				$this->wrapper->insertWithoutId('posts_languages', array($request['type'].'post_id'=>$result, 'language_id' => $l));
			}
		}
		return $result;
	}
	
	// to do: type sensitivity
	public function getPosts() {
		$postlist = [];
		$resultset = $this->wrapper->selectAll($this->table);
		if(!empty($resultset)) {
		foreach($resultset as $b){
			$postlist[] = new Post($b['id'], $b['owned_by'], $b['title'], $b['description']);
		}
	} else {
		$postlist[] = 'albert und albert';
		$postlist[] = 'kaus reist aus';
		$postlist[] = 'peter macht meter';
	}
		return $postlist;
	}
	
	public function getPost($type, $id) {
		$functionToCall = "get";
		$functionToCall .= ucfirst($type);
		$functionToCall .= "Post";
		$post = $this->{$functionToCall}($id);
		return $post;
	}
	
	public function updatePost($request) {
		$post = $this->getPost($request['type'], $request['id']);
		$columnvals = array();
		$conditions = array();
		$columns = $post->getColumnnames();
		foreach($columns as $n){
			$columnvals[$n] = $request[$n];
		}
		$conditions['id'] = $request['id'];
		$table = $this->getTableName($request['type']);
		$result = $this->wrapper->updateWhere($table, $columnvals, $conditions);
		return $result;
	}
	
	public function deletePost($type, $id) {
		echo 'postModel: delete';
		try{
			$this->wrapper->beginTransaction();
			$this->wrapper->deleteWhere('posts_languages', array($type.'post_id' => $id));
			echo 'deleted posts_languages';
			$this->wrapper->deleteWhere('contributions', array($type.'post_id' => $id));
			echo 'deleted contribution';
			$this->wrapper->deleteWhere($type.'posts', array('id' => $id));
			echo 'deleted simplepost';
			$this->wrapper->commit();
			return true;
		}catch (Exception $e) {
			$this->wrapper->rollback();
			echo "Failed: ".$e->getMessage();
			return false;
		}
		
	}
	public function getContribution($id) {
		$result = $this->wrapper->selectWhere($this->ctable, null, array('id'=>$id))->fetch();
		if($result['simplepost_id']) { $post_type = 'simple'; $post_id = $result['simplepost_id'];}
		elseif($result['bookpost_id']) { $post_type = 'book'; $post_id = $result['bookpost_id'];}
		elseif($result['audiopost_id']) { $post_type = 'audio'; $post_id = $result['audiopost_id'];}
		elseif($result['videopost_id'] ) { $post_type = 'video'; $post_id = $result['videopost_id'];}
		else { return null;}
		$contribution = new Contribution($result['id'], $result['contributor_id'], $result['blog_id'], $post_type, $post_id);
		$contribution->setPost($this->getPost($post_type, $post_id));
		return $contribution;
	}
	
	public function getContributionsByBlog($blog_id) {
		$clist = [];
		// get all contributions in blog with id $blog_id
		$conditions['blog_id'] = $blog_id;
		$resultset = $this->wrapper->selectWhere($this->ctable, $this->sortopt, $conditions);
		// get post object related to each contribution
		foreach($resultset as $c){
			// find out type of post
			foreach($this->posttypes as $type){
				if(!empty($c[$type.'post_id'])) { 
					$classname = ucfirst($type)."Post";
					$functionToCall = "get";
					$functionToCall .= $classname;
					// call function to build PostObject depending on type
					// function call should be something like getSimplePost($c['simplepost_id'])
					$post = $this->{$functionToCall}($c[$type.'post_id']); 
					}
			}
			$contribution = new Contribution($c['id'], $c['contributor_id'], $c['blog_id'], $post->getPostType(), $post->getId());
			// attach post object to contribution
			$contribution->setPost($post);
			// get contributor name and save it in contribution
			$user = $this->wrapper->selectWhere('tiusers', null, array('id'=>$c['contributor_id']))->fetch();
			$contribution->setUsername($user['username']);
			// add contribution to list
			$clist[] = $contribution;
		}
		return $clist;
	}
	
	public function getPostsByBlogId($blog_id) {
		$postlist = [];
		$post;
		$conditions = array();
		// to do: sanitize input
		$conditions['blog_id'] = $blog_id;
		$resultset = $this->wrapper->selectWhere($this->ctable, $this->sortopt, $conditions);
		// for each contribution in this blog
		// check for post type and get post object accordingly
		foreach($resultset as $p){
			$contributor = $p['contributor_id'];
			$conds['id'] = $contributor;
			$user = $this->wrapper->selectWhere($this->utable, null, $conds)->fetch();
			$contributor_name = $user['username'];
			foreach($this->posttypes as $type){
				$columnname = $type."post_id";
				$classname = ucfirst($type)."Post";
				$functionToCall = "get";
				$functionToCall .= $classname;
				
				if(!empty($p[$columnname])) {
					$post = $this->{$functionToCall}($p[$columnname]);
					$postlist[] = array('post' => $post, 'contributor' => $contributor_name);
				}
			}
		
		}
		return $postlist;
	}
	public function getPostFromContribution($c) {
				$classname = ucfirst($c->getPostType())."Post";
				$functionToCall = "get";
				$functionToCall .= $classname;
				
				$post = $this->{$functionToCall}($c->getPostId());
				return $post;
	}
	
	
	public function getPostsByContributorId() {
		
	}
	
	public function getPostsByLanguageId() {
		
	}
	
	public function getPostsByTag() {
		
	}
	
	public function getPostsByType() {
		
	}
	public function getContributionsByUser($user_id) {
		$conditions['contributor_id'] = $user_id;
		$resultset = $this->wrapper->selectWhere($this->ctable, null, $conditions);
		$contributions = array();
		foreach($resultset as $c){
			$contributions[] = array('c_id'=>$c['id'], 'b_id'=>$c['blog_id']);
		}
		return $contributions;
	}
	
	public function getSimplePost($id) {
		$conditions=array();
		// to do: sanitize input
		$p_id =  intval($id);
		$conditions['id'] = $p_id;
		$result = $this->wrapper->selectWhere($this->sptable, null, $conditions)->fetch();
		$simplepost = new SimplePost($result['id'], $result['title'], $result['description']);
		return $simplepost;
	}
	
	public function getBookPost($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		$result = $this->wrapper->selectWhere($this->bptable, null, $conditions)->fetch();
		$bookpost = new BookPost($result['id'], $result['title'], $result['description'], $result['author'], $result['publisher'], $result['pages'], $result['isbn']);
		return $bookpost;
	}
	
	// to do: finish
	public function getAudioPost($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		$result = $this->wrapper->selectWhere($this->aptable, $conditions)->fetch();
		$audiopost = new AudioPost($result['id'], $result['owned_by'], $result['title'], $result['description']);
		return $audiopost;
	}
	
	// to do: finish
	public function getVideoPost($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		$result = $this->wrapper->selectWhere($this->vptable, $conditions)->fetch();
		$videopost = new VideoPost($result['id'], $result['owned_by'], $result['title'], $result['description']);
		return $videopost;
	}
	
	public function getPostTypes(){
		return $this->posttypes;	
	}
	
	public function setSortoption($sortoption) {
		$this->sortopt = $sortoption;
	}
	public function getAllLanguages() {
		$p_langs = array();
		$s = 'short ASC';
		$resultset = $this->wrapper->selectAll('languages', $s);
		foreach($resultset as $r){
			$p_langs[] = array('id' => $r['id'], 'ename' => $r['english_name'], 'sname' => $r['self_name'], 'abbr' => $r['short']);
		}
		return $p_langs;
	}
	private function getTableName($type) {
		switch($type) {		
			case 'book':
				$table = $this->bptable;
				break;
			case 'audio':
				$table = $this->aptable;
				break;
			case 'video':
				$table = $this->vptable;
				break;
			case 'simple':
				$table = $this->sptable;
				break;
			default:
				$table = null;
		}
		return $table;
	}
	
}
?>