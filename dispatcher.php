<?php

const REDIRECT_PREFIX = 'redirect:';
const PARTIAL_PREFIX = 'partial:';

function dispatch($routing, $action_url)
{
    $controller_name = $routing[$action_url];

    $model = [];
    $view_name = $controller_name($model);

    build_response($view_name, $model);
}

function build_response($view, $model)
{
    if (strpos($view, REDIRECT_PREFIX) === 0) {
        $url = substr($view, strlen(REDIRECT_PREFIX));
        header("Location: " . $url);
        exit;
    } else if (strpos($view, PARTIAL_PREFIX) === 0) {
		$url = substr($view, strlen(PARTIAL_PREFIX) + 1);
		render($url, $model, true);
	} else {
        render($view, $model, false);
    }
}

function render($view_name, $model, $view_only)
{
    global $routing;
    extract($model);
	
	if ($view_only) {
		include 'views/' . $view_name . '.php';
		return;
	}
	
    include 'views/common/head.php';
	
    include 'views/common/body_header.php';
    include 'views/common/body_menu.php';
    include 'views/' . $view_name . '.php';
    include 'views/common/body_footer.php';
}
