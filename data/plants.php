<?php

# Get all plants
$this->get("", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new PlantService();
            $items = $service->getPlants($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


# Get plant name
$this->get("/variants/{variant}", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new PlantService();
            $item  = $service->getPlant($args["variant"]);

            return $response->getBody()->write(json_encode($item));
        })
        ->add(new Authentication());


# Create plants from json
$this->get("/create", function ($request, $response, $args) {

            $service = new PlantService();
            $service->create();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/raw", function ($request, $response, $args) {

            $service = new PlantService();
            $service->rawToJson();

            return $response;
        })
        ->add(new Authentication());


# Create a json from rdf (for locations)
$this->get("/xml", function ($request, $response, $args) {

            $service = new PlantService();
            $service->XMLtoJSON();

            return $response;
        })
        ->add(new Authentication());


?>