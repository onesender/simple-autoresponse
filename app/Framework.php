<?php

namespace App;

class Framework {

	/**
	 * Jalankan aplikasi
	 */
	public static function start() {
		$class = new static();
	}

	public function __construct() {
		$yaml = YamlParser::getInstance();
		$messageRules = $yaml->getDataMessages();

		$inboxMessage = $this->getInboxMessage();
		if (!$inboxMessage) {
			throw new \Exception("Invalid inbox message");
		}

		$listener = new WebhookListener($inboxMessage);
		
		$listener->addMessages($messageRules)
				 ->dispatch();
	}

	/**
	 * Ambil data inbox dari format json
	 * Contoh input webhook:
	 * {
	 *	  "messages": [
	 *	    {
	 *	      "from": "62812000001@s.whatsapp.net",
	 *	      "id": "990E0DCD7B177A26F1505C397CE93847",
	 *	      "text": {
	 *	        "body": "Hello apa kabar"
	 *	      },
	 *	      "timestamp": "2022-04-12T01:22:41.64+07:00",
	 *	      "type": "text"
	 *	    }
	 *	  ]
	 *	}
	 *
	 * @return array
	 */
	public function getInboxMessage() {
		$rawdata = file_get_contents("php://input");
		$json = json_decode($rawdata, true);

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return false;
		}

		if (!isset($json['messages'][0]['from'])) {
			return false;
		}

		return $json['messages'][0];
	}
}