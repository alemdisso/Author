<?php
class Author_Form_IllustratorChange extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('illustratorChangeForm')
            ->setAction('/admin/edition/change-illustrator')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $element = new Zend_Form_Element_Text('illustrator');
        $validator = new Moxca_Util_ValidString();
        $element->setLabel(_('#Illustrator:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => ''))
                ->addValidator($validator)
                ->addFilter('StringTrim');
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

            $editionObj->setIllustrator($data['illustrator']);

            $editionMapper->update($editionObj);
            return $editionObj;
        }
    }
 }