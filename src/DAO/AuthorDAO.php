<?php

namespace MyBooks\DAO;

use MyBooks\Domain\Author;

class AuthorDAO extends DAO 
{
    /**
     * @var \MyBooks\DAO\BookDAO
     */
    private $bookDAO;

    public function setBookDAO(BookDAO $bookDAO) {
        $this->bookDAO = $bookDAO;
    }

    /**
     * Return a list of all comments for an article, sorted by date (most recent last).
     *
     * @param integer $articleId The article id.
     *
     * @return array A list of all comments for the article.
     */
    public function findAuthorByBook($bookId) {
        // The associated book is retrieved only once
        $book = $this->bookDAO->find($bookId);

        // book_id is not selected by the SQL query
        // The book won't be retrieved during domain objet construction
        $sql = "select auth_id, auth_first_name, auth_last_name from author where book_id=?";
        $result = $this->getDb()->fetchAll($sql, array($articleId));

        // Convert query result to an array of domain objects
        $comments = array();
        foreach ($result as $row) {
            $comId = $row['com_id'];
            $comment = $this->buildDomainObject($row);
            // The associated article is defined for the constructed comment
            $comment->setArticle($article);
            $comments[$comId] = $comment;
        }
        return $comments;
    }

    /**
     * Creates an Comment object based on a DB row.
     *
     * @param array $row The DB row containing Comment data.
     * @return \MicroCMS\Domain\Comment
     */
    protected function buildDomainObject(array $row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);
        $comment->setAuthor($row['com_author']);
        
        return $comment;
    }
}