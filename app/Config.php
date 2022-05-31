<?php

namespace App;

class Config {
	
	public static $instance;

	/**
	 * Load setting as a singleton class
	 */
	public static function loadData() {
		if (empty(static::$instance)) {
			$yaml = YamlParser::getInstance();
			$setting = $yaml->loadSettings();

			self::$instance = $setting;
		}
		return self::$instance;
	}

}