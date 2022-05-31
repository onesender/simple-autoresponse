<?php

namespace App;

class WebhookListener {

	public $inbox; 
	public $rules; 

	public function __construct($inboxMessage) {
		$this->inbox = $inboxMessage;
	}

	public function addMessages($rules) {
		$this->rules = $rules;
		return $this;
	}

	public function dispatch() {
		$inboxContent = $this->getMessageContent();

		$triggers = collect(array_keys($this->rules));

		if($triggers->contains($inboxContent)) {
			$rule = $this->getRuleFor($inboxContent);
			
			if (!$rule) {
				return;
			}

			$message = $this->transformMessage($rule, $this->inbox);
			$respose = $message->doReply();

			echo $respose;
		} else {
			echo 'No trigger word found';
		}

		return $this;
	}

	private function transformMessage($rule, $inbox) {
		return new MessageParser($rule, $inbox);
	}

	private function getRuleFor($inboxContent) {
		return $this->rules[$inboxContent] ?? false;
	}

	private function getMessageContent() {
		$output = '';
		$type = $this->inbox['type'];

		if ($type == 'interactive') {
			if (isset($this->inbox['button']['title'])) {
				$output = $this->inbox['button']['title'];
			}

			if (isset($this->inbox['list']['title'])) {
				$output = $this->inbox['list']['title'];
			}
		} else {
			$output = $this->inbox['text']['body'] ?? '';
		}

		return $output;
	}

}