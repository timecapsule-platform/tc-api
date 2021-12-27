<?php

# Get all cargo
$this->get("", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new CargoService();
            $items = $service->getCargo($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


 

# Create from json
$this->get("/create", function ($request, $response, $args) {

            $service = new CargoService();
            $service->createFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/raw", function ($request, $response, $args) {

            $service = new CargoService();
            $service->rawToJson();

            return $response;
        })
        ->add(new Authentication());


?>