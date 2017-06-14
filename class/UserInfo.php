<?php
/************
* folder: tiblogs/class
* mvc multiblog project
* UserInfo.php -> Einfache Klasse UserInfo
* @autor: Kai Noah Schirmer
* (c) january 2017
*************/
class UserInfo{
	private $id;
	private $avatarPath;
	private $realName;
	private $residency;
	private $place_of_birth;
	private $date_of_birth;
	private $languages[];
	private $websites[];
	private $story;
	private $user_id;
	
	public function __construct($userID) {
		$this->user_id = $userID;
	}
	
	public function getAvatarPath() {
		return $this->avatarPath;
	}
	public function getRealName() {
		return $this->realName;
	}
	public function getResidency() {
		return $this->residency;
	}
	public function getPlaceOfBirth() {
		return $this->place_of_birth;
	}
	public function getDateOfBirth() {
		return $this->date_of_birth;
	}
	public function getLanguages() {
		return $this->languages;
	}
	public function getWebsites() {
		return $this->websites;
	}
	public function getStory() {
		return $this->story;
	}
	public function getId() {
		return $this->id;
	}
	
	public function getAvatarPath($path) {
		$this->avatarPath = $path;
	}
	public function setRealName($name) {
		$this->realName = $name;
	}
	public function setResidency($location) {
		$this->residency = location;
	}
	public function setPlaceOfBirth($place) {
		$this->place_of_birth = $place;
	}
	public function setDateOfBirth($date) {
		$this->date_of_birth = $date;
	}
	public function setLanguages($lang_arr) {
		$this->languages = $lang_arr;
	}
	public function addLanguage($lang) {
		$this->languages[] = $lang;
	}
	public function setWebsites($websites_arr) {
		$this->websites = $websites_arr;
	}
	public function addWebsite($website) {
		$this->websites[] = $website;
	}
	public function removeWebsite($website) {
		
	}
	public function setStory($story) {
		$this->story = $story;
	}

}
?>