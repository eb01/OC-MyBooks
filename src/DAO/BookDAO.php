<?php

namespace MyBooks\DAO;

use MyBooks\Domain\Book;

class BookDAO extends DAO
{
    /**
     * Return a list of all books, sorted by id (desc).
     *
     * @return array A list of all books.
     */
    public function findAll() {
        $sql = "select * from book order by book_id desc";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $books = array();
        foreach ($result as $row) {
            $bookId = $row['book_id'];
            $books[$bookId] = $this->buildDomainObject($row);
        }
        return $books;
    }

    /**
     * Creates a Book object based on a DB row.
     *
     * @param array $row The DB row containing Book data.
     * @return \MyBooks\Domain\Book
     */
    private function buildDomainObject(array $row) {
        $book = new Book();
        $book->setId($row['book_id']);
        $book->setTitle($row['book_title']);
        $book->setSummary($row['book_summary']);
        $book->setIsbn($row['book_isbn']);

        if (array_key_exists('author_id', $row)) {
            // Find and set the associated author
            $authorId = $row['author_id'];
            $author = $this->authorDAO->find($authorId);
            $book->setAuthor($author);
        }

        return $book;
    }
}