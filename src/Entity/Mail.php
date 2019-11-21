<?php

namespace App\Entity;
class Mail{


  private $host = 'smtp.allotraining.com';
  private $port = 587;
  private $user = 'orelien.kamga@gmail.com';
  private $pass = 'Sexiumsy3.0';
  private $encrypt = 'tls';

function sendEmail($titre,$destinataire,$message){
        
// Create the Transport
$transport = (new \Swift_SmtpTransport('smtp.googlemail.com', 465, 'ssl'))
  ->setUsername($this->user)
  ->setPassword($this->pass)
;
 
// Create the Mailer using your created Transport
$mailer = new \Swift_Mailer($transport);
 
// Create a message
$body = 'Hello, <p>Email sent through <span style="color:red;">Swift Mailer</span>.</p>';
 
$message = (new \Swift_Message('Email Through Swift Mailer'))
  ->setFrom([$this->user => 'Kamga Orelien'])
  ->setTo([$destinataire])
  ->setBody($message)
  ->setContentType('text/html')
;
 
// Send the message
$mailer->send($message);

    }

}