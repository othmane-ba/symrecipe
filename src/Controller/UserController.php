<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * This controller allows us to edit User Profile
     *
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edition/{id}', name: 'user.edit',methods:['GET','POST'])]
    public function edit(User $user,Request $request,EntityManagerInterface $manager,UserPasswordHasherInterface $hasher): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        if($this->getUser()!==$user){
            return $this->redirectToRoute('recipe.index');
        }
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($hasher->isPasswordValid($user,$form->getData()->getPlainPassword())){
                $user=$form->getData();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                'Les informations de votre compte ont bien été modifiées.'
            );
            return $this->redirectToRoute('recipe.index');
            }else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrecte!'
                );
            }
            
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * This controller allows us to edit user's password
     *
     * @param User $user
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edition-mot-de-passe/{id}','user.edit.password',methods:['GET','POST'])]
    public function editPassword(User $user,Request $request,UserPasswordHasherInterface $hasher,EntityManagerInterface $manager):Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('security.login');
        }
        if($this->getUser()!==$user){
            return $this->redirectToRoute('recipe.index');
        }
        $form=$this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if($hasher->isPasswordValid($user,$form->getData()['plainPassword'])){
                $user->setUpdatedAt(new \DateTimeImmutable());
                $user->setPlainPassword($form->getData()['newPassword']);
                $manager->persist($user);
             $manager->flush();
                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifier.'
                );
                return $this->redirectToRoute('recipe.index');
            }else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrecte!'
                );
            }
        }
        return $this->render('pages/user/edit_password.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
