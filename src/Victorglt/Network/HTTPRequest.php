<?php
namespace Victorglt\Network;

class HTTPRequest {
		
		private $ch;
		
		public function __construct($url, $headers) {
			$this->ch = curl_init();
			
			curl_setopt($this->ch, CURLOPT_URL, $url);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($this->ch, CURLOPT_HEADER, FALSE);
				
			if(isset($headers)){
				curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
			}
		}
		
		public function __destruct() {
			curl_close($this->ch);
		}

		public function setOpt($opt, $value){
			curl_setopt($this->ch, $opt, $value);
		}
	
		public function get(){
			curl_setopt($this->ch, CURLOPT_HTTPGET, true);
			return curl_exec($this->ch);
		}
		
		public function customRequest($method, $parameters) {
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $parameters);
			
			return curl_exec($this->ch);
		}
		
		public function post($parameters){
			curl_setopt($this->ch, CURLOPT_POST, TRUE);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
			return curl_exec($this->ch);
		}
		
		public function getStatus(){
			return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);	
		}
} 