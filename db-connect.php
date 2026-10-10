<?php 

    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'login_sys';

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $conn = new mysqli($host, $user, $password, $database);
        $conn->set_charset('utf8mb4');
    } catch (mysqli_sql_exception $exception) {
        http_response_code(500);
        exit('Database connection failed. Start MySQL in XAMPP and import user.sql.');
    }





    









?>