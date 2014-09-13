<?php

class Author_Collection_Edition {

    protected $id;
    protected $work;
    protected $title;
    protected $prefix;
    protected $uri;
    protected $editor;
    protected $country;
    protected $serie;
    protected $pages;
    protected $cover;
    protected $isbn;
    protected $illustrator;
    protected $coverDesigner;

    function __construct($work, $editor, $id=0) {
        $this->id = (int)$id;
        $this->work = $work;
        $this->title = null;
        $this->prefix = "";
        $this->uri = null;
        $this->editor = $editor;
        $this->country = 'BR';
        $this->pages = null;
        $this->coverImageFileName = null;
        $this->isbn = null;
        $this->illustrator = null;
        $this->coverDesigner = null;
    }

    public function getId() {
        return $this->id;
    } //getId

    public function setId($id) {
        if (($this->id == 0) && ($id > 0)) {
            $this->id = (int)$id;
        } else {
            throw new Author_Collection_EditionException('It\'s not possible to change a edition\'s ID');
        }

    } //SetId

    public function getEditor()
    {
        return $this->editor;
    } //getName

    public function setEditor($editor)
    {
        $validator = new Moxca_Util_ValidPositiveDecimal();
        if (($validator->isValid($editor)) || ($editor == "")) {
            if ($this->editor != $editor) {
                $this->editor = $editor;
            }
        } else {
            throw new Author_Collection_WorkException("This ($editor) is not a valid editor.");
        }
    } //SetEditor



    public function getPrefix()
    {
        return $this->prefix;
    } //getPrefix

    public function getWork()
    {
        return $this->work;
    } //getWork

    public function getTitle($raw = false)
    {
        if (($this->prefix) && (!$raw)) {
            return $this->prefix . " " . $this->title;

        } else {
            return $this->title;
        }
    } //getTitle

    public function setTitle($title)
    {
        $prefix = "";
        $validator = new Moxca_Util_ValidString();
        if ($validator->isValid($title)) {
            if ($this->title != $title) {
                $words = explode(" ", $title);
                if (count($words) > 1) {
                    $first = $words[0];

                    switch(strtolower($first)) {
                        case "o":
                        case "os":
                        case "a":
                        case "as":
                        case "um":
                        case "uns":
                        case "uma":
                        case "umas":
                            unset($words[0]);
                            $prefix = $first;
                            break;

                        default:
                            break;
                    }

                    $title = implode(" ", $words);
                }

                $this->title = $title;
                $this->prefix = $prefix;

                $converter = new Moxca_Util_StringToAscii();
                $this->uri = $converter->toAscii($this->getTitle());
            }
        } else {
            throw new Author_Collection_EditionException("This ($title) is not a valid title.");
        }

    } //SetTitle

    public function getUri()
    {
        return $this->uri;
    } //getUri

    public function getSerie()
    {
        return $this->serie;
    } //getSerie

    public function setSerie($serie)
    {
        $validator = new Moxca_Util_ValidPositiveDecimal();
        if (($validator->isValid($serie)) || ($serie == "")) {
            if ($this->serie != $serie) {
                $this->serie = $serie;
            }
        } else {
            throw new Author_Collection_WorkException("This ($serie) is not a valid serie.");
        }
    } //SetSerie

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

    public function getPages()
    {
        return $this->pages;
    } //getPages

    public function setPages($pages)
    {
        $validator = new Moxca_Util_ValidPositiveDecimal();
        if (($validator->isValid($pages)) || ($pages == "")) {
            if ($this->pages != $pages) {
                $this->pages = $pages;
            }
        } else {
            throw new Author_Collection_WorkException("This ($pages) is not a valid pages number.");
        }
    } //SetPages

    public function getCover()
    {
        return $this->cover;
    } //getCover

    public function setCover($cover)
    {
        $validator = new Moxca_Util_ValidFilename();
        if (($validator->isValid($cover)) || ($cover == "")) {
            if ($this->cover != $cover) {
                $this->cover = $cover;
            }
        } else {
            throw new Author_Collection_WorkException("This ($cover) is not a valid cover filename.");
        }
    } //SetCover

    public function getIsbn()
    {
        return $this->isbn;
    } //getIsbn

    public function setIsbn($isbn)
    {
        $validator = new Moxca_Util_ValidString();
        if (($validator->isValid($isbn)) || ($isbn == "")) {
            if ($this->isbn != $isbn) {
                $this->isbn = $isbn;
            }
        } else {
            throw new Author_Collection_WorkException("This ($isbn) is not a valid isbn.");
        }
    } //SetIsbn

    public function getIllustrator()
    {
        return $this->illustrator;
    } //getIllustrator

    public function setIllustrator($illustrator)
    {
        $validator = new Moxca_Util_ValidString();
        if (($validator->isValid($illustrator)) || ($illustrator == "")) {
            if ($this->illustrator != $illustrator) {
                $this->illustrator = $illustrator;
            }
        } else {
            throw new Author_Collection_WorkException("This ($illustrator) is not a valid illustrator.");
        }

    } //SetIllustrator

    public function getCoverDesigner()
    {
        return $this->coverDesigner;
    } //getCoverDesigner

    public function setCoverDesigner($coverDesigner)
    {
        $validator = new Moxca_Util_ValidString();
        if (($validator->isValid($coverDesigner)) || ($coverDesigner == "")) {
            if ($this->coverDesigner != $coverDesigner) {
                $this->coverDesigner = $coverDesigner;
            }
        } else {
            throw new Author_Collection_WorkException("This ($coverDesigner) is not a valid coverDesigner.");
        }
    } //SetCoverDesigner

}