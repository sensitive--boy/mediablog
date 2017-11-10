
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
require_once 'nixda/settings.php';
include_once 'include/filemagic.php';
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
		#echo "pm";
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
				#echo "Model Created AudioPost.";
			break;
			case 'video':
				$post = new VideoPost(0, "", "");
				$post->setVisibility('members_only');
				#echo "Model Created VideoPost.";
			break;
		}
		return $post;
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
			$columnvals['published_at'] = date("Y-m-d H:i:s");
			#echo $columnvals['published_at'];
		} else {
			$columnvals['published'] = 0;
		}
		$visibility = array('is_public' => 0, 'members_only' => 0, 'friends_only' => 0);
		$visibility[$request['visibility']] = true;
		$columnvals['is_public'] = $visibility['is_public'];
		$columnvals['members_only'] = $visibility['members_only'];
		$columnvals['friends_only'] = $visibility['friends_only'];
		
		if(isset($request['extvlink']) && !empty($request['extvlink'])) {
			#echo "externes Video ";
			$columnvals['stored_externally'] = true;
			$columnvals['path_to_file'] = $request['extvlink'];
			$name = implode("-", (explode(" ", $request['title'])));
			$columnvals['filename'] = 'ext-'.$name;
			$columnvals['filetype'] = 'ext';
		}

		$plangs = array();

		$table = $this->getTableName($request['type']);
		$result = $this->wrapper->insert($table, $columnvals);
		if($result) {
			foreach($request['p-language'] as $l){
				#echo "<br>".$request['type']." Post: ".$result." spricht Sprache: $l ";
				$this->wrapper->insertWithoutId('posts_languages', array($request['type'].'post_id'=>$result, 'language_id' => $l));
			}
			$this->saveKeywords($request['type'], $result, $request['keywords']);
		}
		return $result;
	}
	
	public function saveKeywords($post_type, $post_id, $keywords) {
		#echo "I save keywords $keywords.";
		$keywords_arr = explode(",", $keywords);
		$sql1 = "WITH s as ( SELECT id FROM keywords WHERE keyword = ? ), i as (INSERT INTO keywords (keyword) SELECT ? WHERE NOT EXISTS (SELECT keyword FROM keywords WHERE keyword = ?) RETURNING id) select id from i union all select id from s;";
		$for_keywords = $this->wrapper->prep($sql1);

		foreach($keywords_arr as $kword){
			if(!(trim($kword) === '')) {
				#echo "zu speicherndes Keyword: ".$kword;
				// return id if keyword is allready in keywords table, otherwise insert and return id
				#$k_id = $this->wrapper->runAnyways("WITH s as ( SELECT id FROM keywords WHERE keyword = ? ), i as (INSERT INTO keywords (keyword) SELECT ? WHERE NOT EXISTS (SELECT keyword FROM keywords WHERE keyword = ?) RETURNING id) select id from i union all select id from s;", array($kword, $kword, $kword))->fetch()[0];
				$k_id = $this->wrapper->ex($for_keywords, array($kword, $kword, $kword));
				// new entry for each keyword per post in posts_keywords table
				#echo "KID = ".$k_id;
				$result = $this->wrapper->insertWithoutId('posts_keywords', array($post_type.'post_id' => $post_id, 'keyword_id' => $k_id));
			}
			#echo "Post Model saved Keywords. ";
		}
	}
	
	// add keywords that are not yet related to post and remove keywords that are not used anymore for this post
	public function updateKeywords($post_type, $post_id, $keywords) {
		#echo "hey, I got the keywords: $keywords";
		$resultset = $this->wrapper->selectWhere('posts_keywords', null, array($post_type.'post_id' => $post_id))->fetchAll();
		#echo "result: ";
		#print_r($resultset);
		$keywords = explode(',', $keywords);
		#print_r($keywords);
		$k_add = array();
		foreach($keywords as $kword){
			if(!in_array($kword, $resultset) && !(trim($kword)==='')){
				$k_add[] = $kword;
			}
		}
		if(!empty($k_add)){ $this->saveKeywords($post_type, $post_id, implode(',', $k_add));}
		
		//DELETE FROM posts_keywords p USING keywords WHERE p.keyword_id =  keywords.id AND keywords.keyword = ? AND p.$post_type.'post_id' = ?
		foreach($resultset as $r){
			if(!in_array($r['keyword'], $keywords)) {
				$this->wrapper->run("DELETE FROM posts_keywords p USING keywords WHERE p.keyword_id =  keywords.id AND keywords.keyword = ? AND p.".$post_type."post_id = ?;", array($r['keyword'], $post_id));
			}
		}
		#echo "Post Model updated Keywords. ";
		
	}
	
	public function getKeywords($post_type, $post_id) {
		$resultset = $this->wrapper->selectWhere('posts_keywords', null, array($post_type.'post_id' => $post_id))->fetchAll();
		if(empty($resultset)) { return null; }
		$keys = array();
		foreach($resultset as $r){
			$rs = $this->wrapper->selectWhere('keywords', null, array('id' =>$r['keyword_id']))->fetch();
			if($rs) {$keys[] = $rs['keyword'];}
		}
		
		return $keys;
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
		if($request['published'] == 'on') {
			$columnvals['published'] = true;
			$columnvals['published_at'] = date("Y-m-d H:i:s");
			#echo $columnvals['published_at'];
		} else {
			$columnvals['published'] = false;
		}
		$conditions['id'] = $request['id'];
		$table = $this->getTableName($request['type']);
		$result = $this->wrapper->updateWhere($table, $columnvals, $conditions);
		$this->updateKeywords($request['type'], $request['id'], $request['keywords']);
		return $result;
	}
	
	public function deletePost($type, $id) {
		#echo 'postModel: delete';
		try{
			$this->wrapper->beginTransaction();
			$this->wrapper->deleteWhere('posts_languages', array($type.'post_id' => $id));
			#echo 'deleted posts_languages';
			$this->wrapper->deleteWhere('posts_keywords', array($type.'post_id' => $id));
			#echo 'deleted posts_keywords';
			$this->wrapper->deleteWhere('contributions', array($type.'post_id' => $id));
			#echo 'deleted contribution';
			$this->wrapper->deleteWhere($type.'posts', array('id' => $id));
			#echo 'deleted simplepost';
			$this->wrapper->commit();
			return true;
		}catch (Exception $e) {
			$this->wrapper->rollback();
			#echo "Failed: ".$e->getMessage();
			return false;
		}
		
	}
		
	public function saveContribution($type, $post_id, $blog_id, $user_id) {
		$columnvals = array('contributor_id' => $user_id,'blog_id' => $blog_id, $type.'post_id' => $post_id );
		$result = $this->wrapper->insert($this->ctable, $columnvals);
		return $result;
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
		$user = $this->wrapper->selectWhere('tiusers', null, array('id'=>$result['contributor_id']))->fetch();
		$contribution->setUsername($user['username']);
		return $contribution;
	}
	
		
	public function getLatest($what, $visible, $limit) {
		
		$sql = '';
		
		if($what == 'all') {
			foreach($this->posttypes as $type){
				$visibility_request = ($visible == 'all' ) ? $type."posts.is_public = true OR ".$type."posts.members_only = true" : $type."posts.is_public = true";
				$sql .= 'SELECT '.$this->ctable.'.* FROM '.$this->ctable.' INNER JOIN '.$type.'posts on ('.$this->ctable.'.'.$type.'post_id = '.$type.'posts.id AND ('.$visibility_request.'))';
				$sql .= ' UNION ALL ';
			}
			// remove last ' UNION ALL'
			$sql = rtrim($sql, ' UNION ALL ');
		} else {
			$type = $what;
			$visibility_request = ($visible == 'all' ) ? $type."posts.is_public = true OR ".$type."posts.members_only = true" : $type."posts.is_public = true";
			$sql .= 'SELECT '.$this->ctable.'.* FROM '.$this->ctable.' INNER JOIN '.$type.'posts on '.$this->ctable.'.'.$type.'post_id = '.$type.'posts.id AND ('.$visibility_request.')';
		}
		$sql .= ' ORDER BY contribution_date DESC LIMIT '.$limit.';';

		#echo "getLatest request: ".$sql;
		$results = $this->wrapper->run($sql);
		$contributions = array();
		foreach($results as $result){
			if($result['simplepost_id']) { $post_type = 'simple'; $post_id = $result['simplepost_id'];}
			elseif($result['bookpost_id']) { $post_type = 'book'; $post_id = $result['bookpost_id'];}
			elseif($result['audiopost_id']) { $post_type = 'audio'; $post_id = $result['audiopost_id'];}
			elseif($result['videopost_id']) { $post_type = 'video'; $post_id = $result['videopost_id'];}
			else {}
			$c = new Contribution($result['id'], $result['contributor_id'], $result['blog_id'], $post_type, $post_id);
			$c->setPost($this->getPost($post_type, $post_id));
			$user = $this->wrapper->selectWhere('tiusers', null, array('id'=>$result['contributor_id']))->fetch();
			$c->setUsername($user['username']);;
			$contributions[] = $c;
		}
		if(empty($contributions)) { echo "Oh no, no contribution!";}
		return $contributions;
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
					// function call should be something like getSimplePost(8)
					$post = $this->{$functionToCall}($c[$type.'post_id']); 
					#echo "got Post: ".$post->getId();
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
				#echo $result['description'];
		$simplepost = new SimplePost($result['id'], $result['title'], $result['description']);
		$keys = $this->wrapper->selectWhere('posts_keywords', null, array('simplepost_id' => intval($id)))->fetchAll();
		$keywords = array();
		foreach($keys as $k){
			$keywords[] = $this->wrapper->selectWhere('keywords', null, array('id' => intval($k)))->fetch()['keyword'];
		}
		foreach($keywords as $keyword){
			$simplepost->addKeyword($keyword);
		}
		
		if($result['published'] == true) {
			$simplepost->setPublished(true);
			$simplepost->setPublishedAt(isset($result['published_at']) ? $result['published_at'] : date("Y-m-d H:i:s") );
		}
		if($result['is_public'] == true) {
			$simplepost->setVisibility('is_public');
		}elseif($result['members_only'] == true) {
			$simplepost->setVisibility('members_only');
		}else {
			$simplepost->setVisibility('friends_only');
		}
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
		$result = $this->wrapper->selectWhere($this->aptable, null, $conditions)->fetch();
		$audiopost = new VideoPost($result['id'], $result['title'], $result['description']);
		$audiopost->setFilename($result['filename']);
		$audiopost->setFiletype($result['filetype']);
		$audiopost->setPublished($result['published']);
		$audiopost->setPublishedAt($result['published_at']);
		$audiopost->setPath_to_file($result['path_to_file']);
		$keys = $this->wrapper->selectWhere('posts_keywords', null, array('audiopost_id' => intval($id)))->fetchAll();
		$keywords = array();
		foreach($keys as $k){
			$keywords[] = $this->wrapper->selectWhere('keywords', null, array('id' => intval($k)))->fetch()['keyword'];
		}
		foreach($keywords as $keyword){
			$audiopost->addKeyword($keyword);
		}
		return $audiopost;
	}
	
	// to do: finish
	public function getVideoPost($id) {
		$conditions=array();
		// to do: sanitize input
		$conditions['id'] = $id;
		$result = $this->wrapper->selectWhere($this->vptable, null, $conditions)->fetch();
		$videopost = new VideoPost($result['id'], $result['title'], $result['description']);
		$videopost->setFilename($result['filename']);
		$videopost->setFiletype($result['filetype']);
		if($result['is_public'] == true) {
			$videopost->setVisibility('is_public');
		}elseif($result['members_only'] == true) {
			$videopost->setVisibility('members_only');
		}else {
			$videopost->setVisibility('friends_only');
		}
		$videopost->setPublished($result['published']);
		$videopost->setPublishedAt($result['published_at']);
		if($result['stored_externally'] == true) {
			$videopost->setStoredExternally(true);
		}
		$videopost->setPath_to_file($result['path_to_file']);
		$keys = $this->wrapper->selectWhere('posts_keywords', null, array('videopost_id' => intval($id)))->fetchAll();
		$keywords = array();
		foreach($keys as $k){
			$keywords[] = $this->wrapper->selectWhere('keywords', null, array('id' => intval($k)))->fetch()['keyword'];
		}
		foreach($keywords as $keyword){
			$videopost->addKeyword($keyword);
		}
		return $videopost;
	}
	
	public function findOrCreateFolder($type, $blog_id) {
		#echo " Task: create Folder";
		$path = MEDIAFOLDER."b".$blog_id."/";
		if(!is_dir($path.$type."s")){ 
			if(!is_writable($path)) {
				echo "No right to write.";
			} else {
				chdir($path);
				$old_umask = umask(0);
				#echo "I am here: ".$_SERVER['SCRIPT_FILENAME'];
            mkdir($type."s");
            umask($old_umask);
            chdir(BASEURL);
         }
      } else {}
      return $path.$type."s/";
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