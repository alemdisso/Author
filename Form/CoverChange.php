<?php
class Author_Form_CoverChange extends Zend_Form
{
    public function init()
    {
        parent::init();
        
        $this->getView();

        // initialize form
        $this->setName('coverChangeForm')
            ->setAction('/admin/edition/change-cover')
            ->setAttrib('enctype', 'multipart/form-data')
            //->setAction('javascript:callEditionCreate();')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $element = new Zend_Form_Element_File('cover');
        $element->setLabel(_('#Upload an image:'))
                ->setDestination(APPLICATION_PATH . '/../public/img/editions/raw');
        // ensure only 1 file
        $element->addValidator('Count', false, 1);
        $element->addValidator('Size', false, 5242880);
        // only JPEG, PNG, and GIFs
        $element->addValidator('Extension', false, 'jpg,png,gif,jpeg');
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
            $editionMapper = new Author_Collection_EditionMapper($db);

            $editionId = $data['id'];
            $edition = $editionMapper->findById($editionId);

            if (!$this->cover->receive()) {
                throw new Author_Form_EditionCreateException('Something wrong receiving cover file');
            } else {
                $newCoverFileName = $edition->getUri() . ".png";
                $formerCoverFilePath = $this->_view->coverFilePath($edition, "raw");
                
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/public' . $formerCoverFilePath)) {
                    unlink(['DOCUMENT_ROOT'] . '/public' . $formerCoverFilePath);
                }
                $formerCoverFilePath = $this->_view->coverFilePath($edition, "tb");
                
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/public' . $formerCoverFilePath)) {
                    unlink(['DOCUMENT_ROOT'] . '/public' . $formerCoverFilePath);
                }
                
                $formerCoverFilePath = $this->_view->coverFilePath($edition, "md");
                
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/public' . $formerCoverFilePath)) {
                    unlink(['DOCUMENT_ROOT'] . '/public' . $formerCoverFilePath);
                }
                                
                
                $fileName = strtolower(strrchr($this->cover->getFileName(), '/'));
                list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw' . $fileName);
                $this->resizeAndSave($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw'
                        , 198
                        , 198
                        , $_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/tb/'
                        , $newCoverFileName);
                
                if (($width > 380) || ($height > 380)) {
                    $this->resizeAndSave($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw' . $fileName
                            , 381
                            , 381
                            , $_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/md/'
                            , $newCoverFileName);

                } else {
                    $this->resizeAndSave($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw' . $fileName
                            , $width
                            , $height
                            , $_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/md/'
                            , $newCoverFileName);

                }

                $this->resizeAndSave($_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/raw' . $fileName
                        , $width
                        , $height
                        , $_SERVER['DOCUMENT_ROOT'] . '/public/img/editions/new/'
                        , $newCoverFileName);
                
            }
            
//            $location = $this->cover->getFileName();
//            $location = str_replace('\\', '/', $location);
//            $tmpArray = explode('/', $location);
            $edition->setCover($newCoverFileName);

            $editionMapper->update($edition);
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