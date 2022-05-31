<?php

use App\Config;

function config($key) {
	$configs = Config::loadData();
	return $configs->{$key} ?? '';
}