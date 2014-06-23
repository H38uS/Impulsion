<?php

/**
 * 
 *
 * @author Jordan
 */
class Default_Form_LoginForm extends Zend_Form {

    public function init() {
        $username = $this->addElement(
                'text', 'username', array('filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(array('StringLength', false, array(3, 20)),),
            'required' => true,
            'label' => 'Login'));

        $password = $this->addElement(
                'password', 'password', array('filters' => array('StringTrim'), 'validators' => array(
                array('StringLength', false, array(6, 20))),
            'required' => true,
            'label' => 'Mot de passe'));

        $login = $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore' => true,
            'label' => 'Se connecter'));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')), 'Form'));
    }

}

?>
