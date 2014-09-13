<?php
class Author_Form_SerieCreate extends Zend_Form
{
    public function init()
    {
        parent::init();

        // initialize form
        $this->setName('newSerieForm')
            ->setAction("javascript:submitSerieForm();")
//            ->setAction('/admin/serie/create')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('serieEditor');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

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
        $element = new Zend_Form_Element_Submit('submitSerie');
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



            $serieMapper = new Author_Collection_SerieMapper($db);
            $serie = new Author_Collection_Serie();
            $serie->SetName($data['name']);
            $serie->SetEditor($data['serieEditor']);
            $serie->SetCountry('BR');

            $serieMapper->insert($serie);

            return $serie;
        }
    }
 }