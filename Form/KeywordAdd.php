<?php
class Author_Form_KeywordAdd extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('keywordAddForm')
            ->setAction('/admin/work/create-keyword')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $mapper = new Author_Collection_TaxonomyMapper();
        $rawLabelsArray = $mapper->getAllWorksKeywordsAlphabeticallyOrdered();

        $view = new Zend_View();
        $keywordsArray = array("0" => $view->translate("#(choose)"));

        foreach($rawLabelsArray as $k => $tagArray) {
            $keywordsArray[$k] = $tagArray['term'];
        }


        $element = new Zend_Form_Element_Select('existingKeyword');
        $element->setLabel('#Keywords')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
		->setMultiOptions($keywordsArray)
                ->setOptions(array('class' => 'choose'))
                ->setRegisterInArrayValidator(false);
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('newKeyword');
        $validator = new Moxca_Util_ValidString();
        $element->setLabel(_('#New keyword:'))
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

            if ($data['existingKeyword'] > 0) {
                $workObj->addKeyword($data['existingKeyword']);

            } else if ($data['newKeyword'] != "") {
                $taxonomyMapper = new Author_Collection_TaxonomyMapper($db);

                $keywords = preg_split( "/(,|;|\|)/", $data['newKeyword'] );

                foreach ($keywords as $eachKeyword) {
                    $eachKeyword = trim($eachKeyword);
                    $termId = $taxonomyMapper->findTermAndInsertIfNew($eachKeyword);
                    $workObj->addKeyword($termId);
                }
            }

            $workMapper->update($workObj);
            return $workObj;
        }
    }
 }