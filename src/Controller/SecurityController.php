<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * This controller allows us to login
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/connexion', name: 'security.login',methods:['GET','POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig', [
            'last_username'=>$authenticationUtils->getLastUsername(),
            'error'=>$authenticationUtils->getLastAuthenticationError()
        ]);
    }
    /**
     * This controller allows us to logout
     *
     * @return void
     */
    #[Route('/deconneion', name: 'security.logout',methods:['GET','POST'])]
    public function logout()
    {
        # Nothing to do here...
    }
    /**
     *  This controller allows us to register
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/inscription','security.registration',methods:['GET','POST'])]
    public function registration(Request $request,EntityManagerInterface $manager):Response
    {
        $user=new User();
        $user->setRoles(['ROLE_USER'])->setPassword('password');
        $form=$this->createForm(RegistrationType::class,$user);
        $form->handleRequest($request);
        // dd($form->getData());
        if($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre compte a bien été créé.'
            );
            return $this->redirectToRoute('security.login');
        }
        return $this->render('pages/security/registration.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
