<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
Use App\Classe\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        
        
        $user = new User();
        $form = $this->createForm(RegisterType::class,$user);
        $notification= null;

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            
            if(!$search_email){

                $password =$encoder->encodePassword($user, $user->getPassword()) ;

                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // envoyer un mail à notre client
                $mail = new Mail();

                $content="Bonjour ".$user->getFirstName()."<br/>Merci pour votre commande.<br/><br/>Le lorem ipsum est, en imprimerie, une suite de mots sans signification utilisée à titre provisoire pour calibrer 
                une mise en page, le texte définitif venant remplacer le faux-texte dès qu'il est prêt ou que la mise en page est achevée. Généralement, on utilise
                un texte en faux latin, le Lorem ipsum ou Lipsum.";
                $mail->send($user->getEmail(),$user->getFirstName(), 'Bienvenue sur La boutique Française',$content);
                $notification = "Votre inscription c'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte";
            }else{
                $notification = "L'émail que vous avez renseigné existe ddéjà ";
            }
            
           
        }

        

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
