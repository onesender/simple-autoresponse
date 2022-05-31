<?php

namespace App;

class MessageParser {

	protected $message;
	protected $to;

	public function __construct($rule, $inbox) {
		$this->to = $inbox['from'];

		switch($rule['response_type']) {
			case 'button': 
				$this->message = $this->buttonParser($rule);
				break;

			case 'list': 
				$this->message = $this->listParser($rule);
				break;

			case 'image': 
				$this->message = $this->imageParser($rule);
				break;
			
			case 'document': 
				$this->message = $this->documentParser($rule);
				break;

			default:
				$this->message = $this->textParser($rule);
		}
	}

	private function textParser($rule) {
		return [
			'type' => 'text',
			'text' => ['body' => $rule['response_message']]
		];
	}

	private function imageParser($rule) {
		return [
			'type' => 'image',
			'image' => [
				'link' => $rule['response_media_link'],
				'caption' => $rule['response_message'],
			]
		];
	}

	private function documentParser($rule) {
		return [
			'type' => 'document',
			'document' => [
				'link' => $rule['response_media_link'],
			]
		];
	}

	private function buttonParser($rule) {
		$buttons = collect($rule['buttons'])
			->mapWithKeys(function($item, $key){
				$buttonObj = [
					'type' 	=> 'reply',
                    'reply' => [
                        'id' 	=> sprintf('btn%d_%d', $key, ($key + 1)),
                        'title' => $item,
                    ]
				];

				return [$key => $buttonObj];
			})->all();

		return [
			'type' => 'interactive',
			'interactive' => [
				'type' => 'button',
				'body' => ['text' => $rule['response_message']],
				'footer' => ['text' => $rule['response_message_footer']],
				'action' => [
					'buttons' => $buttons,
				]
			]
		];
	}


	private function listParser($rule) {
		$sections = collect($rule['list'])
			->map(function($item, $key){
				$rows = collect($item['options'])
					->map(function($item, $skey) use($key) {
						$array = explode('|', $item, 2);
						$title = $array[0];
						$description = $array[1] ?? '';

						$key++;
						$skey++;
						$id = sprintf('option-%d-%d', $key, $skey);

						return [
							'id' => $id,
							'title' => $title,
							'description' => $description,
						];
					})->all();

				$section = [
					'title' => $item['title'],
					'rows' => $rows,
				];
				return $section;
			})->all();


		return [
			'type' => 'interactive',
			'interactive' => [
				'type' => 'list',
				'body' => ['text' => $rule['response_message']],
				'footer' => ['text' => $rule['response_message_footer']],
				'action' => [
					'button' => $rule['list_label'],
					'sections' => $sections,
				]
			]
		];
	}

	

	public function doReply() {
		$wa = new WhatsApp();
		return $wa->setMessage($this->message)
		   ->setRecipient($this->to)
		   ->send();
	}
}