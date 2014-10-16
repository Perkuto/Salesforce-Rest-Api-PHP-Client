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

class SFClient {

	private $credentials;
	
	private const PROD_URL = 'https://login.salesforce.com/services/oauth2/token';
	
	private const SANDBOX_URL = 'https://test.salesforce.com/services/oauth2/token';
	
	public function  __construct($username, $password, $user_token, $client_id, $client_secret, $isSandbox = false){
		$ch = curl_init();
		
		$fields = array(
				'username' => $username,
				'password' => $password.$user_token,
				'grant_type' => 'password',
				'client_id' => $client_id,
				'client_secret' => $client_secret
		);
		
		curl_setopt($ch, CURLOPT_URL, $isSandbox == true ? self::SANDBOX_URL : self::PROD_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		
		$result = curl_exec($ch);
		
		curl_close($ch);
		
		checkError($decoded);
		
		$this->credentials = $decoded;
	}


	public function getObject($object, $fields, $id){

		$fieldsParameter = array('fields' => $fields);
		
		$ch = curl_init();
		
		$instance = $this->credentials->instance_url;
		$token = $this->credentials->access_token;
		
		curl_setopt($ch, CURLOPT_URL, $instance.'/services/data/v31.0/sobjects/'.$object.'/'.$id.'?'.http_build_query($fieldsParameter));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_HEADER, false);


		$result = curl_exec($ch);

		curl_close($ch);

		checkError($decoded);
		
		return $decoded;
	}



	public function query($query){

		$ch = curl_init();

		$instance = $this->credentials->instance_url;
		$token = $this->credentials->access_token;

		curl_setopt($ch, CURLOPT_URL, $instance.'/services/data/v31.0/query/?'.http_build_query(array('q' => $query)));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		curl_setopt($ch, CURLOPT_HEADER, false);


		$result = curl_exec($ch);

		curl_close($ch);

		checkError($decoded);
	
		return $decoded;
	}


	private function updateObject($object, $data, $id){

		$instance = $this->credentials->instance_url;
		$token = $this->credentials->access_token;
		
		$json = json_encode($data);

		$ch = curl_init();

		curl_setopt($ch,  CURLOPT_URL, $instance.'/services/data/v31.0/sobjects/'.$object.'/'.$id);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($json),
		'Authorization: Bearer '.$token)
		);

		$decoded = json_decode(curl_exec($ch));

		checkError($decoded);
		
		return $decoded;
	}

	public function createObject($object, $data){

		$instance = $this->credentials->instance_url;
		$token = $this->credentials->access_token;
		
		$json = json_encode($data);

		$ch = curl_init();

		curl_setopt($ch,  CURLOPT_URL, $instance.'/services/data/v31.0/sobjects/'.$object);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($json),
		'Authorization: Bearer '.$token)
		);

		$decoded = json_decode(curl_exec($ch));

		checkError($decoded);

		return $decoded;

	}
	
	private function checkError($json){
		if(isset($json->error_code)){
			throw new SalesforceException($json->message, $json->errorCode);
		}
	}
}