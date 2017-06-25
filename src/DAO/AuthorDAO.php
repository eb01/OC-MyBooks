<?php

namespace MyBooks\DAO;

use MyBooks\Domain\Author;

class AuthorDAO extends DAO 
{
    /**
     * Returns an author matching the supplied id.
     *
     * @param integer $id
     *
     * @return \MicroCMS\Domain\Author|throws an exception if no matching author is found
     */

    public function find($id) {
        $sql = "select * from author where auth_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No author matching id " . $id);
    }

    /**
     * Creates an Author object based on a DB row.
     *
     * @param array $row The DB row containing Author data.
     * @return \MyBooks\Domain\Author
     */
    protected function buildDomainObject(array $row) {
        $author = new Author();
        $author->setId($row['auth_id']);
        $author->setAuthor($row['auth_first_name'], $row['auth_last_name']);
        return $author;
    }
}