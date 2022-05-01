<?php

namespace App\Twig;

use App\Repository\TransactionRepository;
use App\Repository\TransfertRepository;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AmountExtension extends AbstractExtension
{
    /**
     * @var TransfertRepository
     */
    private $transfertRepository;
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var Security
     */
    private $security;

    /**
     * AmountExtension constructor.
     * @param TransfertRepository $transfertRepository
     * @param TransactionRepository $transactionRepository
     * @param Environment $twig
     * @param Security $security
     */
    public function __construct(TransfertRepository $transfertRepository, TransactionRepository $transactionRepository, Environment $twig, Security $security) {
        $this->transfertRepository = $transfertRepository;
        $this->transactionRepository = $transactionRepository;
        $this->twig = $twig;
        $this->security = $security;
    }

    public function getFunctions() : array
    {
        return [
            new TwigFunction('totalAmount', [$this, 'getAmount'], ['is_safe' => ['html']]),
            new TwigFunction('totalPourcentage', [$this, 'getPourcentage'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @param array $vars
     * @param String $value
     * @return float|int
     */
    private function addition( $vars, String $value)
    {
        $count = 0;
        foreach ($vars as $var) {
            $count += (float) $var->$value();
        }
        return $count;
    }

    public function getAmount($entity) : string {
        $transferts = $this->transfertRepository->findBy(['user_client'=>$entity]);
        $transactions = $this->transactionRepository->findBy(['user_client'=>$entity]);
        $count1 = $this->addition($transactions, 'getSolde');

        $count2 = $this->addition($transferts, 'getAmount');

        return $this->twig->render('partials/amount.html.twig', [
            'totalAmount'   => $count1-$count2
        ]);
    }

    public function getPourcentage($entity) : string {
        $pourcentage = $this->addition($entity->getOperations(), 'getPourcentage');
        return $this->twig->render('partials/pourcentage.html.twig', [
            'pourcentage'   => $pourcentage
        ]);
    }
}