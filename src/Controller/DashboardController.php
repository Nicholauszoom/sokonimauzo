<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\MessagesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

 
 
   
     #[Route('/UserDashboard', methods:['GET'], name: 'u_dashb')]
    public function getUserDash(){
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('dashboard/user_dashboard.html.twig');
    }


     #[Route('/Dashboard', methods:['GET'], name: 'a_dashb')]
    public function getAdmnDash(TaskRepository $taskRepository){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        


        // start of the analsis of the success tasks
        $results =$taskRepository->countTasksWithStatusOneByMonth();
        

        $labels = [];
        $data = [];

        foreach ($results as $task) {
            $labels[] = $task['year'] . '-' . $task['month'];
            $data[] = $task['task_count'];
        }

        // end analysis

        // start analysis of the fail tasks
        
        $failresults =$taskRepository->countFailedTasksWithStatusOneByMonth();
        $flabels = [];
        $fdata = [];

        foreach ($failresults as $ftask) {
            $flabels[] = $ftask['year'] . '-' . $ftask['month'];
            $fdata[] = $ftask['ftask_count'];
        }


        // COUNT SUCESS TASKS
        $total_success =$taskRepository-> totalSuccesTask();
        

        //COUNT FAIL TASK
        $total_fail =$taskRepository->  totalFailedTask();
       
        // COUNT TOTAL TASK
        $total_task =$taskRepository-> findAll();
        $total_task = count($total_task);
        
        return $this->render('dashboard/admin_dashboard.html.twig',[
            'labels' => $labels,
            'data' => $data, 
            'flabels' => $flabels,
            'fdata' => $fdata, 
            'success' => $total_success, 
            'fail' => $total_fail,
            'total' => $total_task, 
        ]);
    }

    #[Route('/TechnDashboard', methods:['GET'], name: 't_dashb')]
    public function getTechnDash(){
        $this->denyAccessUnlessGranted('ROLE_TECHNICIAN');
        return $this->render('dashboard/technician_dash.html.twig');
    }

}
