<h1>Elibom PHP - CodeIgniter API Client</h1>
==========

A php client of the Elibom REST API. <a href="http://www.elibom.com/developers/reference">The full API reference is here</a>


<h2>Requisites</h2>

cURL (apt-get install php5-curl)

<h2>Getting stared</h2>

1. Install

    {
		<h3>URL DE DESCARGA</h3>
	}
	
1. Modificamos nuestras credenciales en el archivo <b>elibom.php</b> en application/config

	```php
		// user : email de elibom
		// token: codigo Api de elibom

		$config['user'] 	= 'xxxxxxxxxxx';
		$config['token'] 	= 'xxxxxxxxxxx';
	```


2. Creamos un archivo en el directorio application/controllers al cual llamaremos <b>sms.php</b>:

    ```php
	class Sms extends CI_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->library('elibom');
		}
		public function index()
		{
			$sms = $this->elibom->sendMessage('numero de celular', 'texto del mensaje');
		}

    ```
    
    Note: You can find your api password at http://www.elibom.com/api-password (make sure you are logged in).
    
    You are now ready to start calling the API methods!

<h2>API Methods</h2>
<h4><b>elibom.php</b>  application/libraries</h4>


* [Send SMS](#send-sms)
* [Schedule SMS](#schedule-sms)
* [Show Delivery](#show-delivery)
* [List Scheduled SMS Messages](#list-scheduled-sms-messages)
* [Show Scheduled SMS Message](#show-scheduled-sms-message)
* [Cancel Scheduled SMS Message](#cancel-scheduled-sms-message)
* [List Users](#list-users)
* [Show User](#show-user)
* [Show Account](#show-account)

### Send SMS
```php
//Return string
$deliveryId = $this->elibom->sendMessage('3201111111','PHP - TEST');
```

### Show Delivery
```php
//Return json object
$delivery = $this->elibom->getDelivery('<delivery_token>');
```

### Schedule SMS 
```php
//Return string
$scheduleId  = $this->elibom->scheduleMessage('3201111111', 'Test PHP', 'dd/MM/yyyy hh:mm');
```

### List Scheduled SMS Messages
```php
//Return json array
$scheduledMessages = $this->elibom->getScheduledMessages();
```

### Show Scheduled SMS Message
```php
//Return json object
$schedule = $this->elibom->getScheduledMessage('<schedule_id>');
```

### Cancel Scheduled SMS Message
```php
//Void
$this->elibom->unscheduleMessage('<schedule_id>');
```

### List Users
```php
//Return json array
$users = $this->elibom->getUsers();
foreach($users as $user) {
        echo json_encode($user);
}
```

### Show User
```php
//Return json object
$user = $this->elibom->getUser('<user_id>');
```

### Show Account
```php
//Return json object
$account = $this->elibom->getAccount();
```
