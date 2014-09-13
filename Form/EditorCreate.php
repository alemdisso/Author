<?php
class Author_Form_EditorCreate extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('newEditorForm')
            ->setAction('javascript:submitEditorForm();')
            ->setMethod('post');

        $element = new Zend_Form_Element_Text('name');
        $titleValidator = new Moxca_Util_ValidTitle();
        $element->setLabel(_('#Name:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => ''))
                ->setRequired(true)
                ->addErrorMessage(_("#Name is required"))
                ->addValidator($titleValidator)
                ->addFilter('StringTrim');
        $this->addElement($element);


        // create submit button
        $element = new Zend_Form_Element_Submit('submitEditor');
        $element->setLabel('#Submit') //Gravar
               ->setDecorators(array('ViewHelper','Errors',
                    array(array('data' => 'HtmlTag'),
                    array('tag' => 'div','class' => '')),
                  ))
               ->setOptions(array('class' => 'submit'));
        $this->addElement($element);



    }

    public function process($data) {

        if ($this->isValid($data) !== true) {
            throw new Author_Form_Exception('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');

            $editorMapper = new Author_Collection_EditorMapper($db);
            $editor = new Author_Collection_Editor();
            $editor->SetName($data['name']);
            $editor->SetCountry('BR');

            $editorMapper->insert($editor);

            return $editor;
        }
    }
 }