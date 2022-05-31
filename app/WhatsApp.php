<?php

namespace App;

class WhatsApp {

	protected $url;
	protected $token;
	protected $message;
	protected $recipient;
	protected $recipientType = 'individual';


	public function __construct() {
		$this->url = config('api_url');
		$this->token = config('api_key');
	}

	public function setMessage($data) {
		$this->message = $data;

		return $this;
	}

	public function setRecipient($data) {
		$this->recipient = $data;

		if (str_contains($this->recipient, '@g.us')) {
			$this->recipientType = 'group';
		}

		return $this;
	}


	public function send() {
		$messageArgs = array_merge( $this->message, [
			'to' => $this->recipient,
			'recipient_type' => $this->recipientType,
		]);

		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => trim($this->url),
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => json_encode($messageArgs),
		  CURLOPT_HTTPHEADER => array(
		    'Authorization: Bearer ' . trim($this->token),
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

}