<?php

# Start the session
session_start();

# Load environment variables and slim framework
use Dotenv\Dotenv;
use \Slim\App;
require_once __DIR__.'/vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once "services/UserService.php";
require_once "services/PlantService.php";
require_once "services/DrugService.php";
require_once "services/LocationService.php";
require_once "services/CargoService.php";
require_once "services/DataService.php";
require_once "services/TimeCapsuleService.php";

# Authorization
require_once "middleware/Authentication.php";
require_once "middleware/Authorization.php";
require_once "middleware/MyUserAuthorization.php";



//# Configuration Options to display errors * ONLY FOR DEVELOPMENT MODE *
//$options = [
//    "settings" => [
//        "displayErrorDetails" => true
//    ],
//];
//# The configuration object  to use as an argument in slim app  * ONLY FOR DEVELOPMENT MODE *
//$config = new \Slim\Container($options);


# Error Handler
$config["errorHandler"] = function ($config) {
    return function ($request, $response, $exception) use ($config) {
        # create the response payload
        $payload = array(
            "code" => $exception->getCode(),
            "message" => $exception->getMessage());

        # prepare the response
        $response = $config["response"]
                ->withHeader("Content-Type", "application/json")
                ->withHeader("Access-Control-Allow-Origin", "*")
                ->write(json_encode($payload));

        if (get_class($exception) === "ApplicationException")
            return $response->withStatus($payload["code"]);

        return $response->withStatus(500);
    };
};


# Instantiate a Slim application
$app = new Slim\App($config);


# Middleware to set the default headers of the app's responses.
$app->add(function($request, $response, $next) {

    # add response headers here
    $response = $response
            ->withHeader("Content-type", "application/json")
            ->withHeader("Access-Control-Allow-Origin", "*")
            ->withHeader("Access-Control-Allow-Methods", "POST, GET, PUT, DELETE, OPTIONS")
            ->withHeader("Access-Control-Allow-Headers", "Content-Type");
    # add more headers here
    //$response = $response->withAddedHeader("Allow", "PUT");

    return $next($request, $response);
});

/**
 * Routes definition
 *
 * $app->get: Handles GET requests (Retrieve data)
 * $app->post: Handles POST requests (Create)
 * $app->put: Handles PUT requests (Update)
 * $app->delete: Handles DELETE requests (Delete)
 * 
 */
/**
 * Auth
 * Authorization and Authentication of Users.
 */
$app->post("/login", function ($request, $response, $args) {

    # Get user data
    $credentials = $request->getParsedBody();

    $userService = new UserService();
    $user = $userService->authenticate($credentials);
    $userService->login($user);
   
    return $response->getBody()->write(json_encode($user));
});

$app->get("/logout", function ($request, $response, $args) {

    $userService = new UserService();
    $userService->logout();
});

 

/**
 * Users
 * API to access User data.
 */
# Get All Users
$app->get("/users", function ($request, $response, $args) {

            $userService = new UserService();
            $users = $userService->getUsers($request->getQueryParams());

            return $response->getBody()->write(json_encode($users));
        })
        # this action requires authentication
        ->add(new Authentication())
        # this action requires authorization. Clearance Level: admin
        ->add(new Authorization("Administrator"));

# Create new User
$app->post("/users", function ($request, $response, $args) {

            # Get user data
            $data = $request->getParsedBody();

            $userService = new UserService();
            $user = $userService->createrUser($data);

            return $response
                    ->withStatus(201)
                    ->getBody()->write(json_encode($user));
        })
        # this action requires authentication
        ->add(new Authentication())
        # this action requires authorization. Clearance Level: admin
        ->add(new Authorization("Administrator"));

# Get specific User
$app->get("/users/{id}", function ($request, $response, $args) {

            $userService = new UserService();
            $user = $userService->getUserById($args["id"]);

            return $response->getBody()->write(json_encode($user));
        })
        ->add(new Authentication())
        ->add(new MyUserAuthorization());


# Update specific User
$app->put("/users/{id}", function ($request, $response, $args) {

            # Get user data
            $data = $request->getParsedBody();

            $userService = new UserService();
            $user = $userService->updateUser($args["id"], $data);

            return $response->getBody()->write(json_encode($user));
        })
        ->add(new Authentication())
        ->add(new Authorization("Administrator"));

# Get specific User
$app->delete("/users/{id}", function ($request, $response, $args) {

            $userService = new UserService();
            $userService->deleteUser($args["id"]);

            return $response;
        })
        ->add(new Authentication())
        ->add(new Authorization("Administrator"));

/**********************************  Companies API  *************************************************/

# Get Companies
$app->get("/companies", function ($request, $response, $args) {

            $companyService = new CompanyService();
            $companies = $companyService->getCompanies();

            return $response->getBody()->write(json_encode($companies));
        })
        # this action requires authentication
        ->add(new Authentication());
 
# Get Company by id
$app->get("/companies/{id}", function ($request, $response, $args) {

            $companyService = new CompanyService();
            $company = $companyService->getCompany($args["id"]);

            return $response->getBody()->write(json_encode($company));
        })
        # this action requires authentication
        ->add(new Authentication());


# Get a Subscriber's Companies
$app->get("/subscribers/{id}/companies", function ($request, $response, $args) {

            $companyService = new CompanyService();
            $companies = $companyService->getSubscriberCompanies($args["id"]);

            return $response->getBody()->write(json_encode($companies));
        })
        # this action requires authentication
        ->add(new Authentication());


# Set the Current company using an index to the companies array. 
$app->put("/session/company", function ($request, $response, $args)
{
        $data = $request->getParsedBody(); 
    
        $companyService = new CompanyService();
        $company = $companyService->setCurrentCompany($data["index"]);
    
        return $response->getBody()->write(json_encode($company));
         
})->add(new Authentication());

# Get the Current company. 
# Returns the current company as it is stored on the SESSION along with its INDEX in the 'companies' array.
$app->get("/session/company", function ($request, $response, $args)
{
           
        $companyService = new CompanyService();
        $company = $companyService->getCurrentCompany();
    
        return $response->getBody()->write(json_encode($company));
         
})->add(new Authentication());




/**********************************  Plants API  *************************************************/


$app->group("/users/{userId}/timecapsules", function() {
    require_once "routes/timecapsules.php";
});

 
$app->group("/plants", function() {
    require_once "routes/plants.php";
});


 
$app->group("/drugs", function() {
    require_once "routes/drugs.php";
});


$app->group("/locations", function() {
    require_once "routes/locations.php";
});

$app->group("/cargo", function() {
    require_once "routes/cargo.php";
});

$app->group("/data", function() {
    require_once "routes/data.php";
});
 
 

 
 



# Run the Slim application
$app->run();
