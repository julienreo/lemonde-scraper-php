<?php

Class Mailer {

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * Mailer config
     * 
     * @param array $config
     */
    public function __construct(array $config) {
        $ssl = $config['ssl'] === true ? 'ssl' : null;

        try {
            $transport = (new Swift_SmtpTransport($config['smtp'], $config['port'], $ssl))
                ->setUsername($config['username'])
                ->setPassword($config['password']);
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());
        }

        $this->mailer = new Swift_Mailer($transport);
    }

    /**
     * Send data
     * 
     * @param array $data
     */
    public function send(array $data) {
        $mailer = $this->mailer;

        try {
            $message = (new Swift_Message())
                ->setTo($data['to'])
                ->setFrom($data['from'])
                ->setSubject($data['subject'])        
                ->addPart($data['body'], 'text/html');
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());
        }

        $mailer->send($message);
    }
}