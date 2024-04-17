<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AtLeastThreeIngredientsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // Vérifiez ici si la recette a au moins 3 ingrédients
        $ingredientsCount = count($value->getIngredients());

        if ($ingredientsCount < 3) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
