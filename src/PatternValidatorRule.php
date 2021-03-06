<?php

namespace ItvisionSy\Validator;

/**
 * Description of PatternValidatorRule
 *
 * @author Muhannad Shelleh <muhannad.shelleh@itvision-sy.com>
 */
class PatternValidatorRule extends ValidatorRule {
    
    protected $args = ['pattern'];

    protected function _validate($value) {
        return !!preg_match($this->pattern, $value)?:"Value does not meet the required pattern";
    }

}
