<?php
class Author_Form_PrizeEdit extends Author_Form_PrizeCreate
{
    public function init()
    {
        parent::init();
        // initialize form
        $this->setName('editPrizeForm')
            ->setAction('/admin/prize/edit')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

    }

    public function process($data) {

        if ($this->isValid($data) !== true) {
            throw new Author_Form_EditionCreateException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $prizeMapper = new Author_Collection_PrizeMapper($db);
            $id = $data['id'];
            $prize = $prizeMapper->findById($id);

            $prize->SetPrizeName($data['prize']);
            $prize->SetInstitutionName($data['institution']);
            $prize->SetCategoryName($data['category']);
            $prize->SetYear($data['year']);
            $prizeMapper->update($prize);

            return $prize;
        }
    }
 }