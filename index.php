<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    include_once('Entities.php');
    include_once('formController.php');
    ?>
    <form method="POST">
        <p>Имя сотрудника: <input type="text" name="name" id="name" /></p>
        <p>Email сотрудника: <input type="text" name="email" id="email" /></p>
        <p>Телефон сотрудника: <input type="text" name="phone" id="phone" /></p>
        <p>
            Зарплата: <input type="text" name="salary" id="salary" />
            <input type="checkbox" name="isPerHour" id="isPerHour" value="yes" />
            в час
        </p>
        <p>Менеджер <input type="checkbox" name="isManager" id="isManager" value="on" /></p>
        <div id="block" style="display:none;">
            <p>Выберите своих сотрудников: </p>
            <?php
            if(!isset($_COOKIE["Persons"]) && !isset($_COOKIE["Managers"])) {
                echo "Нет сотрудников";
            }
            $i=0;
            echo "<table border=\"1\">";
            if(isset($_COOKIE["Persons"]))
                foreach($persons as $person) {
                    echo "<tr><td><input type=\"checkbox\" name=\"check_persons_list[]\" id=\"check_persons_list[]\" value=\"$i\" /></td>";
                    echo "<td>Сотрудник:</td><td>" . $person->name . "</td><td>" . $person->email . "</td><td>" . $person->phone . "</td></tr>";
                    $i++;
                }
            if(isset($_COOKIE["Managers"]))
                foreach($managers as $manager) {
                    echo "<tr><td><input type=\"checkbox\" name=\"check_persons_list[]\" id=\"check_persons_list[]\" value=\"$i\" /></td>";
                    echo "<td>Менеджер:</td><td>" . $manager->name . "</td><td>" . $manager->email . "</td><td>" . $manager->phone . "</td></tr>";
                    
                    $i++;
                }
            echo "</table>"
            ?>
        </div>
        <input type="submit" name="send" id="send" value="Send"/>
    </form>
    <form method="get">
        <input type="submit" name="getUsers" id="getUsers" value="Get users"/>
    </form>
    <script
        src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
    <script>
        $('#isManager').on("change", function(){
            if ($('#isManager').is(':checked')){
                $('#block').show();
            }
            else{
                $('#block').hide();
            }
        });
    </script>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</body>
</html>