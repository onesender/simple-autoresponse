<?php

require_once './vendor/autoload.php';

define('BASEPATH', __DIR__ );
define('SETTING_FILE', BASEPATH . '/config.yaml' );


date_default_timezone_set('Asia/Jakarta');
ini_set('date.timezone', 'Asia/Jakarta');
date_default_timezone_set('Asia/Jakarta');

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

\App\Framework::start();