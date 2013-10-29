<?php

class Author_Collection_Serie {

    protected $id;
    protected $name;
    protected $editor;
    protected $uri;
    protected $country;

    function __construct($id=0) {
        $this->id = (int)$id;
        $this->name = null;
        $this->editor = null;
        $this->uri = null;
        $this->country = null;
    }

    public function getId() {
        return $this->id;
    } //getId

    public function setId($id) {
        if (($this->id == 0) && ($id > 0)) {
            $this->id = (int)$id;
        } else {
            throw new Author_Collection_SerieException('It\'s not possible to change a serie\'s ID');
        }
    } //SetId

    public function getCountry()
    {
        return $this->country;
    } //getCountry

    public function setCountry($country)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($country)) {
            if ($this->country != $country) {
                $this->country = $country;
            }
        } else {
            throw new Author_Collection_WorkException("This ($country) is not a valid country.");
        }

    } //SetCountry

    public function getEditor()
    {
        return $this->editor;
    } //getEditor

    public function setEditor($editor)
    {
        $validator = new Moxca_Util_ValidPositiveDecimal();
        if ($validator->isValid($editor)) {
            if ($this->editor != $editor) {
                $this->editor = $editor;
            }
        } else {
            throw new Author_Collection_WorkException("This ($editor) is not a valid editor.");
        }

    } //SetEditor

    public function getName()
    {
        return $this->name;
    } //getName

    public function setName($name)
    {
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($name)) {
            if ($this->name != $name) {
                $this->name = $name;
                $converter = new Moxca_Util_StringToAscii();
                $this->uri = $converter->toAscii($this->name);
            }
        } else {
            throw new Author_Collection_SerieException("This ($name) is not a valid name.");
        }

    } //SetName

    public function getUri()
    {
        return $this->uri;
    } //getUri


}
