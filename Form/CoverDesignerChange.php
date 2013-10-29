<?php
class Author_Form_CoverDesignerChange extends Zend_Form
{
    public function init()
    {
        parent::init();



        // initialize form
        $this->setName('coverDesignerChangeForm')
            ->setAction('/admin/edition/change-cover-designer')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $element = new Zend_Form_Element_Text('coverDesigner');
        $validator = new Moxca_Util_ValidString();
        $element->setLabel(_('#Cover designer:'))
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

            $editionObj->setCoverDesigner($data['coverDesigner']);

            $editionMapper->update($editionObj);
            return $editionObj;
        }
    }
 }