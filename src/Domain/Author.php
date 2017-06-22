<?php

namespace MyBooks\Domain;

class Author 
{
    /**
     * Author id.
     *
     * @var integer
     */
    private $id;

    /**
     * Author FirstName + Author LastName.
     *
     * @var string
     */
    private $author;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function setAuthor($authorFirstName, $authorLastName) {
        $this->author = $authorFirstName + ' ' + $authorLastName;
        return $this;
    }

}