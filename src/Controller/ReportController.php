<?php

 namespace App\Controller;

use Doctrine\DBAL\Driver\Mysqli\Initializer\Options as InitializerOptions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    
    // Include Dompdf required namespaces
    use Dompdf\Dompdf;
    use Dompdf\Options;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    class ReportController extends AbstractController
    {
        public function index()
        {
            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');
            
            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);
            
            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('report/monthly.html.twig', [
                'title' => "Welcome to our PDF Test"
            ]);
            
            // Load HTML to Dompdf
            $dompdf->loadHtml($html);
            
            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');
    
            // Render the HTML as PDF
            $dompdf->render();
    
            // Output the generated PDF to Browser (force download)
            $dompdf->stream("mypdf.pdf", [
                "Attachment" => true
            ]);
        }
    }
   
//      #[Route('/monthly', methods:['GET'], name: 'a_dashb')]
//     public function getReport(TaskRepository $taskRepository){
//         $this->denyAccessUnlessGranted('ROLE_ADMIN');
        


//         // COUNT SUCESS TASKS
//         $total_success =$taskRepository-> totalSuccesTask();
        

//         //COUNT FAIL TASK
//         $total_fail =$taskRepository->  totalFailedTask();
       
//         // COUNT TOTAL TASK
//         $total_task =$taskRepository-> findAll();
//         $total_task = count($total_task);
        
//         return $this->render('report/monthly.html.twig',[
//             'success' => $total_success, 
//             'fail' => $total_fail,
//             'total' => $total_task, 
//         ]);
//     }


// }
