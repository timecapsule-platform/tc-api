<?php

# Get all locations
$this->get("", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new LocationService();
            $items = $service->getLocations($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


 

# Create plants from json
$this->get("/create", function ($request, $response, $args) {

            $service = new LocationService();
            $service->createFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/raw", function ($request, $response, $args) {

            $service = new LocationService();
            $service->rawToJson();

            return $response;
        })
        ->add(new Authentication());


?>