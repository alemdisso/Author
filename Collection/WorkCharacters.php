<?php

class Author_Collection_WorkCharacters {

    protected $work;
    protected $characters;

    function __construct($id=0) {
        $this->id = (int)$id;
        $this->characters = array();
    }

    public function getAll() {
        return $this->characters;
    } //getAll

    public function addCharacter($id) {
        $this->characters[] = $id;

    } //addCharacter

}