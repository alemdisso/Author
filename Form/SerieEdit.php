<?php
class Author_Form_SerieEdit extends Author_Form_SerieCreate
{
    public function init()
    {
        parent::init();
        // initialize form
        $this->setName('editSerieForm')
            ->setAction('/admin/serie/edit')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $validator = new Moxca_Util_ValidGreaterThanZeroInteger;
        $element = new Zend_Form_Element_Select('serieEditor');
        $element->setLabel('#Editor')
                ->addValidator($validator)
                ->setRequired(true)
                ->addErrorMessage(_("#Editor is required"))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => 'choose'))
                ->setRegisterInArrayValidator(false);
        $this->addElement($element);



    }

    public function process($data) {

        if ($this->isValid($data) !== true) {
            throw new Author_Form_Exception('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $serieMapper = new Author_Collection_SerieMapper($db);
            $id = $data['id'];


            $serie = $serieMapper->findById($id);
            $serie->SetName($data['name']);
            $serie->SetEditor($data['serieEditor']);
            $serieMapper->update($serie);

            return $serie;
        }
    }
 }