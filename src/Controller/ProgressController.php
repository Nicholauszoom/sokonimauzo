<?php

namespace App\Controller;

use App\Entity\Progress;
use App\Form\ProgressType;
use App\Repository\ProgressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/progress')]
class ProgressController extends AbstractController
{
    #[Route('/', name: 'app_progress_index', methods: ['GET'])]
    public function index(ProgressRepository $progressRepository,Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TECHNICIAN');

        $technician=$security->getUser();

        $progresbytechn = $progressRepository->findAllByTechnicianId($technician->getId());
    
        return $this->render('progress/index.html.twig', [
            'progress' => $progressRepository->findAll(),
            'progresbytechn'=> $progresbytechn,
        ]);
    }

    #[Route('/admnview', name: 'app_padmn_viewrogress_index', methods: ['GET'])]
    public function getByAdmin(ProgressRepository $progressRepository,Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        
    
        return $this->render('progress/admn_viewall.html.twig', [
            'progress' => $progressRepository->findAll(),
          
        ]);
    }

    #[Route('/new', name: 'app_progress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProgressRepository $progressRepository,Security $security): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TECHNICIAN');

        $progress = new Progress();
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $progress =$form -> getData();
            $imagePath =$form->get('imagePath')->getData();

            if($imagePath){
                $newFileName =uniqid() . '.' . $imagePath->guessExtension();

                try{
                    $imagePath->move(
                        $this -> getParameter('kernel.project_dir') . '/public/uploads', $newFileName);
                }catch(FileException $e){
                    return new Response($e->getMessage());
                }
                $progress->setImagePath('/uploads/' . $newFileName);
            }

            $progressRepository->save($progress, true);

            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }
       
    
        return $this->renderForm('progress/new.html.twig', [
            'progress' => $progress,
            'form' => $form,
            
        ]);
    }

    #[Route('/{id}', name: 'app_progress_show', methods: ['GET'])]
    public function show(Progress $progress): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_TECHNICIAN');

        return $this->render('progress/show.html.twig', [
            'progress' => $progress,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_progress_show', methods: ['GET'])]
    public function showByAdmin(Progress $progress): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('progress/admin_show.html.twig', [
            'progress' => $progress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_progress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Progress $progress, ProgressRepository $progressRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TECHNICIAN');

        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $progressRepository->save($progress, true);

            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('progress/edit.html.twig', [
            'progress' => $progress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_progress_delete', methods: ['POST'])]
    public function delete(Request $request, Progress $progress, ProgressRepository $progressRepository): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$progress->getId(), $request->request->get('_token'))) {
            $progressRepository->remove($progress, true);
        }

        return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
    }
}
