<?php
namespace Test;

class BookList 
{
	private $pdo;
	private $sort = 'newest';
	private $keyword;

	public function __construct(\PDO $pdo, string $sort = 'newest', string $keyword = '') 
	{
		$this->pdo = $pdo;
		$this->sort = $sort;
		$this->keyword = $keyword;
	}

	public function sort($dir): self 
	{
		return new self($this->pdo, $dir, $this->keyword);
	}

	public function search($keyword): self 
	{
		return new self($this->pdo, $this->sort, $keyword);
	}

	public function getKeyword(): string 
	{
		return $this->keyword;
	}

	public function getSort(): string 
	{
		return $this->sort;
	}

	public function delete($id): self 
	{
		$stmt = $this->pdo->prepare('DELETE FROM books WHERE book_id = :id');
		$stmt->execute(['id' => $id]);

		return $this;
	}

	public function getBooks(): array 
	{
		$parameters = [];

		if ($this->sort == 'newest') 
		{
			$order = ' ORDER BY book_id DESC';
		}
		else if ($this->sort == 'oldest') 
		{
			$order = ' ORDER BY book_id ASC';
		}
		else 
		{
			$order = ' ';
		}

		$inner = 'INNER JOIN authors authors ON books.author_id = authors.author_id';

		if ($this->keyword) 
		{
			$where = ' WHERE authors.author_name LIKE :author_name';
			$parameters['author_name'] = '%' . $this->keyword . '%';
		}
		else 
		{
			$where = '';
		}

		$limit = ' LIMIT 100';


		$stmt = $this->pdo->prepare('SELECT books.book_id, books.author_id, books.book_name, authors.author_name FROM books books ' . $inner . $where . $order . $limit);
		$stmt->execute($parameters);

		return $stmt->fetchAll();
	}
}