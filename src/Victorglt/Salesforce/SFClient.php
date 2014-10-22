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

class SFClient {

	private $credentials;

	public function  __construct($username, $password, $user_token, $client_id, $client_secret, $isSandbox = false){
		$request = new HTTPRequest($isSandbox == true ? SFUrlBuilder::SANDBOX_URL : SFUrlBuilder::PROD_URL, null);
		
		$result = $request->post(array(
				'username' => $username,
				'password' => $password.$user_token,
				'grant_type' => 'password',
				'client_id' => $client_id,
				'client_secret' => $client_secret
		));
	
		
		$this->credentials = $this->parseResult($result, $request->getStatus());
	}

	
	public function getObject($object, $fields, $id){
		$request = new HTTPRequest(SFUrlBuilder::objectUrl($this->credentials->instance_url, 'v31.0', $object,  $id, array('fields' => $fields))
								  ,array('Authorization: Bearer '.$this->credentials->access_token));

		$result = $request->get();
		return $this->parseResult($result, $request->getStatus());	
	}

	public function query($query){
		$request = new HTTPRequest(SFUrlBuilder::queryUrl($this->credentials->instance_url, 'v31.0' , array('q' => $query))
					,array('Authorization: Bearer '.$this->credentials->access_token));
		
		$result = $request->get();
		return $this->parseResult($result, $request->getStatus());		}


	public function updateObject($object, $data, $id){
		$encodedData = json_encode($data);
		$request = new HTTPRequest(SFUrlBuilder::objectUrl($this->credentials->instance_url, 'v31.0', $object, $id, $data)
								   ,array('Content-Type: application/json',
									      'Content-Length: ' . strlen($encodedData),
									      'Authorization: Bearer '.$this->credentials->access_token));
		
		$result = $request->customRequest('PATCH', $encodedData);

		return $this->parseResult($result, $request->getStatus());
	}

	public function createObject($object, $data){
		
		$request = new HTTPRequest(SFUrlBuilder::objectUrl($this->credentials->instance_url, 'v31.0', $object, null, null)
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
	
}