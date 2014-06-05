<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Elibom {

	private $url = 'https://www.elibom.com/';
	
    private $_ci;	

    private $_config = array(
       'user' => NULL,
	   'token' => NULL
    );

    public function __construct($config = array())
    {
        if (count($config) > 0)
        {
            $this->initialize($config);
        }
        $this->_ci =& get_instance();
        $this->_ci->load->config('elibom');
        $this->_config['user'] = $this->_ci->config->item("user");
        $this->_config['token'] = $this->_ci->config->item("token");
    }
	
    public function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            $this->_config[$key] = $val;
        }

        return $this;
    }


	///DEL FILE ELIBOM.PHP
	public function sendMessage($to, $txt) {
		$deliveryToken = $this->send($to, $txt);

		return $deliveryToken;
	}

	public function getDelivery($deliveryToken) {
		$deliveryData = $this->get($deliveryToken);

		return $deliveryData;
	}

	public function scheduleMessage($to, $txt, $date) {
		$scheduleId = $this->schedule($to, $txt, $date);

		return $scheduleId;
	}

	public function getScheduledMessage($scheduleId) {
		$schedule = $this->get_scheduler($scheduleId);

		return $schedule;
	}

	public function getScheduledMessages() {
		$schedules = $this->getAll();

		return $schedules;
	}

	public function unscheduleMessage($scheduleId) {
		$schedules = $this->unschedule($scheduleId);
	}

	public function getUsers() {
		$users = $this->getAll_user();

		return $users;
	}

	public function getUser($userId) {
		$user = $userController->get_user($userId);

		return $user;
	}

	public function getAccount() {
		$account = $this->get_account();

		return $account;
	}
	/// FIN ELIBOM.PHP



	///DEL FILE account.PHP
	public function get_account() {
		$response = $this->get_client('account');

		return $response;
	}
	/// FIN account.PHP


	///DEL FILE MESSENGE.PHP
	public function send($to, $txt) {
		$data = array("destinations" => $to, "text" => $txt);
		$response = $this->post('messages', $data);
		print_r($response);die();
		return $response->deliveryToken;
	}
	/// FIN MENSSENGE.PHP


	///DEL FILE DELIVERY.PHP
	public function get($id) {
		$response = $this->get_client('messages/' . $id);

		return $response;
	}
	/// FIN DELIVERY.PHP


	///DEL FILE USER.PHP
	public function getAll_user() {
		$response = $this->get_client('users');

		return $response;
	}

	public function get_user($id) {
		$response = $client->get_client('users/' . $id);

		return $response;
	}
	/// FIN USER.PHP


	///DEL FILE scheduler.PHP
	public function schedule($to, $txt, $date) {
		$data = array("destinations" => $to, "text" => $txt, "scheduleDate" => $date);
		$response = $this->post('messages', $data);

		return $response->scheduleId;
	}

	public function get_scheduler($id) {
		$response = $this->get_client('schedules/' . $id);

		return $response;
	}

	public function getAll() {
		$response = $this->get_client('schedules/scheduled');

		return $response;
	}

	public function unschedule($id) {
		$this->delete('schedules/' . $id);
	}
	/// FIN scheduler.PHP
	
	
	
	///DEL FILE CLIENT.PHP
	public function post($resource, $data) {
		$data_string = json_encode($data);

		$handler = curl_init($this->url . $resource);

		curl_setopt($handler, CURLOPT_POST, true);
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);

		$this->configureHeaders($handler, $data_string);

		curl_setopt($handler, CURLOPT_POSTFIELDS, $data_string);

		$response = curl_exec ($handler);
		$code = curl_getinfo($handler, CURLINFO_HTTP_CODE);
		if ($code != 200) {
			$errorMessage = $this->getErrorMessage($code, $resource);
			throw new Exception($errorMessage);
		}

		return json_decode($response);
	}

	public function get_client($resource, $data = '{}') {
		$data_string = json_encode($data);

		$handler = curl_init($this->url . $resource);

		//curl_setopt($handler, CURLOPT_GET, true);
		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);

		$this->configureHeaders($handler, $data_string);

		curl_setopt($handler, CURLOPT_POSTFIELDS, $data_string);

		$response = curl_exec ($handler);
		$code = curl_getinfo($handler, CURLINFO_HTTP_CODE);
		if ($code != 200) {
			$errorMessage = $this->getErrorMessage($code, $resource);
			throw new Exception($errorMessage);
		}

		return json_decode($response);
	}

	public function delete($resource, $data = '{}') {
		$data_string = json_encode($data);

		$handler = curl_init($this->url . $resource);

		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);

		$this->configureHeaders($handler, $data_string);

		curl_setopt($handler, CURLOPT_POSTFIELDS, $data_string);

		$response = curl_exec ($handler);
		$code = curl_getinfo($handler, CURLINFO_HTTP_CODE);
		if ($code != 200) {
			$errorMessage = $this->getErrorMessage($code, $resource);
			throw new Exception($errorMessage);
		}

		return json_decode($response);
	}

	private function configureHeaders($handler, $data_string) {
		$auth_string = $this->_config['user'] .":" . $this->_config['token'];
		$auth = base64_encode ($auth_string);
		curl_setopt($handler, CURLOPT_HTTPHEADER, array(
			'Authorization: Basic ' . $auth,
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string)
			)
		);
	}

	private function getErrorMessage($code, $resource) {
		switch($code) {
			case 0 : {
				return 'Server not found, check your internet connection or proxy configuration.';
			}
			case 401 : {
				return 'Unauthorized resource [' . $resource . ']. Check your user credentials';
			}
			default : {
				return 'Unexpected error [' . $resource . '] [code=' . $code . ']';
			}
		}
	}
	/// FIN CLIENT.PHP
	
	
	
	
	
	
	
	
	
	
}