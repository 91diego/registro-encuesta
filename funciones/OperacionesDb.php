<?php 

	require_once "conexion/Conexion.php";

	function c() {

		return Conexion::conectar();
	}

	/**
     * INSERTA LOS DATOS DE LA NEGOCIACION EN LA TABLA
     *
     * @param  array  $data -> informacion de la negociacion
     * @param  string  $table
     */
	function InsertDeal($data, $table) {

		$insertDeal = "INSERT INTO $table (id_negociacion, cliente, desarrollo, responsable, puesto_responsable, departamento_responsable, gerente_responsable, origen, canal_ventas) VALUES (:id_negociacion, :cliente, :desarrollo, :responsable, :puesto_responsable, :departamento_responsable, :gerente_responsable, :origen, :canal_ventas)";
		$stmt = Conexion::conectar()->prepare($insertDeal);
		$stmt->bindParam(":id_negociacion", $data[0]['id_negociacion'], PDO::PARAM_STR);
		$stmt->bindParam(":cliente", $data[0]['negociacion'], PDO::PARAM_STR);
		$stmt->bindParam(":desarrollo", $data[0]['desarrollo'], PDO::PARAM_STR);
		$stmt->bindParam(":responsable", $data[0]['responsable'], PDO::PARAM_STR);
		$stmt->bindParam(":puesto_responsable", $data[0]['puesto'], PDO::PARAM_STR);
		$stmt->bindParam(":departamento_responsable", $data[0]['departamento'], PDO::PARAM_STR);
		$stmt->bindParam(":gerente_responsable", $data[0]['gerente_responsable'], PDO::PARAM_STR);
		$stmt->bindParam(":origen", $data[0]['origen'], PDO::PARAM_STR);
		$stmt->bindParam(":canal_ventas", $data[0]['canal_ventas'], PDO::PARAM_STR);

		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}

		$stmt->close();
		$stmt = null;
	}

	/**
     * BUSCA EL REGISTRO EN LA BASE DE DATOS
     *
     * @param  int  $id -> id de la negociacion
     * @param  string  $table
     */
	function FindDeal($table, $id) {

		$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE id_negociacion = :id");
		$stmt->bindParam(":id", $id, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch();
		$stmt -> close();
		$stmt = null;
	}

	/**
     * REGRESA EL ID DE LA ENCUESTA
     *
     * @param  string  $name -> nombre de la encuesta
     * @param  int  $phase -> id de la fase
     * @param  string  $table -> nombre de la tabla
     */
	function SurveyData($table, $name, $phase) {


		$stmt = Conexion::conectar()->prepare("SELECT id FROM $table WHERE nombre = :encuesta
			AND fase_id = :phase");
		$stmt->bindParam(":encuesta", $name, PDO::PARAM_STR);
		$stmt->bindParam(":phase", $phase, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch();
		$stmt -> close();
		$stmt = null;
	}

	/**
     * BUSCA SI YA SE REALIZO EL ENVIO DE LA ENCUESTA
     *
     * @param  int $id -> id de la negociacion
     * @param  int $surveyId -> id de la encuesta
     * @param  string $table
     */
	function FindSurveyShipping($table, $id, $surveyId) {

		$stmt = Conexion::conectar()->prepare("SELECT * FROM $table WHERE negociacion_id = :id
			AND encuesta_id = :surveyId");
		$stmt->bindParam(":id", $id, PDO::PARAM_STR);
		$stmt->bindParam(":surveyId", $surveyId, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt->fetch();
		$stmt -> close();
		$stmt = null;
	}

	/**
     * INSERTA EL ENVIO EN LA BASE DE DATOS
     *
     * @param  int $id -> id de la negociacion
     * @param  int $surveyId -> id de la encuesta
     * @param  string $surveyShippingDate -> fecha de envio     
     * @param  string $table
     */
	function ShippingDate($table, $id, $surveyId, $surveyShippingDate) {

		$shippingStatus = "ENVIADO";
		$responseStatus = "PENDIENTE";
		$responseDate = "SIN RESPUESTA";
		$numberShipments = 1;

		$insertDeal = "INSERT INTO $table (encuesta_id, negociacion_id, estatus_envio, fecha_envio, numero_envios, estatus_respuesta, fecha_respuesta) VALUES (:encuesta_id, :negociacion_id, :estatus_envio, :fecha_envio, :numero_envios, :estatus_respuesta, :fecha_respuesta)";
		$stmt = Conexion::conectar()->prepare($insertDeal);
		$stmt->bindParam(":encuesta_id", $surveyId, PDO::PARAM_STR);
		$stmt->bindParam(":negociacion_id", $id, PDO::PARAM_STR);
		$stmt->bindParam(":estatus_envio", $shippingStatus, PDO::PARAM_STR);
		$stmt->bindParam(":fecha_envio", $surveyShippingDate, PDO::PARAM_STR);
		$stmt->bindParam(":numero_envios", $numberShipments, PDO::PARAM_STR);
		$stmt->bindParam(":estatus_respuesta", $responseStatus, PDO::PARAM_STR);
		$stmt->bindParam(":fecha_respuesta", $responseDate, PDO::PARAM_STR);

		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}

		$stmt->close();
		$stmt = null;
	}

/*	function obtenerRegistros($desarrollo, $tabla) {

		//print_r($desarrollo); exit;
		
		$sqlConsulta = "SELECT nombre, cantidad AS 'cantidad stock' FROM $tabla WHERE desarrollo = :DESARROLLO";

		$stmt = Conexion::conectar()->prepare($sqlConsulta);
		$stmt->bindParam(":DESARROLLO",$desarrollo, PDO::PARAM_STR);
		$stmt->execute();
		return $stmt -> fetchAll();
		$stmt -> close();
		$stmt = null;

	}*/
?>