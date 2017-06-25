<?php

namespace MyBooks\DAO;

use MyBooks\Domain\Book;

class BookDAO extends DAO
{

    /**
     * @var \MicroCMS\DAO\AuthorDAO
     */
    private $authorDAO;

    public function setAuthorDAO(AuthorDAO $authorDAO) {
        $this->authorDAO = $authorDAO;
    }

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
        var_dump($books);
        return $books;
    }

     /**
     * Returns a book matching the supplied id.
     *
     * @param integer $id
     *
     * @return \MicroCMS\Domain\Book|throws an exception if no matching book is found
     */
    public function find($id) {
        $sql = "select * from book where book_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No book matching id " . $id);
    }

    /**
     * Return The author of the book.
     *
     * @param integer $bookId The book id.
     *
     * @return array The Author of the book
     */
    public function findAuthorByBook($bookId) {
        // The associated book is retrieved only once
        $book = $this->find($bookId);

        // book_id is not selected by the SQL query
        // The book won't be retrieved during domain object construction
        $sql = "select auth_id from book where book_id=?";
        $result = $this->getDb()->fetchAssoc($sql, array($bookId));

        // Convert query result to an array of domain objects
        $authorId = $result['auth_id'];
        $author = $this->authorDAO->find($authorId);
        return $author;
    }

    /**
     * Creates a Book object based on a DB row.
     *
     * @param array $row The DB row containing Book data.
     * @return \MyBooks\Domain\Book
     */
    protected function buildDomainObject(array $row) {
        $book = new Book();
        $book->setId($row['book_id']);
        $book->setTitle($row['book_title']);
        $book->setSummary($row['book_summary']);
        $book->setIsbn($row['book_isbn']);

        if (array_key_exists('auth_id', $row)) {
            // Find and set the associated author
            $authorId = $row['auth_id'];
            $author = $this->authorDAO->find($authorId);
            $book->setAuthor($author);
        }
        return $book;
    }
}