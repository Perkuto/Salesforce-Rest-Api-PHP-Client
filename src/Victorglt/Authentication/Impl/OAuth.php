<?php

namespace Victorglt\Authentication\Impl;

use Victorglt\Authentication\Authentication;
class OAuth implements Authentication {
	
	private $code;
	
	private $client_id;
	
	private $client_secret;
	
	private $redirect_uri;
	
	public function getCode() {
		return $this->code;
	}
	public function setCode($code) {
		$this->code = $code;
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
	public function getRedirectUri() {
		return $this->redirect_uri;
	}
	public function setRedirectUri($redirect_uri) {
		$this->redirect_uri = $redirect_uri;
		return $this;
	}
	
	public function getAuthenticationRequestFields() {
		return array('grant_type' => 'authorization_code',
					'code' => $this->getCode(),
					'client_id' => $this->getClientId(),
					'client_secret' => $this->getClientSecret(),
					'redirect_uri' => $this->getRedirectUri()
					);
	}

}