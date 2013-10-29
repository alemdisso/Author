<?php
class Author_Form_SerieChange extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('serieChangeForm')
            ->setAction('/admin/edition/change-serie')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $element = new Zend_Form_Element_Select('serie');
        $element->setLabel('#Serie')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => 'change'))
                ->setRegisterInArrayValidator(false);
        $this->addElement($element);


        // create submit button
        $element = new Zend_Form_Element_Submit('submit');
        $element->setLabel('#Submit') //Gravar
               ->setDecorators(array('ViewHelper','Errors',
                    array(array('data' => 'HtmlTag'),
                    array('tag' => 'div','class' => '')),
                  ))
               ->setOptions(array('class' => ''));
        $this->addElement($element);



    }

    public function process($data) {

        if ($this->isValid($data) !== true) {
            throw new Author_Form_EditionCreateException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $editionMapper = new Author_Collection_EditionMapper($db);

            $editionId = $data['id'];
            $editionObj = $editionMapper->findById($editionId);

            $editionObj->setSerie($data['serie']);

            $editionMapper->update($editionObj);
            return $editionObj;
        }
    }
 }