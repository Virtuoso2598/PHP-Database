<?php

/**
 * File formController.php 
 * This file is used to controll POST and GET requests respectively from the index.php form 
 */

/**POST-method controller */
if( $_SERVER["REQUEST_METHOD"] == "POST"){
    /** 
     * Checking that the name, mail and phone are not empty.
     *  If the fields from index.php are filled incorrectly, then return an error
    */
    if(!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["salary"]) && is_numeric($_POST["salary"])){
        /** 
         * Using cookies like a local storage of employees.
         * First of all, check if the cookies exists. If cookies do not exist, 
         * then create an empty array to write a new employee. 
         * If the cookie exists, then use json parse and cast the array to the Person class.
         * Next, an instance of the Person class is created and determined by the data from the POST request
         * Finally, add new data to cookies and override them.
        */
        
        if(empty($_POST["isManager"])) {
            if(!isset($_COOKIE["Persons"])) {
                $persons = array();
            } else {
                $persons = json_decode($_COOKIE["Persons"], true);
                for($i=0; $i < count($persons); $i++){
                    $persons[$i] = json_encode($persons[$i]);
                    $persons[$i] = new Person($persons[$i]);
                }
            }
            $newPerson = new Person();
            $newPerson->name = htmlentities($_POST["name"]);
            $newPerson->email = htmlentities($_POST["email"]);
            $newPerson->phone = htmlentities($_POST["phone"]);
            $newPerson->id = count($persons);
            $newPerson->setSalary(!empty($_POST["isPerHour"]), $_POST["salary"]);

            array_push($persons, $newPerson);
            setcookie("Persons", json_encode($persons));
            echo "Запись добавлена";
            header("/index.php");
        } else {
            /**  Data processing for a Manager is the same as for an employee */
            if(!isset($_COOKIE["Managers"])) {
                $managers = array();
            } else {
                $managers = json_decode($_COOKIE["Managers"], true);
                for($i=0; $i < count($managers); $i++){
                    $managers[$i] = json_encode($managers[$i]);
                    $managers[$i] = new Manager($managers[$i]);
                }
            }

            $persons = json_decode($_COOKIE["Persons"], true);
            $newManager = new Manager();
            $newManager->name = htmlentities($_POST["name"]);
            $newManager->email = htmlentities($_POST["email"]);
            $newManager->phone = htmlentities($_POST["phone"]);
            $newManager->id = count($persons) + count($managers);
            $newManager->setSalary(!empty($_POST["isPerHour"]), htmlentities($_POST["salary"]));

            $managerPersons = array();
            if(!empty($_POST['check_persons_list'])) {
                foreach($_POST['check_persons_list'] as $check)
                    array_push($managerPersons, $check);
            }
            $newManager->employees = json_encode($managerPersons);
            array_push($managers, $newManager);
            setcookie("Managers", json_encode($managers));
            echo "Запись добавлена";
            header("/index.php");
        }
    }    
    else {
        echo "Поля name, email, phone или salary заполнены неверно!";
    }
}

/** GET-method controller.
 *  Parses data from cookies and displays it on the page as a table
*/  
if( $_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_COOKIE["Persons"])) {
        $persons = json_decode($_COOKIE["Persons"], true);
        for($i=0; $i < count($persons); $i++){
            $persons[$i] = json_encode($persons[$i]);
            $persons[$i] = new Person($persons[$i]);
        }
    } 
    if(isset($_COOKIE["Managers"])) { 
        $managers = json_decode($_COOKIE["Managers"], true);
        for($i=0; $i < count($managers); $i++){
            $managers[$i] = json_encode($managers[$i]);
            $managers[$i] = new Manager($managers[$i]);
        }
        echo "<table border=\"1\">";
        foreach($managers as $manager) {
            echo "<tr><td>-Менеджер: </td><td>" . $manager->name . "</td><td>" . $manager->email . "</td><td>" . $manager->phone . "</td><td>"  . $manager->salary ."</td></tr>";
            preg_match_all("/\d+/", $manager->employees, $employees);
            foreach($employees[0] as $id) {
                foreach($persons as $person)
                    if($id == $person->id) 
                        echo "<tr><td>--Сотрудник: </td><td>" . $person->name . "</td><td>" . $person->email . "</td><td>" . $person->phone . "</td><td>"  . $person->salary ."</td></tr>";
                foreach($managers as $juniorManagers) 
                    if($id == $juniorManagers->id) 
                    echo "<tr><td>--Менеджер: </td><td>" . $juniorManagers->name . "</td><td>" . $juniorManagers->email . "</td><td>" . $juniorManagers->phone . "</td><td>"  . $juniorManagers->salary ."</td></tr>";
            }
        }
        echo "</table>";
    }
    if(!isset($_COOKIE["Persons"]) && !isset($_COOKIE["Managers"])) {
        echo "Таблица пуста";
    }
}
?>