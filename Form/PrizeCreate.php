<?php
class Author_Form_PrizeCreate extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('newPrizeForm')
            ->setAction('/admin/prize/create')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('work');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $element = new Zend_Form_Element_Text('prize');
        $titleValidator = new Moxca_Util_ValidTitle();
        $element->setLabel(_('#Prize:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => ''))
                ->setRequired(true)
                ->addErrorMessage(_("#Title is required"))
                ->addValidator($titleValidator)
                ->addFilter('StringTrim');
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('institution');
        $titleValidator = new Moxca_Util_ValidTitle();
        $element->setLabel(_('#Institution:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => ''))
                ->addValidator($titleValidator)
                ->addFilter('StringTrim');
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('category');
        $titleValidator = new Moxca_Util_ValidTitle();
        $element->setLabel(_('#Category:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => ''))
                ->addValidator($titleValidator)
                ->addFilter('StringTrim');
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('year');
        $titleValidator = new Moxca_Util_ValidTitle();
        $element->setLabel(_('#Year:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => ''))
                ->addValidator($titleValidator)
                ->addFilter('StringTrim');
        $this->addElement($element);

        // create submit button
        $element = new Zend_Form_Element_Submit('submit');
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
            $prizeMapper = new Author_Collection_PrizeMapper($db);

            $prize = new Author_Collection_Prize($data['work']);
            $prize->SetPrizeName($data['prize']);
            $prize->SetInstitutionName($data['institution']);
            $prize->SetCategoryName($data['category']);
            $prize->SetYear($data['year']);
            $prizeMapper->insert($prize);

            return $prize;
        }
    }
 }