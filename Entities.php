<?php

/**File Entities.php 
 * Contains class Person and class Manager
 */

/**
* Base class Person is used to describe the properties of an employee
* (name, email, phone and salary of employee)
*/
class Person {
    public $name;
    public $email;
    public $phone;
    public $salary;
    /**id is unique parameter for identification employers and managers */
    public $id;

    public function __construct($json = false) {
        if ($json) $this->set(json_decode($json, true));
    }
    /**function "set" uses for cast json object to class "Person" object */
    public function set($data) {
        foreach ($data AS $key => $value) {
            if (is_array($value)) {
                $sub = new Person;
                $sub->set($value);
                $value = $sub;
            }
            $this->{$key} = $value;
        }
    }
    /**function "setSalary" uses for calculate the salary of employee */
    public function setSalary(bool $perHourSalary, int $wage) {
        if($perHourSalary) {
            $this->salary = 8*20*$wage;
        } else {
            $this->salary = $wage;
        }
    }
}

/**
 * Class "Manager" extends the employee
 * Managers have a their own employees
 */
class Manager extends Person {
    public $employees;

    public function __construct($json = false) {
        if ($json) $this->set(json_decode($json, true));
    }
    public function set($data) {
        foreach ($data AS $key => $value) {
            if (is_array($value)) {
                $sub = new Manager;
                $sub->set($value);
                $value = $sub;
            }
            $this->{$key} = $value;
        }
    }
}
?>