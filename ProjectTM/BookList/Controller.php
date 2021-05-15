<?php

namespace BookList;

class Controller 
{
	public function filterList(\Test\BookList $bookList): \Test\BookList  
	{
		if (!empty($_GET['sort'])) 
			$bookList = $bookList->sort($_GET['sort']);

		if (!empty($_GET['search'])) 
			$bookList = $bookList->search($_GET['search']);

		return $bookList;
	}

	public function delete(\Test\BookList  $bookList): \Test\BookList  
	{
		if(!empty($_POST['book_id']))
			$bookList = $bookList->delete($_POST['book_id']);

		return $bookList;
	}
}