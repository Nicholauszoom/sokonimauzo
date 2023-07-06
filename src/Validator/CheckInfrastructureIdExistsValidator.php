<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckInfrastructureIdExistsValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        $infrastructure = $this->entityManager->getRepository('App\Entity\Messages')->find($value);

        if (!$infrastructure) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ infrastructure }}', $value)
                ->addViolation();
        }
    }
}