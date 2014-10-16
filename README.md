Simple REST API Client for Salesforce.com
=========================================

Usage
=====

Login


```
$client = new SFClient('username', 'password', 'user_token', 'client_id', 'client_secret');

```

Get Object

```
$client->getObject('Account', 'Name, BillingStreet', '001900K0001pPuOAAU');
```

Update Object

```
$client->getObject('Account', array('Name' => 'Victor'), '001900K0001pPuOAAU');

```


Query

```
$client->query('SELECT Id, FirstName, LastName, Email FROM Contact');

```


