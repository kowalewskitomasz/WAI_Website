<?php
require_once '../dispatcher.php';
require_once '../routing.php';
require_once '../controllers.php';

//aspekty globalne
session_start();

//wyb�r kontrolera do wywo�ania:
$action_url = $_GET['action'];
dispatch($routing, $action_url);
