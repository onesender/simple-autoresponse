<?php

namespace App;

use Symfony\Component\Yaml\Yaml;
use App\Entity\Settings;

class YamlParser {
	
	public static $instance;

	public static function getInstance() {
		if (empty(static::$instance)) {
			self::$instance = new static();
		}
		return self::$instance;
	}
	
	protected $yamls = [];

	public function loadSettings() {
		$setting = new Settings();
		$arraySettings = Yaml::parseFile( SETTING_FILE );
		foreach($arraySettings as $key => $value) {
			$setting->{$key} = $value;
		}

		return $setting;
	}

	public function getDataMessages() {
		$path = BASEPATH . '/template/*.yaml';
		$files = glob($path);

		$defaultData = self::getDefaultData();
		return collect($files)->mapWithKeys(function($item, $key) use($defaultData) {
			$pathinfo = pathinfo($item);

			$yamlData = array_merge($defaultData, Yaml::parseFile($item));
			$key = $yamlData['trigger'];

			return [$key => $yamlData];
		})->all();
	}

	public static function getDefaultData(){
		return [
			'trigger' => '/menu',
			'response_type' => 'text',
			'response_message' => 'Terima kasih'
		];
	}
}