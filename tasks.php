<?php
    include 'connect.php';

    // Käsitellään GET-pyyntö
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Haetaan taskit tietokannasta
        $query = $yhteys_pdo->prepare("SELECT * FROM task");
        $query->execute();
        $tasks = $query->fetchall(PDO::FETCH_ASSOC);
    
        // Iteroidaan $html_list -muuttujaan lista kannasta saadun datan perusteella
        $html_list = '';
        foreach ($tasks as $task) {
            $html_list .= '<li id="task-'.$task['id'].'">';
            $html_list .= '<button hx-delete="./tasks.php?id='.$task['id'].'" hx-target="#task-'.$task['id'].'" hx-swap="outerHTML">X</button>';
            $html_list .= $task['title'].' — '.$task['description'];
            $html_list .= '</li>';
        }

        // Palautetaan muodostettu lista
        echo $html_list;
    }

    // Käsitellään DELETE-KUTSU
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        // Puretaan urlista id-parametrin arvo
        $uri = $_SERVER['REQUEST_URI'];
        $uri_parts = explode('?', $uri);
        $query_parts = explode('=', $uri_parts[1]);
        $index = array_search('id', $query_parts);
        $id = $query_parts[$index + 1];

        //Deletoidaan id:tä vastaava taski kannasta
        $query = $yhteys_pdo->prepare("DELETE FROM task WHERE id=:id");
        $query->execute(array(':id' => $id));
        $res = $query->fetch(PDO::FETCH_ASSOC);

        // Jos kutsu onnistui, palautetaan tyhjä merkkijono
        echo '';
    } 

    // Käsitellään POST-KUTSU
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Puretaan taskin tiedot POST-kutsun formdatasta
        $title = $_POST['title'];
        $description = $_POST['description'];

        //Lisätään taski kantaan
        $query = $yhteys_pdo->prepare("INSERT INTO task (title, description, parent, done) VALUES (:title, :description, NULL, '0');");
        $query->execute(array(':title' => $title, ':description' => $description));
        $res = $query->fetch(PDO::FETCH_ASSOC);

        // Poimitaan juuri lisätyn rivin id
        $last_id = $yhteys_pdo->lastInsertId();

        // Haetaan juuri lisätty taski kannasta id:n perusteella
        $query = $yhteys_pdo->prepare("SELECT * FROM task WHERE ID=:id;");
        $query->execute(array(':id' => $last_id));
        $res = $query->fetch(PDO::FETCH_ASSOC);

        // Generoidaan taskin tietojen perusteella <li>-elementti
        $task .= '<li id="task-'.$res['id'].'">';
        $task .= '<button hx-delete="./tasks.php?id='.$res['id'].'" hx-target="#task-'.$res['id'].'" hx-swap="outerHTML">X</button>';
        $task .= $res['title'].' — '.$res['description'];
        $task .= '</li>';

        // Palautetaan luotu <li>-elementti
        echo $task;
    } 
?>