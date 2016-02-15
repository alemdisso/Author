<?php
class Author_Form_EditionCreate extends Zend_Form
{
    public function init()
    {
        parent::init();
        // initialize form
        $this->setName('newEditionForm')
            ->setAction('/admin/edition/create')
            ->setAttrib('enctype', 'multipart/form-data')
            //->setAction('javascript:callEditionCreate();')
            ->setElementDecorators(array('FormElements',array('HtmlTag', array('tag' => 'div', 'class' => 'form')),'Form'))
            ->setMethod('post');

        $element = new Zend_Form_Element_Text('title');
        $titleValidator = new Moxca_Util_ValidTitle();
        $element->setLabel(_('#Title:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin clear_both')),
                ))
//                ->setOptions(array('class' => ''))
                ->setRequired(true)
                ->addErrorMessage(_("#Title is required"))
                ->addValidator($titleValidator)
                ->addFilter('StringTrim');
        $this->addElement($element);

        $element = new Zend_Form_Element_File('cover');
        $element->setLabel(_('#Upload an image:'))
                ->setDecorators(array(
                    'File',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setDestination($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw');
        // ensure only 1 file
        $element->addValidator('Count', false, 1);
        $element->addValidator('Size', false, 5242880);
        // only JPEG, PNG, and GIFs
        $element->addValidator('Extension', false, 'jpg,png,gif,jpeg');
        $this->addElement($element);

        $typesObj = new Author_Collection_WorkTypes();
        $titlesArray = $typesObj->allTitles();

        $element = new Zend_Form_Element_Radio('type');
	$element->setLabel('#Type')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'option inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
		->setMultiOptions($titlesArray)
                ->setOptions(array('class' => ''))
                ->setSeparator('');
        $this->addElement($element);

        $validator = new Moxca_Util_ValidGreaterThanZeroInteger;
        $element = new Zend_Form_Element_Select('editor');
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

        $element = new Zend_Form_Element_Textarea('summary');
        $element->setLabel('#Summary:')
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
              ))
            ->setAttrib('rows','3')
            ->setOptions(array('class' => ''))
            ->setRequired(false)
            ->addFilter('StringTrim');
        $this->addElement($element);

        $element = new Zend_Form_Element_Textarea('description');
        $element->setLabel('#Description:')
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
              ))
            ->setAttrib('rows','8')
            ->setOptions(array('class' => ''))
            ->setRequired(false)
            ->addFilter('StringTrim');
        $this->addElement($element);

        $element = new Zend_Form_Element_Select('serie');
        $element->setLabel('#Serie')
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->setOptions(array('class' => 'change'))
                ->setRegisterInArrayValidator(false);
        $this->addElement($element);

       $element = new Zend_Form_Element_Text('isbn');
        $validator = new Moxca_Util_ValidIsbn();
        $element->setLabel(_('#ISBN:'))
                ->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'inputAdmin')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
                ))
                ->addValidator($validator)
                ->addFilter('StringTrim');
        $this->addElement($element);

        $element = new Zend_Form_Element_Text('pages');
        $validator = new Moxca_Util_ValidPositiveInteger();
        $element->setLabel(_('#Pages:'))
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
//                    array('Label',
//                      array('tag' => 'div','tagClass' => '')
//                    ),
                  ))
               ->setOptions(array('class' => 'submit'));
        $this->addElement($element);



    }

    public function process($data) {

        if ($this->isValid($data) !== true) {
            throw new Author_Form_Exception('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $workMapper = new Author_Collection_WorkMapper($db);

            $work = new Author_Collection_Work();

            $work->SetTitle($data['title']);
            $work->SetType($data['type']);
            $work->SetDescription($data['description']);
            $work->SetSummary($data['summary']);

            if (!$this->cover->receive()) {
                throw new Author_Form_Exception('Something wrong receiving cover file');
            } else {
                
                $coverFileName = $work->getUri() . ".png";
                
                $fileName = strtolower(strrchr($this->cover->getFileName(), '/'));
                list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw' . $fileName);
                $this->resizeAndSave($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw'
                        , 198
                        , 198
                        , $_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/tb/'
                        , $coverFileName);
                
                if (($width > 380) || ($height > 380)) {
                    $this->resizeAndSave($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw' . $fileName
                            , 381
                            , 381
                            , $_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/md/'
                            , $coverFileName);

                } else {
                    $this->resizeAndSave($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw' . $fileName
                            , $width
                            , $height
                            , $_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/md/'
                            , $coverFileName);

                }

                $this->resizeAndSave($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw' . $fileName
                        , $width
                        , $height
                        , $_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/new/'
                        , $coverFileName);
                
            }

            $workMapper->insert($work);

            $editionMapper = new Author_Collection_EditionMapper($db);
            $edition = new Author_Collection_Edition($work->getId(), $data['editor']);
            $edition->SetTitle($data['title']);
            $edition->setIsbn($data['isbn']);
            $edition->setSerie($data['serie']);
            $edition->setPages($data['pages']);
            $edition->setIllustrator($data['illustrator']);
            $edition->setCoverDesigner($data['coverDesigner']);
            $edition->setCountry('BR');

//            $location = $this->cover->getFileName();
//            $location = str_replace('\\', '/', $location);
//            $tmpArray = explode('/', $location);
//            $coverFileName = end($tmpArray);
//            if ($coverFileName != "") {
//                $edition->setCover($coverFileName);
//            }
                $edition->setCover($coverFileName);

            $editionMapper->insert($edition);

            return $edition;
        }
    }
    
    private function resizeAndSave($rawImageFilePath, $width, $height, $whereToSave, $newImageFilename="")
    {

        $rsz = new Moxca_Util_Resize($rawImageFilePath);
        $rsz->resizeImage($width, $height);
        $rsz->saveImage($whereToSave . '/' . $newImageFilename);
        unset($rsz);
        
    }
    
    
    
 }