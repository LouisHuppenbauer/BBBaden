<?php

class Session {
	private $vars = array();
	private $SESSID;

	public function get($key) {
		if(empty($this->SESSID)) {
			return false;
		}

		return array_key_exists($key, $this->vars) ? $this->vars[$key] : false;
	}

	public function set($key, $value) {
             	if(empty($this->SESSID)) {
                        return false;
              	}

		$this->vars[$key] = $value;
	}

	public function start() {
              	if(!empty($this->SESSID)) {
                        return true;
              	}

		if(empty($_COOKIE['SESSID'])) {
			$this->SESSID = $this->generateSESSID();
			$this->setSessionCookie();
			$this->createSessionFile();
		}
		else {
			$this->SESSID = $_COOKIE['SESSID'];
		}

		if($this->getSessionFilePath()) {
			$this->loadSessionFile();
			return true;
		}
		else {
			unset($_COOKIE['SESSID']);
			$this->SESSID = 0;
			$this->start();
		}

		return false;
	}

	public function save() {
              	if(empty($this->SESSID)) {
              		return false;
              	}

		file_put_contents($this->getSessionFilePath(), serialize($this->vars));
	}

	private function loadSessionFile() {
	        $this->vars = unserialize(file_get_contents($this->getSessionFilePath()));
	}

	private function setSessionCookie() {
		setcookie('SESSID', $this->SESSID);
	}

	private function createSessionFile() {
		return file_put_contents($this->getSessionFilePath(), array());
	}

	private function generateSESSID() {
		return md5(microtime() + mt_rand(1,100000));
	}

	private function getSessionFilePath() {
		return 'storage/'.$this->SESSID.'.sess';
	}
}
