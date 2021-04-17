<?php

namespace App\Controller;

use App\Entity\Order;
use App\Classe\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function index(Cart $cart,$stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        
        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }


        if($order->getState() ==0){
            //on vide le panier
            $cart->remove();
            //Modifier le statut de la commande orderState en mettant 1
            $order->setState(1);
            $this->entityManager->flush();

            // envoyer un mail à notre client pour lui confirmer sa commande  
        }
        
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
