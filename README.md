REST API Client for Salesforce.com
=========================================

Usage
=====

Login

```
//Choose your authentication method
$authentication = new PasswordAuth();
$authentication->setUsername('username')
			->setPassword('password')
			->setUserToken('user_token')
			->setClientId('client_id')
			->setClientSecret('client_secret');


//Create client (true production, false sandbox, and API version)
$client = new SFClient($authentication, false, 'v31.0');

```

Get Object

```
//Object name , desired fields and object id
$client->getObject('Account', 'Name, BillingStreet', '001900K0001pPuOAAU');
```

Update Object

```
//Object name, data changed and object id
$client->updateObject('Account', array('Name' => 'Victor'), '001900K0001pPuOAAU');

```

Query

```
$client->query('SELECT Id, FirstName, LastName, Email FROM Contact');

```


