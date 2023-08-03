<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
// use Symfony\Component\Serializer\Serializer;
use Mpdf\Mpdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

use KnpSnappyPdf;

#[Route('/task')]
class TaskController extends AbstractController
{


    protected static $defaultName = 'app:check-status';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        // parent::__construct();
        $this->entityManager = $entityManager;
    }



    #[Route('/', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): Response
    {
    
        // $tasks = $taskRepository->countTasksWithStatusOneByMonth();
        // $results =$taskRepository->countTasksWithStatusOneByMonth();
        

        // $labels = [];
        // $data = [];

        // foreach ($results as $task) {
        //     $labels[] = $task['year'] . '-' . $task['month'];
        //     $data[] = $task['task_count'];
        // }
        // $results = $taskRepository->countTasksWithStatusOneByMonth();
        // return $this->render('chart/index.html.twig', [
        //     'labels' => $labels,
        //     'data' => $data,
        // ]);
        return $this->render('task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
            // 'labels' => $labels,
            // 'data' => $data,
            // 'results' => $results
           
        ]); 
    }

    #[Route('/techn_task', name: 'app_task_techn_view', methods: ['GET'])]
    public function view_by_technician(TaskRepository $taskRepository, Security $security): Response
    {
     
      $techn=$security->getUser();

        $tasks = $taskRepository->findByTechnicianId($techn->getId());

        return $this->render('task/technicianview.html.twig', [
            // 'tasks' => $taskRepository->findAll(),
            'tasks'=>$tasks,
           
        ]); 
    }

    #[Route('/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new( MailerInterface $mailer ,Request $request, TaskRepository $taskRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        // reposrt generation   
        $mpdf = new Mpdf();
        $content = "<h2>Report for Maintanance Task </h2>";
        $content .= '<p>HelpDesk support system ,You have assigned a</br>
                        You have assigned new task to conduct for more.
        </p>';

        $mpdf->WriteHTML($content);
        $maintananceReport = $mpdf->Output('','S');

        
         $email = (new Email())
            ->from('nicholaussomi5@gmail.com')
            ->to($task->getTechn())
            // ->to('nicholauszoom95@gmail.com')
            ->subject('HelpDesk support system ,You have assigned a new task')
            ->text('You have assigned new task to conduct for more info visit our official website https:\\localhost:8000
                THANK YOU!
            ')
            ->attach($maintananceReport, 'contract-note.pdf');

        $mailer ->send($email);    

            $taskRepository->save($task, true);

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

      
        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {

 // Check if the entity is expired
 $endDate = $task->getEndAt();
 $currentDate = new \DateTime();
 $interval = $currentDate->diff($endDate);
 $isExpired = $interval->invert === 1;
        return $this->render('task/show.html.twig', [
            'task' => $task,
            'isExpired' => $isExpired,
        ]);
    }


    #[Route('/{id}/', name: 'app_task_techn_show', methods: ['GET'])]
    public function show_update_by_technician_by_task(Task $task): Response
    {

 // Check if the entity is expired
 $endDate = $task->getEndAt();
 $currentDate = new \DateTime();
 $interval = $currentDate->diff($endDate);
 $isExpired = $interval->invert === 1;
        return $this->render('task/technicianshow.html.twig', [
            'task' => $task,
            'isExpired' => $isExpired,
        ]);
    }



    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskRepository->save($task, true);

            return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $taskRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_task_index', [], Response::HTTP_SEE_OTHER);
    }

// task edit status

public function editTaskStatusAction(Request $request, $id)
{
    // Load the task from the database
    $entityManager = $this->$this->getDoctrine()->getManager();
    $task = $entityManager->getRepository(Task::class)->find($id);

    // Create the form
    $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('edit_task_status', ['id' => $id]))
        ->setMethod('POST')
        ->add('status', ChoiceType::class, [
            'choices' => [
                'New' => 'new',
                'In progress' => 'in_progress',
                'Completed' => 'completed',
            ],
            'expanded' => true,
            'multiple' => false,
        ])
        ->add('submit', SubmitType::class, ['label' => 'Update Status'])
        ->getForm();

    // Handle form submission
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $task->setStatus($data['status']); // Set the new status
        $entityManager->flush(); // Save changes to the database
        return $this->redirectToRoute(''); // Redirect to a different page
    }

    // Render the form in your HTML template
    return $this->render('task/show.html.twig', [
        'form' => $form->createView(),
    ]);
}

// #[Route('/report', name: 'task_report', methods: ['GET'])]
// public function report(TaskRepository $taskRepository): Response
// {


//     // $task =$taskRepository->findAll();
//     // $data = [];
//     // foreach ($task as $task) {
//     //     $data[] = [
//     //         'id' => $task->getId(),
//     //         'name' => $task->getName(),
//     //         'time_created' => $task->getStartAt()->format('Y-m-d H:i:s'),
//     //     ];
//     // }

//     return $this->render('task/task_report.html.twig', [
//         'tasks' => $taskRepository->findAll(),
       
//     ]); 
// }





//end

// protected function execute(InputInterface $input, OutputInterface $output)
// {
//     $repository = $this->entityManager->getRepository(Task::class);
//     $records = $repository->findAll();

//     foreach ($records as $record) {
//         $now = new \DateTime();
//         if ($now >= $record->getEndAt()) {
//             $record->setStatus('expired');
//             $this->entityManager->persist($record);
//         }
//     }

//     $this->entityManager->flush();

//     $output->writeln('Status update completed.');

//     return Command::SUCCESS;
// }


// #[Route('/report', name: 'app_task_REPORT', methods: ['GET'])]
// public function generateTaskReportAction(Dompdf $dompdf ,TaskRepository $taskRepository): Response
// {
//     // Get tasks from the database
//     // $tasks = $this->$taskRepository->findAll();

//     // Render the report template using Twig
//     $html = $this->renderView('task_report.html.twig', [
//         'report' => $taskRepository->findAll(),
//     ]);

//     // Load HTML into dompdf
//     $dompdf->loadHtml($html);

//     // Set paper size and orientation
//     $dompdf->setPaper('A4', 'portrait');

//     // Render PDF file
//     $dompdf->render();

//     // Output PDF file to browser
//     $pdf = $dompdf->output();

//     return new Response($pdf, 200, [
//         'Content-Type' => 'application/pdf',
//         'Content-Disposition' => 'inline; filename="task_report.pdf"',
//     ]);
// }



#[Route('/task_report', name: 'app_task_report', methods: ['GET'])]
public function getReport(Dompdf $dompdf ,TaskRepository $taskRepository): Response
{



    // $task->
       $html = $this->renderView('task_report.html.twig', [
            'report' => $taskRepository->findAll(),
    ]);

    // Load HTML into dompdf
       $dompdf->loadHtml($html);

    // Set paper size and orientation
       $dompdf->setPaper('A4', 'portrait');

    // Render PDF file
       $dompdf->render();

    // Output PDF file to browser
       $pdf = $dompdf->output();


    
   return new Response($pdf, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="task_report.pdf"',
    ]);
}

    #[Route('/reports', name: 'task_report_test3', methods: ['GET'])]
public function generatePdfReport(Pdf $pdf)
    {
    $html = $this->renderView('task/task_report.html.twig', [
        // Pass data to the report template
    ]);

    $pdfContent = $pdf->getOutputFromHtml($html);
    // getOutputFromHtml($html);

    return new Response(
        $pdfContent,
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="report.pdf"'
        ]
    );
}

     
}
