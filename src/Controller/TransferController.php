<?php 

namespace App\Controller;

use App\Entity\Operation;
use App\Entity\Transaction;
use App\Entity\Transfert;
use App\Form\TransfertType;
use App\Functions\MyFunction;
use App\Notification\EmailNotification;
use App\Repository\TransactionRepository;
use App\Repository\TransfertRepository;
use App\Services\AuthService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("account/transfer")
 * Class TransferController
 * @package App\Controller
 */
class TransferController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var MyFunction
     */
    private $functions;
    /**
     * @var EmailNotification
     */
    private $emailNotification;

    /**
     * TransferController constructor.
     * @param Security $security
     * @param MyFunction $functions
     * @param EmailNotification $emailNotification
     */
    public function __construct(Security $security, MyFunction $functions, EmailNotification $emailNotification)
    {
        $this->security = $security;
        $this->functions = $functions;
        $this->emailNotification = $emailNotification;
    }

    /**
     * @Route("/", name="account_transfer")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function transfer(Request $request) {
        if($this->security->getUser()->getEmailToken()) {
            return $this->redirectToRoute('email_error');
        }
        $new_transfert = new Transfert();
        $form = $this->createForm(TransfertType::class, $new_transfert);
        $form->handleRequest($request);
        $operation = new Operation();

        if($form->isSubmitted() && $form->isValid()) {
            if((float)$new_transfert->getAmount() > $this->functions->getAmount()) {
                $this->addFlash('danger', "Vous n'avez pas suffisamment de fond");
                return $this->redirectToRoute('account_transfer');
            }
            $new_transfert->setUserClient($this->security->getUser());
            $this->getDoctrine()->getManager()->persist($new_transfert);
            $this->addFlash('success', "Transfert en cours");
            $operation->setTransfert($new_transfert)
                ->setPourcentage(5)
                ->setCreatedAt(new DateTime());
            $this->getDoctrine()->getManager()->persist($operation);
            $this->getDoctrine()->getManager()->flush();
            //$this->emailNotification->transfertAlert($this->security->getUser(), $new_transfert);
            return $this->redirectToRoute('account_transfer');
        }

        return $this->render('account/transfer.html.twig', [
            'form'  => $form->createView(),
            'transferts'    => $this->functions->transfertByUser(),
            'current_page'  => 'transfert'
        ]);
    }

    /**
     * @Route("/show/{id}", name="transfert_show")
     * @param Transfert $transfert
     * @param Request $request
     * @return Response
     */
    public function show(Transfert $transfert, Request $request) {
        if($this->security->getUser()->getEmailToken()) {
            return $this->redirectToRoute('email_error');
        }
        return $this->render('account/transfert_show.html.twig', [
            'transfert' => $transfert,
        ]);
    }

}