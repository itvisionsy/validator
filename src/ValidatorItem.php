<?php

namespace ItvisionSy\Validator;

/**
 * The item validation class.
 * 
 * It is the core class that handles evaluating a single value against multiple rules and getting a list of errors, if any.
 * 
 * @author Muhannad Shelleh <muhannad.shelleh@itvision-sy.com>
 * 
 * @method ValidatorItem value($value) Sets the value to be tested
 * @method ValidatorItem rules($rules) Sets the rules for the value to be tested against
 * 
 * @property-read ValidatorRule[] $rules The rules for the value to be tested against
 * @property-read mixed $value The value to be tested
 * @property-read string[] $errors An array of errors resulted from the last validation. If not errors then empty array
 */
class ValidatorItem {

    /**
     * The value to be tested
     * @var mixed
     */
    protected $value;
    /**
     * The rules to be applied to the value
     * @var mixed[]
     */
    protected $rules = [];
    /**
     * The errors list resulted from the last evaluation of the item
     * @var string[]
     */
    protected $errors = [];

    /**
     * Instantiates a new ValidatorItem object
     * @param mixed $value The value to be tested against the rules
     * @param string[]|string|ValidatorRule|ValidatorRule[] $rules The rules for the value to be tested against
     * @return \ItvisionSy\Validator\ValidatorItem
     */
    public static function make($value, $rules = null) {
        return new static($value, $rules);
    }

    /**
     * Instantiates a new ValidatorItem object and evaludates it
     * @param mixed $value The value to be tested against the rules
     * @param string[]|string|ValidatorRule|ValidatorRule[] $rules The rules for the value to be tested against
     * @param null $validatorItem a reference value to take the validatorItem object back
     * @return boolean|array TRUE if the validator succeeded, array of errors otherwise
     */
    public static function quick($value, $rules, &$validatorItem = null) {
        $validatorItem = static::make($value, $rules);
        return $validatorItem->validate()? : $validatorItem->errors;
    }

    /**
     * 
     * @param mixed $value The value to be tested against the rules
     * @param string[]|string|ValidatorRule|ValidatorRule[] $rules The rules for the value to be tested against
     */
    public function __construct($value, $rules = null) {
        $this->value = $value;
        if ($rules) {
            $this->setRules($rules);
        }
    }

    /**
     * Validates the value against the rules
     * @return boolean TRUE if the validation succeeded, FALSE otherwise.
     */
    public function validate() {
        $errors = [];
        foreach ($this->rules as $rule) {
            if (is_callable($rule)) {
                $result = $rule($this->value);
            } else {
                $result = $rule->validate($this->value);
            }
            if ($result !== true) {
                $errors[] = $result;
            }
        }
        $this->errors = $errors;
        return count($errors) == 0;
    }

    /**
     * Resets the rules to empty set, or optionally to a new set of rules.
     * @param string[]|string|ValidatorRule|ValidatorRule[] $rules The rules for the value to be tested against
     * @return \ItvisionSy\Validator\ValidatorItem
     */
    public function resetRules($rules = null) {
        $this->rules = [];
        if ($rules) {
            $this->setRules($rules);
        }
        return $this;
    }

    /**
     * Sets the rules for the value to be tested against
     * @param string[]|string|ValidatorRule|ValidatorRule[] $rules The rules for the value to be tested against
     * @return \ItvisionSy\Validator\ValidatorItem
     * @throws \BadMethodCallException
     */
    public function setRules($rules) {
        if (is_array($rules)) {
            foreach ($rules as $rule) {
                if (!(is_object($rule) && $rule instanceof ValidatorRule || is_string($rule) || is_callable($rule))) {
                    throw new \BadMethodCallException("\$rule should be a valid predefined rule or an instance of \\ItvisionSy\\Validator\\ValidatorRule");
                }
                $this->setRules($rule);
            }
        } elseif (is_string($rules)) {
            $tempCheck = implode('', array_map(function($value) {
                        return ucfirst((string) $value);
                    }, explode('_', str_replace(['-'], '_', $rules))));
            $possibleSubs = explode('|', $tempCheck);
            if (count($possibleSubs) > 1) {
                return $this->setRules($possibleSubs);
            }
            $arguments = explode(":", $tempCheck);
            if (count($arguments) > 1) {
                $tempCheck = $arguments[0];
                $arguments = explode(",", $arguments[1]);
            } else {
                $arguments = [];
            }
            $ruleClass = __NAMESPACE__ . "\\{$tempCheck}ValidatorRule";
            $this->rules[] = $rule = $ruleClass::make();
            $rule->setParameters($arguments);
        } elseif (is_object($rules) && $rules instanceof ValidatorRule) {
            $this->rules[] = $rules;
        } elseif (is_callable($rules)) {
            $this->rules[] = $rules;
        } else {
            throw new \BadMethodCallException("\$rules should be either a string 'rule|rule|rule' or an array [rule,rule,rule] or Itvision\\Validator\\ValidatorRule[]");
        }
        return $this;
    }

    public function __call($name, $arguments) {
        switch ($name) {
            case 'value':
                if (count($arguments) > 0) {
                    return call_user_func_array([$this, 'setValue'], $arguments);
                } else {
                    return $this->getValue();
                }
                break;
            case 'rules':
                if (count($arguments) > 0) {
                    return call_user_func_array([$this, 'setRules'], $arguments);
                } else {
                    return $this->getRules();
                }
                break;
        }
    }

    public function __get($name) {
        switch ($name) {
            case 'value':
                return $this->value;
                break;
            case 'rules':
                return $this->rules;
                break;
            case 'errors':
                return $this->errors;
                break;
        }
    }

}
