<?php  
namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckInfrastructureIdExists extends Constraint
{
    public $message = 'The infrastructure with ID {{ id }} does not exist.';

    public function validatedBy()
    {
        return static function () {
            return new CheckInfrastructureIdExistsValidator();
                   };
    }
}
