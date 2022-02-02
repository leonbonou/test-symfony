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
    private $client;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        /* $configuration = new Configuration([
            "apiUrl" => "https://api.elasticemail.com",  
            "apiKey" => "6ADB60E7B7683D9AB2C551E6B512CFFE3AF80B213CC128CA4E562DF30921833C87495BC239EE4D8CA6906005AD9F5CA5"
        ]);
        $this->client = new Client($configuration); */
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function confirmAccount(UserClient $client) : void
    {
       /*  try{
            $client->Email->Send(
                "Test",
                "leonbonou20@gmail.com",
                "anselmehotegni@gmail.com",
                "je suis le test"
            );
        } catch(Exception $e){
            throw new Exception($e);
        } */
        
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