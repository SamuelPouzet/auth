<?php

namespace SamuelPouzet\Auth\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailerService
{
    protected ?string $configName = null;

    public function __construct(protected PHPMailer $email, protected array $config)
    {
    }

    //recipients
    public function setFrom(string $address, string $name = null): MailerService
    {
        $this->email->setFrom($address, $name);
        return $this;
    }

    public function setTo(string $address, string $name = null): MailerService
    {
        $this->email->addAddress($address, $name);
        return $this;
    }

    public function setReplyTo(string $address, string $name = null): MailerService
    {
        $this->email->addReplyTo($address, $name);
        return $this;
    }

    public function setCC(string $address, string $name = null): MailerService
    {
        $this->email->addCC($address, $name);
        return $this;
    }

    public function setBCC(string $address, string $name = null): MailerService
    {
        $this->email->addBCC($address, $name);
        return $this;
    }

    //Attachments
    public function addAttachment(string $path, string $name = null): MailerService
    {
        $this->email->addAttachment($path, $name);
        return $this;
    }

    //content
    public function setSubject(string $subject): MailerService
    {
        $this->email->Subject = $subject;
        return $this;
    }

    public function setBody(string $body): MailerService
    {
        $this->email->Body = $body;
        return $this;
    }

    public function setAltBody(string $altBody): MailerService
    {
        $this->email->AltBody = $altBody;
        return $this;
    }

    protected function configureServer(): void
    {
        $config = $this->config[$this->configName]['options'];

        if (isset($config['protocol']) && $config['protocol'] === 'smtp') {
            $this->email->SMTPDebug = $config['debug'];
            $this->email->isSMTP();
            $this->email->SMTPAuth   = $config['smtp_auth'];
            $this->email->SMTPSecure = $config['secure'];
            $this->email->SMTPOptions = $config['options'];
        }



        $this->email->isHTML();
        $this->email->Host       = $config['host'];
        $this->email->Username   = $config['user'];
        $this->email->Password   = $config['password'];
        $this->email->Port       = $config['port'];
    }

    public function send(string $configName = 'default'): void
    {
        try {
            if ($this->configName !== $configName) {
                $this->configName = $configName;
                $this->configureServer();
            }
            $this->email->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->email->ErrorInfo}";
        }

    }
}
