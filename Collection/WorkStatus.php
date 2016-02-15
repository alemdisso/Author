<?php

class Author_Collection_WorkStatus {

    private $titles = array();


    public function __construct() {
        $this->titles = array(
            Author_Collection_WorkStatusConstants::STATUS_NIL      => _("#Nil"),
            Author_Collection_WorkStatusConstants::STATUS_RAW      => _("#Raw"),
            Author_Collection_WorkStatusConstants::STATUS_RESIZED  => _("#Resized"),
        );
    }

    public function TitleForStatus($status)
    {
            switch ($status) {
                case Author_Collection_WorkStatusConstants::STATUS_NIL:
                case Author_Collection_WorkStatusConstants::STATUS_RAW:
                case Author_Collection_WorkStatusConstants::STATUS_RESIZED:
                    return $this->titles[$status];
                    break;

                default:
                    return _("#Unknown status");
                    break;
            }
    }

    public function AllTitles($includeNull = false)
    {

        if ($includeNull) {
            return $this->titles;
        } else {
            $data = array();
            foreach ($this->titles as $k => $v) {
                if ($k != Author_Collection_WorkStatusConstants::STATUS_NIL) {
                    $data[$k] = $v;
                }
            }
            return($data);
        }
    }
}
