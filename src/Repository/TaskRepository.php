<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Doctrine\Functions\MySqlYear;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */


class TaskRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Task::class);
        // $this->entityManager=$entityManager;
    }

    public function save(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return Task[] Returns an array of Task objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }




// public function findAllByTechnicianId($technId)
//     {
//         return $this->createQueryBuilder('t')
//             ->andWhere('t.techn = :technId')
//             ->setParameter('technId', $technId)
//             ->orderBy('t.startAt', 'DESC')
//             ->getQuery()
//             ->getResult();
//     }







/**
     * Finds tasks by technician ID.
     *
     * @param int $technicianId The technician ID to filter by.
     *
     * @return Task[] The tasks found.
     */
    public function findByTechnicianId($technId)
    {
        return $this->createQueryBuilder('t')
            ->join('t.techn', 'techn')
            ->andWhere('techn.id = :technId')
            ->setParameter('technId', $technId)
            ->getQuery()
            ->getResult();
    }




    // TOTAL NUMBER OF THE SUCCESS TASKS  FIND BY TECHN ID

    public function successTaskByTechnId($technId)
{
    return $this->createQueryBuilder('t')
        ->join('t.techn', 'techn')
        ->andWhere('techn.id = :technId')
        ->andWhere('t.status = :status')
        ->setParameter('technId', $technId)
        ->setParameter('status', 1)
        ->getQuery()
        ->getResult();
}
    




  // TOTAL NUMBER OF THE FAILED TASKS  FIND BY TECHN ID

  public function failTaskByTechnId($technId)
  {
      return $this->createQueryBuilder('t')
          ->join('t.techn', 'techn')
          ->andWhere('techn.id = :technId')
          ->andWhere('t.status = :status')
          ->setParameter('technId', $technId)
          ->setParameter('status', 0)
          ->getQuery()
          ->getResult();
  }


    // public function getTaskCountsByMonth(): array
    // {
        
    //     return $this->createQueryBuilder('t')
    //         ->select('COUNT(t.id) AS task_count, (t.start_at) AS year, (t.start_at) AS month')
    //         ->where('t.status = 1')
    //         ->groupBy('year, month')
    //         ->orderBy('year, month')
    //         ->getQuery()
    //         ->getResult();
    // }

    // ANALYSIS OF THE SUCCESSFUL TASKS PER MONTH AND ANNNUALLY
    public function countTasksWithStatusOneByMonth()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('YEAR(t.start_at) AS year, MONTH(t.start_at) AS month, COUNT(t.id) AS task_count')
           ->where('t.status = :status')
           ->setParameter('status', 1)
           ->groupBy('year, month')
           ->orderBy('year, month');

        return $qb->getQuery()->getResult();
 

    }


    // ANALYSIS OF THE FAIL TASKS PER MONTH AND ANNNUALLY
    public function countFailedTasksWithStatusOneByMonth()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('YEAR(t.start_at) AS year, MONTH(t.start_at) AS month, COUNT(t.id) AS ftask_count')
           ->where('t.status = :status')
           ->setParameter('status', 0)
           ->groupBy('year, month')
           ->orderBy('year, month');

        return $qb->getQuery()->getResult();
 

    }

    // TOTAL NUMBER OF THE SUCCESS TASKS 

    public function totalSuccesTask()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('t.id AS total_success')
           ->where('t.status = :status')
           ->setParameter('status', 1);
          

        return $qb->getQuery()->getResult();
    }
 
        // TOTAL NUMBER OF THE FAILED TASKS 

    public function totalFailedTask()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('t.id AS total_fail')
           ->where('t.status = :status')
           ->setParameter('status', 0);
           

        return $qb->getQuery()->getResult();
 
   
}

// TOTAL TASK
    public function totalTask()
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('COUNT(t.id) AS total_task');
          

        return $qb->getQuery()->getResult();
 
   
}

}

