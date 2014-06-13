<?php
class Author_Form_CharacterAdd extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('characterAddForm')
            ->setAction('/admin/work/create-character')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $mapper = new Author_Collection_TaxonomyMapper();
        $rawLabelsArray = $mapper->getAllCharactersAlphabeticallyOrdered();

        $view = new Zend_View();
        $charactersArray = array("0" => $view->translate("#(choose)"));

        foreach($rawLabelsArray as $k => $name) {
            $charactersArray[$k] = $name;
        }


        $element = new Zend_Form_Element_Select('existingCharacter');
        $element->setLabel('#Characters')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
		->setMultiOptions($charactersArray)
                ->setOptions(array('class' => 'choose'))
                ->setRegisterInArrayValidator(false);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('newCharacter');
        $validator = new Moxca_Util_ValidString();
        $element->setLabel(_('#New character:'))
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
            throw new Author_Form_WorkCreateException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $workMapper = new Author_Collection_WorkMapper($db);

            $workId = $data['id'];
            $workObj = $workMapper->findById($workId);

            $workObj->addCharacter($data['existingCharacter']);

            $workMapper->update($workObj);
            return $workObj;
        }
    }
 }