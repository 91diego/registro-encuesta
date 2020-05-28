<?php 

    /**
     * OBTIENE LOS DATOS DEL DEPARTAMENTO
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function departament($id) {

        // INFORMACION DEL DEPARTAMENTO
        $detailsDepartament = 'https://intranet.idex.cc/rest/117/w0qdwl5fbr0hpuf1/department.get?ID='.$id;
        // OBTIENE LA RESPUESTA DE LA API REST BITRIX
        $responseAPI = file_get_contents($detailsDepartament);

        // CAMPOS DE LA RESPUESTA
        $departament = json_decode($responseAPI, true);
        return $departament["result"];
        // FIN INFORMACION DEPARTAMENTO
    }
  
    /**
     * OBTIENE LOS DATOS DEL RESPONSABLE
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    function users($id) {

        // INFORMACION RESPONSABLE
        $detailsResponsable = 'https://intranet.idex.cc/rest/117/w0qdwl5fbr0hpuf1/user.get?ID='.$id;
        // OBTIENE LA RESPUESTA DE LA API REST BITRIX
        $responseAPI = file_get_contents($detailsResponsable);

        // CAMPOS DE LA RESPUESTA
        $responsable = json_decode($responseAPI, true);
        // FIN INFORMACION RESPOSABLE
        return $responsable["result"];
    }

    /**
     * OBTIENE EL ORIGEN DE LA NEGOCIACION
     *
     * @param  string  $name
     * @return \Illuminate\Http\Response
     */
    function source($name) {

        // INFORMACION RESPONSABLE
        $detailsResponsable = 'https://intranet.idex.cc/rest/117/w0qdwl5fbr0hpuf1/crm.status.list?FILTER[STATUS_ID]='.$name;
        // OBTIENE LA RESPUESTA DE LA API REST BITRIX
        $responseAPI = file_get_contents($detailsResponsable);

        // CAMPOS DE LA RESPUESTA
        $responsable = json_decode($responseAPI, true);
        // FIN INFORMACION RESPOSABLE
        return $responsable["result"];
    }

    /**
     * OBTIENE EL CANAL DE VENTAS
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    function purchase($id) {

        // INFORMACION RESPONSABLE
        $detailPurchase = 'https://intranet.idex.cc/rest/117/w0qdwl5fbr0hpuf1/crm.status.get?ID='.$id;
        // OBTIENE LA RESPUESTA DE LA API REST BITRIX
        $responseAPI = file_get_contents($detailPurchase);

        // CAMPOS DE LA RESPUESTA
        $purchase = json_decode($responseAPI, true);
        // FIN INFORMACION RESPOSABLE
        return $purchase["result"];
    }

    /**
     * Obtiene el nombre del desarrollo del CRM
     * @param  int  $id
     * @return string $nombreDeesarrollo
     */
    function place($id) {

        $fieldsDeals = 'https://intranet.idex.cc/rest/117/w0qdwl5fbr0hpuf1/crm.deal.fields';

        // OBTIENE LA RESPUESTA DE LA API REST BITRIX
        $responseAPI = file_get_contents($fieldsDeals);

        // CAMPOS DE LA RESPUESTA
        $fields = json_decode($responseAPI, true);

        // NUMERO DE CAMPOS EN LA POSICION DEL ARRAY
        $numberItems = count($fields['result']['UF_CRM_5D12A1A9D28ED']['items']);
        // ARRAY DE ITEMS
        $items = [];
        for ($i=0; $i < $numberItems; $i++) {

            array_push($items, [

                "id" => $fields['result']['UF_CRM_5D12A1A9D28ED']['items'][$i]["ID"],
                "nombre" => $fields['result']['UF_CRM_5D12A1A9D28ED']['items'][$i]["VALUE"]
            ]);
        }

        for ($i=0; $i < count($items); $i++) { 
            
            if ($items[$i]["id"] == $id) {

                $nombreDesarrollo = $items[$i]["nombre"];
                return $nombreDesarrollo;
            }
        }
        return $nombreDesarrollo;
    }

?>