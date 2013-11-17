<?php

/**
 *
 * @author Jordan Mosio
 */
class LiensController extends Zend_Controller_Action 
{    
    protected $_sendto = array("laura" => "lauramosio@hotmail.fr", "anita" => "anitapetrowitch@hotmail.fr", "jordan" => "jordan.mosio@hotmail.fr");
    protected $_names = array("laura" => "Laura Mosio", "anita" => "Anita Ptrowitch", "jordan" => "Jordan Mosio");
    
    public function indexAction() {
        
    }
        
    public function sendemailAction() {
        $mailaddress = $this->getRequest()->getPost("mailaddress");
        $firstname = $this->getRequest()->getPost("firstname");
        $surname = $this->getRequest()->getPost("surname");
        $subject = $this->getRequest()->getPost("subject");
        $message = $this->getRequest()->getPost("message");

        if ($mailaddress == "" || $firstname == "" || $surname == ""
                || $subject == "" || $message == "") {
            $this->_redirect('/liens/fail');
        }

        $key = $this->getRequest()->getPost("sendto");
        if (array_key_exists($key, $this->_sendto)) {
            $mail = new Zend_Mail('UTF-8');
            $mail->setBodyText($message)
                    ->setFrom($mailaddress, $firstname . ' ' . $surname)
                    ->addTo($this->_sendto[$key], $this->_names[$key])
                    ->setSubject($subject);
            $sent = $mail->send();
            if ($mail->send()) {
                $this->_redirect('/liens/successful');
            } else {
            $this->_redirect('/index');
                $this->_redirect('/liens/fail');
            }
        } else {
            $this->_redirect('/liens/fail/' . $key . array_key_exists($key, $this->_sendto));
        }
    }
    
    public function successfulAction() {
        
    }

    public function failAction() {
        
    }
}

?>
