<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AtLeastThreeIngredients extends Constraint
{
    public $message = 'La recette doit avoir au moins 3 ingrédients.';
}
