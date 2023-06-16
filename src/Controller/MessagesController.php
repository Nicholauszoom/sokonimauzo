<?php

namespace App\Controller;


use App\Entity\Messages;
use App\Entity\User;
use App\Form\MessagesType;
use App\Repository\MessagesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as ConfigurationSecurity;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/messages')]
class MessagesController extends AbstractController
{
private $latitude;
private $longitude;
    
    #[Route('/', name: 'app_messages_index', methods: ['GET'])]
    public function index(MessagesRepository $messagesRepository ): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

       
        
        return $this->render('messages/index.html.twig', [
            'messages' => $messagesRepository->findAll(),
        ]);
    }


    #[Route('/read', name: 'app_messages_read', methods: ['GET'])]
    public function read(MessagesRepository $messagesRepository,Security $security ): Response
    {

        $user=$security->getUser();

        $message = $messagesRepository->findAllByUserId($user->getId());

        
        return $this->render('messages/readmessage.html.twig', [
            // 'messages' => $messagesRepository->findAll(),
            'messages'=>$message,
        ]);
    }




    #[Route('/student_message', name: 'app_message_student_view', methods: ['GET'])]
    public function view_by_student(MessagesRepository $messagesRepository, Security $security): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // $this->denyAccessUnlessGranted('ROLE_TECHNICIAN');
      $user=$security->getUser();

        $message = $messagesRepository->findAllByUserId($user->getId());

        return $this->render('task/technicianview.html.twig', [
            // 'tasks' => $taskRepository->findAll(),
            'messages'=>$message,
           
        ]); 
    }


    
//current modification
    #[Route('/new', name: 'app_messages_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MessagesRepository $messagesRepository,ValidatorInterface $validator,Security $security): Response
    {

      
        // $this->denyAccessUnlessGranted('ROLE_USER');



        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
 
            // $data = $form->getData();
            // $latitude = $data['latitude'];
 
            // $message->setLatitude($latitude);
            // $message->setLongitude($longitude);
            $message =$form -> getData();
            $imagePath =$form->get('imagePath')->getData();
            if($imagePath){
                $newFileName =uniqid() . '.' . $imagePath->guessExtension();

                try{
                    $imagePath->move(
                        $this -> getParameter('kernel.project_dir') . '/public/uploads', $newFileName);
                }catch(FileException $e){
                    return new Response($e->getMessage());
                }
                $message->setImagePath('/uploads/' . $newFileName);
            }

            $messagesRepository->save($message, true);

            return $this->redirectToRoute('app_messages_new', [], Response::HTTP_SEE_OTHER);
        }
    // } else {
    // return null;
    // }
    $user=$security->getUser();

    $messagess = $messagesRepository->findAllByUserId($user->getId());

        
            // $messages = $messagesRepository->findAllByUserId($userId);

            // return new Response('Location saved successfully.', Response::HTTP_OK);

        return $this->renderForm('messages/new.html.twig', [
             
            'message' => $message,
            // 'messages' => $messagesRepository->findAll(),
            'messages'=>$messagess,
            'form' => $form->createView(),
        ]);
    }




    #[Route('/{id}', name: 'app_messages_show', methods: ['GET'])]
    public function show(Messages $message): Response
    {
       

        return $this->render('messages/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_messages_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Messages $message, MessagesRepository $messagesRepository): Response
    {
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messagesRepository->save($message, true);

            return $this->redirectToRoute('app_messages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('messages/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_messages_delete', methods: ['POST'])]
    public function delete(Request $request, Messages $message, MessagesRepository $messagesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $messagesRepository->remove($message, true);
        }

        return $this->redirectToRoute('app_messages_index', [], Response::HTTP_SEE_OTHER);
    }



// //////////////////
// #[Route('/', name: 'app_messages_index', methods: ['GET'])]
// public function index(MessagesRepository $messagesRepository): Response
// {
//     return $this->render('messages/index.html.twig' ,[
//         'messages' => $messagesRepository->findAll(),
//     ]);
// }



#[Route('/accountmessage', name: 'app_messages_By_user_Id',  methods: ['GET'])]
public function getMssgByUserId(MessagesRepository $messagesRepository,Security $security): Response
    {
        $user = $security->getUser();
        $userId = $user ? $user->getId(): null;

        if ($userId) {
            $messages = $messagesRepository->findAllByUserId($userId);
        } else {
            $messages = [];
        }

       

        return $this->renderForm('messages/by_account.html.twig', [
            'messages' =>  $messages,
            
        ]);
    }



      
    // #[Route('/location_page', name: 'app_location_index', methods: ['GET'])]
    // public function getLocationPage(): Response
    // {
    //     return $this->render('messages/location.html.twig');
    // }
}