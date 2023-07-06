<?php

namespace App\Controller;

use App\Entity\Technician;
use App\Form\TechnicianType;
use App\Repository\TechnicianRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/technician')]
class TechnicianController extends AbstractController
{
    #[Route('/', name: 'app_technician_index', methods: ['GET'])]
    public function index(TechnicianRepository $technicianRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('technician/index.html.twig', [
            'technicians' => $technicianRepository->findAll(),
        ]);
    }




    #[Route('/new', name: 'app_technician_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TechnicianRepository $technicianRepository, MailerInterface $mailer, UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $technician = new Technician();
        $form = $this->createForm(TechnicianType::class, $technician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
     
             //Password encoder script
             $technician->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $technician,
                    $form->get('plainPassword')->getData()

                )
            );

            $email = (new Email())
            ->from('nicholaussomi5@gmail.com')
            ->to($technician->getEmail())
            // ->to('nicholauszoom95@gmail.com')
            ->subject('HelpDesk support system ,Your officially registered in our system')
            ->text('HELLO, Mr this Ardhi university administrator :Joel Patrick!
                   It been a pleasure to inform you that your registered to be one of our technician team.
                   *******************#**********************
                   visit our web application system url: http://localhost:8000/ to view the updates and the tasks assigned to you,
                    password use :12345678 and your username : ...@gmail.com
     
                    THANK YOU!
            ');

        $mailer ->send($email);   
          
          
          
            $technicianRepository->save($technician, true);

            return $this->redirectToRoute('app_technician_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('technician/new.html.twig', [
            'technician' => $technician,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_technician_show', methods: ['GET'])]
    public function show(Technician $technician): Response
    {
        return $this->render('technician/show.html.twig', [
            'technician' => $technician,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_technician_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Technician $technician, TechnicianRepository $technicianRepository): Response
    {
        $form = $this->createForm(TechnicianType::class, $technician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $technicianRepository->save($technician, true);

            return $this->redirectToRoute('app_technician_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('technician/edit.html.twig', [
            'technician' => $technician,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_technician_delete', methods: ['POST'])]
    public function delete(Request $request, Technician $technician, TechnicianRepository $technicianRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$technician->getId(), $request->request->get('_token'))) {
            $technicianRepository->remove($technician, true);
        }

        return $this->redirectToRoute('app_technician_index', [], Response::HTTP_SEE_OTHER);
    }




    // public function getTaskByTechId(MessageRepository $messageRepository, Security $security): Response
    // {
    //     $user = $security->getUser();
    //     $userId = $user ? $user->getId() : null;

    //     if ($userId) {
    //         $messages = $messageRepository->findAllByUserId($userId);
    //     } else {
    //         $messages = [];
    //     }

    //     return $this->render('default/index.html.twig', [
    //         'messages' => $messages,
    //     ]);
    // }


}
