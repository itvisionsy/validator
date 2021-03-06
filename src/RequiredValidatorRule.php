<?php

namespace ItvisionSy\Validator;

/**
 * Description of RequiredValidatorRule
 *
 * @author Muhannad Shelleh <muhannad.shelleh@itvision-sy.com>
 */
class RequiredValidatorRule extends ValidatorRule {
    
    protected $ignoreNotProvided = false;

    protected function _validate($value) {
        return $value !== static::VALUE_IS_NOT_PROVIDED_AT_ALL ? TRUE : "Value is required";
    }

}
