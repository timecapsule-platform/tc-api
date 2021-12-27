<?php

# Get all drugs
$this->get("", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new DrugService();
            $items = $service->getDrugs($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


# Get drug name using a variant
$this->get("/variants/{variant}", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new DrugService();
            $item  = $service->getDrug($args["variant"]);

            return $response->getBody()->write(json_encode($item));
        })
        ->add(new Authentication());


# Create plants from json
$this->get("/create", function ($request, $response, $args) {

            $service = new DrugService();
            $service->createDrugsFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/raw", function ($request, $response, $args) {

            $service = new DrugService();
            $service->rawToJson();

            return $response;
        })
        ->add(new Authentication());


?>