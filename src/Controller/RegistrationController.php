<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register( MailerInterface $mailer, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newUser = $form->getData();

        // $imagePath= $form->get('imagePath')->getData();
       
        // if($imagePath){
        //   $newFileName= uniqid() . '.'. $imagePath->guessExtension();   
         
        //   try{
        //        $imagePath->move(
        //         $this->getParameter('kernel.project_dir') . '/public/uploads',
        //         $newFileName
        //        );
        //   }catch(FileException $e){
        //    return new Response($e->getMessage());
        //   }

        //   $newUser->setImagePath('/uploads/' . $newFileName);
        // }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()

                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $email = (new Email())
            ->from('ardhihelpdesk@example.com')
            ->to($user->getEmail())
            ->subject('Alredy you have an account in Helpdesk Support System!')
            ->text('Hello! THis is Admin for Ardhi Help desk system
                    you alredy been registered  
                    Ardhi University Help Desk Support System .
                    You can now report to us damaged assets in side Ardhi University campus
                    Through our website "http://localhost:8000/" use:passward :12345678 and this username.

                    For more info and  procudure how to use this website click: http://localhost:8000/about
            ');

        $mailer ->send($email);    

            return $this->redirectToRoute('app_users');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    
}
