<?php
class Author_Form_SummaryChange extends Zend_Form
{
    public function init()
    {
        parent::init();



        // initialize form
        $this->setName('summaryChangeForm')
            ->setAction('/admin/work/change-summary')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $element = new Zend_Form_Element_Textarea('summary');
        $element->setLabel('#Summary:')
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'input item_formulario')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
              ))
            ->setAttrib('rows','3')
            ->setOptions(array('class' => ''))
            ->setRequired(false)
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

            $workObj->setSummary($data['summary']);

            $workMapper->update($workObj);
            return $workObj;
        }
    }
 }