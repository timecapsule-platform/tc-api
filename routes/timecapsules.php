<?php

# Get all timecapsules
$this->get("", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new TimeCapsuleService();
            $items = $service->getTimeCapsules($args["userId"]);

            return $response->getBody()->write(json_encode($items));
        })
        ->add(new Authentication());


# Get time capsule
$this->get("/{id}", function ($request, $response, $args) {

            $params = $request->getQueryParams();
    
            $service = new TimeCapsuleService();
            $item  = $service->getTimeCapsule($args["id"]);

            return $response->getBody()->write(json_encode($item));
        })
        ->add(new Authentication());


# Delete timecapsule
$this->delete("/{id}", function ($request, $response, $args) {

            
            $service = new TimeCapsuleService();
            $service->deleteTimeCapsule($args["id"]);

            return $response;
        })
        ->add(new Authentication());


# Create a new TimeCapsule
$this->post("", function ($request, $response, $args) {

            # Get the new item data
            $item = $request->getParsedBody();
    
            $service = new TimeCapsuleService();
            $data = $service->createTimeCapsule($item);

            return $response->getBody()->write(json_encode($data));
        })
        ->add(new Authentication());

 
 


?>