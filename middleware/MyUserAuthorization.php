<?php

class MyUserAuthorization {
    public function __construct() {
    }

    public function __invoke($request, $response, $next) {
        $requestedUserId = $request->getAttribute("id");

        # get the user's role
        $myUserId = filter_var($_SESSION["user"]->id);

        # Ensure that the user has the permission required to access the resource
        if ($myUserId != $requestedUserId)
            throw new ApplicationException("You do not have the permissions required to access other users' resources.", 403);

        return $next($request, $response);
    }

}
