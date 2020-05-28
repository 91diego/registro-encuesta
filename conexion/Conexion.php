<?php

    class Conexion{

		static public function conectar(){

			try {
    			$link = new PDO("mysql:host=localhost;dbname=encuestas",
				"idexcc12",
				"qu6YecVrjc8o8PZ",
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
				);
				return $link;
			} catch (PDOException $e) {
			    echo 'Falló la conexión: ' . $e->getMessage();
			}
		}    	
    }

?>
