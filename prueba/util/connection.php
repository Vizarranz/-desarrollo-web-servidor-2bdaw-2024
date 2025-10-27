<?php
    $_server = "127.0.0.1";
    $_user = "estudiante"; //Cambiar a "root"
    $_password = "estudiante"; //Cambiar a ""
    $_database = "hype_db";

    //  Mysqli
    $_conexion = new Mysqli($_server,$_user,$_password,$_database)
        or die("Connection error");

    /* Database
    create schema hype_db;
    use hype_db;

    create table users (
	`id` int not null auto_increment primary key,
    `username` varchar(50),
    `password` varchar(255),
    `email` varchar(100)
);
    */
?>
