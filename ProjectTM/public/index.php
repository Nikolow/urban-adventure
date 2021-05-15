<?php
	spl_autoload_register(function($name) 
	{
		$parts = explode('\\', $name);
		$parts[0] = $parts[0] == 'Test' ? 'Model' : $parts[0];

		require '../' . implode(DIRECTORY_SEPARATOR, $parts) . '.php';
	});

	/* 
		DB CONNECTION
	*/
	$dbConnection = "pgsql:host=localhost;port=5432;dbname=postgres;user=postgres;password=advancedd";
	$pdo = new \PDO($dbConnection);
	$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	//


	/*
		ROUTING
		'' - index page
		'delete' - delete action
		'filterBooks' - filter & search
	*/
	$pageRoute = $_GET['route'] ?? '';

	if ($pageRoute == '') // index
	{
		$Model = new \Test\BookList($pdo);
		$View = new \BookList\View();
	}
	else if ($pageRoute == 'delete') // delete
	{
		$Model = new \Test\BookList($pdo);
		$Controller = new \BookList\Controller();

		$Model = $Controller->delete($Model);

		$View = new \BookList\View();
	}
	else if ($pageRoute == 'filterBooks') // filter & search
	{
		$Model = new \Test\BookList($pdo);
		$View = new \BookList\View();
		$Controller = new \BookList\Controller();

		$Model = $Controller->filterList($Model);
	}
	else // all others
	{
		http_response_code(404);
		echo 'Page not found (Invalid route)';
	}




	echo $View->show($Model);