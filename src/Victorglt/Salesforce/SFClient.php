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
namespace Victorglt\Salesforce;

use Victorglt\Network\HTTPRequest;
use Victorglt\Authentication\Authentication;

class SFClient {

	private $credentials;
	
	private $version;

	public function  __construct(Authentication $authentication, $isSandbox = false, $version){
		$this->version = $version;
		$request = new HTTPRequest($isSandbox == true ? SFConstants::SANDBOX_URL : SFConstants::PROD_URL, null);
		$result = $request->post($authentication->getAuthenticationRequestFields());
		$this->credentials = $this->parseResult($result, $request->getStatus());
	}

	
	public function getObject($object, $fields, $id){
		$request = new HTTPRequest($this->objectUrl($object,  $id, array('fields' => $fields)) ,array('Authorization: Bearer '.$this->credentials->access_token));
		$result = $request->get();
		return $this->parseResult($result, $request->getStatus());	
	}

	public function query($query){
		$request = new HTTPRequest($this->queryUrl(array('q' => $query)) ,array('Authorization: Bearer '.$this->credentials->access_token));
		$result = $request->get();
		return $this->parseResult($result, $request->getStatus());		
	}


	public function updateObject($object, $data, $id){
		$encodedData = json_encode($data);
		$request = new HTTPRequest($this->objectUrl($object, $id, $data)
								  	,array('Content-Type: application/json',
									      'Content-Length: ' . strlen($encodedData),
									      'Authorization: Bearer '.$this->credentials->access_token));
		
		$result = $request->customRequest('PATCH', $encodedData);
		return $this->parseResult($result, $request->getStatus());
	}

	public function createObject($object, $data){
		$encodedData = json_encode($data);
		$request = new HTTPRequest($this->objectUrl($object, null, null)
								  ,array('Content-Type: application/json',
									     'Content-Length: ' . strlen($encodedData),
									     'Authorization: Bearer '.$this->credentials->access_token));

		$result = $request->post($encodedData);
		return $this->parseResult($result, $request->getStatus());
	}
	
	private function parseResult($result, $httpStatus){
		$json = json_decode($result);
		
		if($httpStatus != 200 && $httpStatus != 204){
			if(isset($json->error)){
				throw new SalesforceException('Description: '.$json->error_description.' Error: '.$json->error, $httpStatus, null);
			}
			
			if(isset($json[0]->errorCode)){
				throw new SalesforceException('Description: '.$json[0]->message.' Error: '.$json[0]->errorCode, $httpStatus, null);
			}
			
			throw new SalesforceException('Description: Unknow Error', $httpStatus, null);
				
		}
		
		return $json;
	}
	
	

	private function objectUrl($object, $id, $parameters){
		$url = implode('/', array($this->credentials->instance_url, 'services', 'data', $this->version, 'sobjects', $object));
	
		if(isset($id)){
			$url = $url.'/'.$id;
			if(isset($parameters)){
				$url = $url.'?'.http_build_query($parameters);
			}
		}
		return $url;
	}
	
	private function queryUrl($parameters = array()){
		return $this->credentials->instance_url.'/services/data/'.$this->version.'/query/?'.http_build_query($parameters);
	}
	
	public function getVersion() {
		return $this->version;
	}
	public function setVersion($version) {
		$this->version = $version;
	}	
}