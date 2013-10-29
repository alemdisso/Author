<?php
class Author_Form_DescriptionChange extends Zend_Form
{
    public function init()
    {
        parent::init();



        // initialize form
        $this->setName('descriptionChangeForm')
            ->setAction('/admin/work/change-description')
            ->setMethod('post');

        $element = new Zend_Form_Element_Hidden('id');
        $element->addValidator('Int')
            ->addFilter('StringTrim');
        $this->addElement($element);
        $element->setDecorators(array('ViewHelper'));

        $element = new Zend_Form_Element_Textarea('description');
        $element->setLabel('#Description:')
              ->setDecorators(array(
                  'ViewHelper',
                  'Errors',
                    array(array('data' => 'HtmlTag'), array('tagClass' => 'div', 'class' => 'input item_formulario')),
                    array('Label', array('tag' => 'div', 'tagClass' => 'labelAdmin')),
              ))
            ->setAttrib('rows','8')
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
            throw new Author_Form_WorkCreateException('Invalid data!');
        } else {
            $db = Zend_Registry::get('db');
            $workMapper = new Author_Collection_WorkMapper($db);

            $workId = $data['id'];
            $workObj = $workMapper->findById($workId);

            $workObj->setDescription($data['description']);

            $workMapper->update($workObj);
            return $workObj;
        }
    }
 }