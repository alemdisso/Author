<?php

class Author_Collection_Prize {

    protected $id;
    protected $work;
    protected $prizeName;
    protected $institutionName;
    protected $categoryName;
    protected $year;

    function __construct($work, $id=0) {
        $this->id = (int)$id;
        $this->work = $work;
        $this->prizeName = null;
        $this->institutionName = null;
        $this->categoryName = null;
        $this->year = null;
    }

    public function getId() {
        return $this->id;
    } //getId

    public function setId($id) {
        if (($this->id == 0) && ($id > 0)) {
            $this->id = (int)$id;
        } else {
            throw new Author_Collection_PrizeException('It\'s not possible to change a prize\'s ID');
        }

    } //SetId

    public function getWork()
    {
        return $this->work;
    } //getWork

    public function getPrizeName()
    {
        return $this->prizeName;
    } //getName

    public function setPrizeName($prizeName)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($prizeName)) {
            if ($this->prizeName != $prizeName) {
                $this->prizeName = $prizeName;
            }
        } else {
            throw new Author_Collection_WorkException("This ($prizeName) is not a valid prizeName.");
        }
    } //SetPrizeName


    public function getInstitutionName()
    {
        return $this->institutionName;
    } //getInstitutionName

    public function setInstitutionName($institutionName)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($institutionName)) {
            if ($this->institutionName != $institutionName) {
                $this->institutionName = $institutionName;
            }
        } else {
            throw new Author_Collection_WorkException("This ($institutionName) is not a valid institutionName.");
        }

    } //SetInstitutionName

    public function getCategoryName()
    {
        return $this->categoryName;
    } //getCategoryName

    public function setCategoryName($categoryName)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($categoryName)) {
            if ($this->categoryName != $categoryName) {
                $this->categoryName = $categoryName;
            }
        } else {
            throw new Author_Collection_WorkException("This ($categoryName) is not a valid categoryName.");
        }

    } //SetCategoryName

    public function getYear()
    {
        return $this->year;
    } //getYear

    public function setYear($year)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($year)) {
            if ($this->year != $year) {
                $this->year = $year;
            }
        } else {
            throw new Author_Collection_WorkException("This ($year) is not a valid year.");
        }

    } //SetYear


}