<?php
namespace App\Notification;

use App\Entity\Transaction;
use App\Entity\Transfert;
use App\Entity\UserClient;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use ElasticEmailClient\ElasticClient as Client;
use ElasticEmailClient\ApiConfiguration as Configuration;
use Exception;
use PhpParser\Node\Stmt\TryCatch;
use Swift_Mailer;
use Swift_Message;
use Twig\Environment;


class EmailNotification {
    /**
     * @var Environment
     */
    private $twig;
    private $client;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function confirmAccount(UserClient $client, $mailer) : void
    {
        
        $email = (new Swift_Message('Confirmation Account'))
            ->setFrom("leonbonou20@gmail.com")
            ->setTo($client->getEmail())
            ->setSubject("Confirmation Account")
            ->setBody(
                $this->twig->render('email/confirmAccount.html.twig', ['client'=> $client]),
                'text/plain'
            )
            ;
        $mailer->send($email);
    }

    public function transactionAlert(UserClient $client, Transaction $transaction) : void
    {
        $email = (new Email())
            ->from("leonbonou20@gmail.com")
            ->to($client->getEmail())
            ->subject("Transaction notifcation")
            ->html(
                $this->twig->render('email/transactionAlert.html.twig', ['client'=> $client, 'transaction'=>$transaction])

            )
        ;

        $this->mailer->send($email);
    }

    public function transfertAlert(UserClient $client, Transfert $transfert) : void
    {
        $email = (new Email())
            ->from("leonbonou20@gmail.com")
            ->to('anselmehotegni@gmail.com')
            ->subject("Transfert notifcation")
            ->html(
                $this->twig->render('email/transfertAlert.html.twig', ['client'=> $client, 'transfert'=>$transfert])
            )
        ;

        $this->mailer->send($email);
    }

}