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

class SFUrlBuilder {
	
	const PROD_URL = 'https://login.salesforce.com/services/oauth2/token';
	
	const SANDBOX_URL = 'https://test.salesforce.com/services/oauth2/token';
	
	public static function objectUrl($instance, $version, $object, $id, $parameters){
		$url = $instance.'/services/data/'.$version.'/sobjects/'.$object.'/'; 
		
		if(isset($id)){
			$url = $url.$id;
			if(isset($parameters)){
				$url = $url.'?'.http_build_query($parameters);
			}
		}

		return $url;
	}
	
	public static function queryUrl($instance, $version, $parameters = array()){
		return $instance.'/services/data/'.$version.'/query/?'.http_build_query($parameters);
	}
}