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