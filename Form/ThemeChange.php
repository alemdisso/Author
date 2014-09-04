<?php
class Author_Form_ThemeChange extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('themeChangeForm')
            ->setAction('/admin/work/change-theme')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $mapper = new Author_Collection_TaxonomyMapper();
        $rawLabelsArray = $mapper->getAllThemesAlphabeticallyOrdered();

        $view = new Zend_View();
        $labelsArray = array("0" => $view->translate("#(choose)"));

        foreach($rawLabelsArray as $k => $themeData) {
            $labelsArray[$k] = $themeData['term'];
        }


        $element = new Zend_Form_Element_Select('theme');
        $element->setLabel('#Theme')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
		->setMultiOptions($labelsArray)
                ->setOptions(array('class' => 'choose'))
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
            throw new Author_Form_WorkCreateException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $workMapper = new Author_Collection_WorkMapper($db);

            $workId = $data['id'];
            $workObj = $workMapper->findById($workId);

            $workObj->setTheme($data['theme']);

            $workMapper->update($workObj);
            return $workObj;
        }
    }
 }
