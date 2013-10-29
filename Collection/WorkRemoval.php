<?php

class Author_Collection_WorkRemoval {

    private $work;
    private $workMapper;

    public function __construct(Author_Collection_Work $work, Author_Collection_WorkMapper $workMapper)
    {
        $this->work = $work;
        $this->workMapper = $workMapper;
    }

    public function canBeRemoved()
    {
        $can=true;
        return $can;

    }

    public function remove()
    {
        //check if can really remove
        if ($this->canBeRemoved()) {

            // delete work
            $this->workMapper->delete($this->work);

        } else {
            throw new Author_Collection_WorkException("This work can't be removed");
        }



    }

}
