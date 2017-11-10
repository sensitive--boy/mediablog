<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* UserModel.php -> class UserModel
* cares about User objects
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
require_once 'class/TiAutoloader.php';
spl_autoload_register('TiAutoloader::ClassLoader');
class UserModel {
	private $table;
	private $wrapper;
	private $sortopt = "username ASC";
	
	public function __construct() {
		$this->wrapper = Wrapper::getInstance();
		$this->table  = 'tiusers';
	}
	public function createUser(){
		$user = new User(1, "", "","",0);
		#echo "createUser from UserModel";
		return $user;
	}
	
	public function getUserFromLogin($n, $e, $p){
		#echo "called getUserFromLogin with: ".$n." und ".$e." und ".$p;
		$conditions=array();
		$conditions['username'] = $n;
		$conditions['email'] = $e;
		#$conditions['verified'] = true;
		
		$result = $this->wrapper->selectWhere($this->table, null, $conditions, "AND")->fetch();
		if(password_verify($p, $result['pw'])) {
			#echo "User gefunden.";
			$user = new User($result['id'], $result['username'], $p, $result['email'], $result['is_editor']);
		} else {
			#echo "kein User gefunden.";
			$user = null;
		}
		return $user; 
		
	}
	
	
	public function registrateUser($n, $p, $email){
		#$n = substr($n, 0, 20);
		$user = $this->getUserFromLogin($n, $email, $p);
		$data = array();
		if($user != null){
					#echo "User allready exists";
			#PagesController::$notices[] = "Diese Username ist bereits mit dieser email registriert. Bitte anderen Usernamen oder Email wählen.";
			return null;		
		} else {
			#echo "User wird eingetragen.";
			$data["username"] = $n;
			$data["pw"] = password_hash($p, PASSWORD_DEFAULT);
			$data["email"] = $email;
			$u_id = $this->wrapper->insert($this->table, $data);
			#echo "User wurde eingetragen.";
			$this->wrapper->insertWithoutId('userinfos', array('user_id' => $u_id));
			return $this->getUserFromLogin($n, $email, $p); 
		}
	}
	
	public function getUser($id){
		$conditions=array();
		$conditions["id"] = $id;
		$result = $this->wrapper->selectWhere($this->table, null, $conditions)->fetch();
		$user = new User($result['id'], $result['username'], $result['pw'], $result['email'], $result['is_editor']);
		return $user;
	}
	
	public function getUsers(){
		$userlist = [];
		$resultset = $this->wrapper->selectAll($this->table, $this->sortopt);
		if(!empty($resultset)) {
			foreach($resultset as $u){
				$userlist[] = new User($u['id'], $u['username'], $u['pw'], $u['email'], $u['is_editor']);
			}
		} else { // Error!
			$userlist[] = 'albert';
			$userlist[] = 'kaus';
			$userlist[] = 'peter';
		}
		return $userlist;
	}
	
	
	public function updateUser($params){
		//To do: implement
	}
	
	public function getUserInfo($user_id) {
		$info_data = $this->wrapper->selectWhere('userinfos', null, array('user_id' => $user_id))->fetch();
		#print_r($info_data);
		echo $info_data['user_id'];
		$userinfo = new UserInfo($info_data['user_id']);
		$userinfo->setId($info_data['id']);
		$a_path = isset($info_data['path_to_avatar']) ? $info_data['path_to_avatar'] : "";
		$userinfo->setAvatarPath($a_path);
		$userinfo->setImageDescription($info_data['imagedescription']);
		$rname = isset($info_data['realname']) ? $info_data['realname'] : "";
		$userinfo->setRealName($rname);
		$userinfo->setLanguages(explode(',', $info_data['languages']));
		$userinfo->setWebsites(explode(',', $info_data['websites']));
		$userinfo->setStory($info_data['story']);
		if($info_data['is_public']) {
			$visibility = 'is_public';
		} elseif($info_data['members_only']) { 
			$visibility = 'members_only';
		} else {
			$visibility = 'friends_only';
		}
		$userinfo->setVisibility($visibility);
		return $userinfo;
	}
	public function saveUserInfo($request) {
		$columnvals = array();
		$conditions = array('id'=>$request['id']);
		$columnvals['path_to_avatar'] = $request['path_to_avatar'];
		$columnvals['imagedescription'] = $request['imagedesc'];
		$columnvals['realname'] = $request['fullname'];
		$columnvals['story'] = $request['story'];
		$columnvals['languages'] = $request['languages'];
		$columnvals['websites'] = $request['websites'];
		
		$visibility = array('is_public' => 0, 'members_only' => 0, 'friends_only' => 0);
		$visibility[$request['visibility']] = true;
		$columnvals['is_public'] = $visibility['is_public'];
		$columnvals['members_only'] = $visibility['members_only'];
		$columnvals['friends_only'] = $visibility['friends_only'];
		
		$result = $this->wrapper->updateWhere('userinfos', $columnvals, $conditions);
		return $result;
	}
	
	public function setSortoption($sortoption) {
		$this->sortopt = $sortoption;
	}
	
	public function talk(){
		return "UserModel";
	}

}
?>