<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/mot-depasse-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {
        
        if($this->getUser()){
            return $this->redirectToRoute('home');
        }

        if($request->get('email')){

            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if($user){
                // 1 - enregistrer en base la demande  de reset_password avec user, token et createdAt
                $reset_password=  new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // 2 envoie d'un email à l'utilisateur avec un lien lui permettant de modifier son password
                $url = $this->generateUrl('update_password',[
                    'token' =>$reset_password->getToken()
                ]);
                $content = "Bonjour ".$user->getFirstName()."<br>Vous avez demander à réilitialiser votre mot de passe sur la boutique Française .<br><br>";
                $content .= "Merci de bien vouloir cliquer sur le liens suivant pour <a href='".$url."'> mettre à jours votre mot de passe</a> .";
                $mail = new Mail();
                $mail->send($user->getEmail(),$user->getFirstName().''.$user->getLastName(), 'Réinitialiser votre mot de passe sur la boutique Française .', $content);

                $this->addFlash('notice', 'Vous allez recevoir dans quelques seconde un mail avec la procédure pour réinitiaiser votre ot de passe.');
            }else{
                $this->addFlash('notice', 'Cette adresse mail est inconnue.');
            }
        }
        return $this->render('reset_password/index.html.twig');
    }


     /**
     * @Route("/modifier-mon-mot-depasse/{token}", name="update_password")
     */
    public function update(Request $request,$token, UserPasswordEncoderInterface $encoder): Response
    {
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        
        if(!$reset_password){
            return $this->redirectToRoute('reset_password');
        }

        // vérifier si lle createdAt = now -3h
        $now = new \DateTime();

        if($now > $reset_password->getCreatedAt()->modify('+ 3 hour')){
            $this->addFlash('notice', 'Votre demande demande de midification de mot de passe a exipiré . Merci de renouveller.');
            return $this->redirectToRoute('reset_password');
        }

        // rendre une vue avec mot de passe et confirmer mot de passse

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $new_pwd = $form->get('new_password')->getData();

            //recuperation de l'utilisateur
            $user = $reset_password->getUser();

            // Encodage du mot de passe
            $password = $encoder->encodePassword($user , $new_pwd);
            $user->setPassword($password);

            // flussh de la base de donnée
            $this->entityManager->flush();

            // Redirection vers la page de connexion des

            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jours');

            return $this->redirectToRoute('app_login');

        }
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
