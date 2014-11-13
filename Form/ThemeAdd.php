<?php
class Author_Form_ThemeAdd extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('themeAddForm')
            ->setAction('/admin/work/create-theme')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $mapper = new Author_Collection_TaxonomyMapper();
        $rawLabelsArray = $mapper->getAllThemesAlphabeticallyOrdered();

        $view = new Zend_View();
        $themesArray = array("0" => $view->translate("#(choose)"));

        foreach($rawLabelsArray as $k => $tagArray) {
            $themesArray[$k] = $tagArray['term'];
        }


        $element = new Zend_Form_Element_Select('existingTheme');
        $element->setLabel('#Themes')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
		->setMultiOptions($themesArray)
                ->setOptions(array('class' => 'choose'))
                ->setRegisterInArrayValidator(false);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('newTheme');
        $validator = new Moxca_Util_ValidString();
        $element->setLabel(_('#New theme:'))
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
            throw new Author_Form_Exception('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $workMapper = new Author_Collection_WorkMapper($db);

            $workId = $data['id'];
            $workObj = $workMapper->findById($workId);

            if ($data['existingTheme'] > 0) {
                $workObj->addTheme($data['existingTheme']);

            } else if ($data['newTheme'] != "") {
                $taxonomyMapper = new Author_Collection_TaxonomyMapper($db);
                $termId = $taxonomyMapper->findTermAndInsertIfNew($data['newTheme']);
                //die("vou add theme $termId");
                $workObj->addTheme($termId);
            }

            $workMapper->update($workObj);
            return $workObj;
        }
    }
 }