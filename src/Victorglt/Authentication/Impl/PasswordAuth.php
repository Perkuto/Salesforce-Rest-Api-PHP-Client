<?php
/**
 Simple Rest API for Salesforce.com
 Copyright (C) 2014 Victor Galante

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

namespace Victorglt\Authentication\Impl;

use Victorglt\Authentication\Authentication;
use Victorglt\Network\HTTPRequest;

class PasswordAuth implements Authentication{
	
	private $username;
	private $password;
	private $user_token;
	private $client_id;
	private $client_secret;
	
	public function getUsername() {
		return $this->username;
	}
	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}
	public function getPassword() {
		return $this->password;
	}
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}
	public function getUserToken() {
		return $this->user_token;
	}
	public function setUserToken($user_token) {
		$this->user_token = $user_token;
		return $this;
	}
	public function getClientId() {
		return $this->client_id;
	}
	public function setClientId($client_id) {
		$this->client_id = $client_id;
		return $this;
	}
	public function getClientSecret() {
		return $this->client_secret;
	}
	public function setClientSecret($client_secret) {
		$this->client_secret = $client_secret;
		return $this;
	}
	
	public function getAuthenticationRequestFields(){
		return array(
				'username' => $this->getUsername(),
				'password' => $this->getPassword().$this->getUserToken(),
				'grant_type' => 'password',
				'client_id' => $this->getClientId(),
				'client_secret' => $this->getClientSecret()
		);
	}
}