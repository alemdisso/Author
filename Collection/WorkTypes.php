<?php

class Author_Collection_WorkTypes {

    private $titles = array();


    public function __construct() {
        $this->titles = array(
            Author_Collection_WorkTypeConstants::TYPE_NIL      => _("#Nil"),
            Author_Collection_WorkTypeConstants::TYPE_CHILDREN => _("#Children"),
            Author_Collection_WorkTypeConstants::TYPE_YOUNG    => _("#Young"),
            Author_Collection_WorkTypeConstants::TYPE_FICTION  => _("#Fiction"),
            Author_Collection_WorkTypeConstants::TYPE_ESSAY    => _("#Essay"),
        );
    }

    public function TitleForType($type)
    {
            switch ($type) {
                case Author_Collection_WorkTypeConstants::TYPE_NIL:
                case Author_Collection_WorkTypeConstants::TYPE_CHILDREN:
                case Author_Collection_WorkTypeConstants::TYPE_YOUNG:
                case Author_Collection_WorkTypeConstants::TYPE_FICTION:
                case Author_Collection_WorkTypeConstants::TYPE_ESSAY:
                    return $this->titles[$type];
                    break;

                default:
                    return _("#Unknown type");
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
                if ($k != Author_Collection_WorkTypeConstants::TYPE_NIL) {
                    $data[$k] = $v;
                }
            }
            return($data);
        }
    }
}