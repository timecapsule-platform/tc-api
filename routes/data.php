<?php



/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       SOURCE LOCATIONS     ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

# Get all 
$this->get("/source-locations", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new DataService();
            $items = $service->getSourceLocations($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


 

# Create from json
$this->get("/source-locations/create", function ($request, $response, $args) {

            $service = new DataService();
            $service->sourceLocationsCreateFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/source-locations/raw", function ($request, $response, $args) {

            $service = new DataService();
            $service->sourceLocationsRawToJson();

            return $response;
        })
        ->add(new Authentication());





/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       FAMILIES      ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

# Get all 
$this->get("/families", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new DataService();
            $items = $service->getFamilies($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


 

# Create from json
$this->get("/families/create", function ($request, $response, $args) {

            $service = new DataService();
            $service->familiesCreateFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/families/raw", function ($request, $response, $args) {

            $service = new DataService();
            $service->familiesRawToJson();

            return $response;
        })
        ->add(new Authentication());




/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       Plant Parts       ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

# Get all 
$this->get("/plant-parts", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new DataService();
            $items = $service->getPlantParts($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


 

# Create from json
$this->get("/plant-parts/create", function ($request, $response, $args) {

            $service = new DataService();
            $service->plantPartsCreateFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/plant-parts/raw", function ($request, $response, $args) {

            $service = new DataService();
            $service->plantPartsRawToJson();

            return $response;
        })
        ->add(new Authentication());





/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       REFERENCE SOURCES         ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

# Get all 
$this->get("/sources", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new DataService();
            $items = $service->getSources($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


 

# Create from json
$this->get("/sources/create", function ($request, $response, $args) {

            $service = new DataService();
            $service->sourcesCreateFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/sources/raw", function ($request, $response, $args) {

            $service = new DataService();
            $service->sourcesRawToJson();

            return $response;
        })
        ->add(new Authentication());





/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       DEPARTURES            ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

# Get all 
$this->get("/departures", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new DataService();
            $items = $service->getDepartures($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


 

# Create from json
$this->get("/departures/create", function ($request, $response, $args) {

            $service = new DataService();
            $service->departuresCreateFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/departures/raw", function ($request, $response, $args) {

            $service = new DataService();
            $service->departuresRawToJson();

            return $response;
        })
        ->add(new Authentication());



/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       ARRIVALS            ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

# Get all 
$this->get("/arrivals", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new DataService();
            $items = $service->getArrivals($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


 

# Create from json
$this->get("/arrivals/create", function ($request, $response, $args) {

            $service = new DataService();
            $service->arrivalsCreateFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/arrivals/raw", function ($request, $response, $args) {

            $service = new DataService();
            $service->arrivalsRawToJson();

            return $response;
        })
        ->add(new Authentication());



/////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////       SHIPS            ////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////

# Get all ships
$this->get("/ships", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new DataService();
            $items = $service->getShips($params);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


 

# Create from json
$this->get("/ships/create", function ($request, $response, $args) {

            $service = new DataService();
            $service->shipsCreateFromJson();

            return $response;
        })
        ->add(new Authentication());


# Create a correct json format from raw (as it is produced by sparql)
$this->get("/ships/raw", function ($request, $response, $args) {

            $service = new DataService();
            $service->shipsRawToJson();

            return $response;
        })
        ->add(new Authentication());


?>