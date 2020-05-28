<?php

    header('Access-Control-Allow-Origin: *');
    include 'funciones/Log.php';
    include 'funciones/DatosNegociacion.php';
    include 'funciones/OperacionesDb.php';

    /**
    * SE REGISTRA LA NEGOCIACION Y EL ENVIO
    * EN LA BASE DE DATOS DE ENCUESTAS
    * @param string $name -> nombre de le encuesta
    * @param int $fase -> id de la fase asociada a la encuesta
    * @param int id -> id de la negociacion asociada a la encuesta
    */

	/* ESCRIBE HISTORIAL DE PETICIONES A LA APP EN UN LOG */
	writeToLog($_REQUEST, 'incoming');

    // SE ESTABLECE LA ZONA HORARIA
    date_default_timezone_set('America/Mexico_City');
    // ALMACENA LOS DATOS QUE SE PASARAN A LA VISTA
    $data = [];
    
    // SE ASIGNA LA FECHA ACTUAL
    $currentDate = date('Y m d h:i:s A');

    /* INFORMACION DEL DEAL */
    // OBTIENE LA INFORMACION DE LA NEGOCIACION
    $detailsDeal = 'https://intranet.idex.cc/rest/117/w0qdwl5fbr0hpuf1/crm.deal.get?ID='.$_REQUEST["id"];

    // OBTIENE LA RESPUESTA DE LA API REST BITRIX
    $responseAPI = file_get_contents($detailsDeal);

    // CAMPOS DE LA RESPUESTA
    $deal = json_decode($responseAPI, true);
    /* FIN INFORMACION DEAL */

    // CONTIENE LOS DATOS DEL RESPONSABLE
    $user = users($deal["result"]["ASSIGNED_BY_ID"]);

    // CONTIENE LOS DETALLES DEL DEPARTAMENTO
    $departament = departament($user[0]["UF_DEPARTMENT"][0]);

    // CONTIENE LOS DATOS DEL GERENTE
    $manager = users($departament[0]["UF_HEAD"]);

    // CONTIENE EL CANAL DE VENTAS
    $purchase = purchase($deal["result"]["UF_CRM_5D03F07FB6F84"]);

    // CONTIENE EL ORIGEN DE LA VENTA
    $source = source($deal["result"]["SOURCE_ID"]);

    // CONTIENE EL NOMBRE DEL DESARROLLO
    $place = place($deal["result"]["UF_CRM_5D12A1A9D28ED"]);

    $negociacion = explode(": ", $deal["result"]["TITLE"]);
    array_push($data, [
        "id_negociacion" => $_REQUEST["id"],
        "negociacion" => strtoupper($negociacion[1]),
        "desarrollo" => strtoupper($place),
        "responsable" => strtoupper($user[0]["NAME"]." ".$user[0]["LAST_NAME"]),
        "puesto" => strtoupper($user[0]["WORK_POSITION"]),
        "departamento" => strtoupper($departament[0]["NAME"]),
        "gerente_responsable" => strtoupper($manager[0]["NAME"]." ".$manager[0]["LAST_NAME"]),
        "origen" => strtoupper($source[0]["NAME"]),
        "canal_ventas" =>  strtoupper($purchase["NAME"]),
    ]);

    writeToLog($data, ' DATA CRM ');
    // BUSCAMOS LA NEGOCIACION EN LA BASE DE DATOS
    $findDeal = findDeal("negociaciones", $_REQUEST["id"]);
    // SI LA NEGOCIACION NO EXISTE, SE AGREGA EL REGISTRO
    if (empty($findDeal)) {
        
        $insertInfoDeal = InsertDeal($data, "negociaciones");
        echo "Negociacion agregada";
    } else {

        echo 'Ya existe un registro';
    }

    // OBTENEMOS EL ID DE LA ENCUESTA
    $surveyId = SurveyData("encuestas", $_REQUEST["name"], $_REQUEST["fase"]);

    // BUSCAMOS EL ENVIO DE LA ENCUESTA
    $surveyShipping = FindSurveyShipping("envio_encuestas", $_REQUEST["id"], $surveyId["id"]);
    // writeToLog($insertInfoDeal, 'FIND SURVEY ID ');
    print_r($surveyId["id"]);
    // VALIDAMOS QUE EXISTA O NO ENVIO DE LA ENCUESTA
    if (empty($surveyShipping)) {
        
        $shippingDate = ShippingDate("envio_encuestas", $_REQUEST["id"], $surveyId["id"], $currentDate);
        echo "Envio registrado";
    } else {

        echo "La encuesta ya fue enviada";
    }
?>