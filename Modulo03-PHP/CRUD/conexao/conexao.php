<?php

    $servername = "localhost"; //ip ou dominio do servidor
    $username = "root"; //usuário no banco de dados
    $password = ""; //senha do usuário no banco de dados
    $dbname = "faculdade";

    //Criar conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    //Verificar a conexão
    if ($conn->connect_error){
        die("Conexão falhou...");
    }

?>