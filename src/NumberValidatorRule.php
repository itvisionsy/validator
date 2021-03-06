<?php

namespace ItvisionSy\Validator;

/**
 * Description of NumberValidatorRule
 *
 * @author Muhannad Shelleh <muhannad.shelleh@itvision-sy.com>
 */
class NumberValidatorRule extends ValidatorRule {

    protected function _validate($value) {
        return is_numeric($value)? : "Value should be a number";
    }

}
