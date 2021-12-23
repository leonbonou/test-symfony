<?php
namespace App\Notification;

use App\Entity\Transaction;
use App\Entity\Transfert;
use App\Entity\UserClient;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailNotification {

    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(MailerInterface $mailer , Environment $twig)
    {

        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function confirmAccount(UserClient $client) : void
    {
        $email = (new Email())
            ->from("leonbonou20@gmail.com")
            ->to($client->getEmail())
            ->subject("Confirmation Account")
            ->html(
                $this->twig->render('email/confirmAccount.html.twig', ['client'=> $client])
            )
        ;

        $this->mailer->send($email);
    }

    public function transactionAlert(UserClient $client, Transaction $transaction) : void
    {
        $email = (new Email())
            ->from("leonbonou20@gmail.com")
            /*->to($client->getEmail())*/
            ->to('anselmehotegni@gmail.com')
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