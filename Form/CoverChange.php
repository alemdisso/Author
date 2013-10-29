<?php
class Author_Form_CoverChange extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('coverChangeForm')
            ->setAction('/admin/edition/change-cover')
            ->setAttrib('enctype', 'multipart/form-data')
            //->setAction('javascript:callEditionCreate();')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $element = new Zend_Form_Element_File('cover');
        $element->setLabel(_('#Upload an image:'))
                ->setDestination(APPLICATION_PATH . '/../public/img/editions/raw');
        // ensure only 1 file
        $element->addValidator('Count', false, 1);
        $element->addValidator('Size', false, 5242880);
        // only JPEG, PNG, and GIFs
        $element->addValidator('Extension', false, 'jpg,png,gif,jpeg');
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
            $edition = $editionMapper->findById($editionId);

            if (!$this->cover->receive()) {
                throw new Author_Form_EditionCreateException('Something wrong receiving cover file');
            }
            $location = $this->cover->getFileName();
            $location = str_replace('\\', '/', $location);
            $tmpArray = explode('/', $location);
            $edition->setCover(end($tmpArray));

            $editionMapper->update($edition);
            return $edition;
        }
    }
 }