<?php

use PHPMailer\PHPMailer\PHPMailer;

require 'vendor/autoload.php';

class SingleMail
{
    public string $emailTo = "marty.clown@mail.ru";
    public string $message = "hello";
    public string $emailFrom = "marty.clown@mail.ru";
    public $inbox;
    const PASSWORD_INBOX = 'Mq4ZbiF5Ty01jX6XkGVv';

    public function __construct(string $emailTo, string $message)
    {
        if (!trim($emailTo) || !trim($message)) {
            throw new Exception("Недостаточно средств", 1);
        }

        $this->emailTo = $emailTo;
        $this->message = $message;

        try {
            $this->inbox = imap_open("{imap.mail.ru:993/imap/ssl}INBOX", $this->emailFrom, self::PASSWORD_INBOX);
        } catch (Exception $e) {
            'Cannot connect to Yandex: ' . $e;
        }
    }

    public function sendMessage() {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.mail.ru'; // Адрес вашего SMTP-сервера
            $mail->SMTPAuth = true;
            $mail->Username = $this->emailFrom; // Ваш логин от почтового ящика
            $mail->Password = self::PASSWORD_INBOX; // Ваш пароль
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            //Recipients
            $mail->setFrom($this->emailFrom, 'BeckkyWay'); // От кого
            $mail->addAddress($this->emailTo); // Кому

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Test_subject';
            $mail->Body = $this->message;
            echo "this";
            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function getMessage()
    {
        $msgnos = imap_search($this->inbox, 'ALL');
        foreach ($msgnos as $mail) {
            $headerInfo = imap_headerinfo($this->inbox, $mail);
            $output[] = [
                'date' => $headerInfo->date,
                'fromaddress ' => $headerInfo->from[0]->mailbox . "@" . $headerInfo->from[0]->host,
                'title' => $headerInfo->subject,
                'text' => imap_body($this->inbox, $mail, FT_PEEK),
            ];
        }

        var_dump($output);
    }
}

$sm = new SingleMail("marty.clown@mail.ru", '<p>мама папа я дружная свинья</p>');
$sm->SendMessage();
