<?php

/*
 * SpeCtre
 * Alexandros Preventis
 * Feb 4, 2016
 */

require_once "dao/UserDao.php";
require_once "exceptions/ApplicationException.php";
require_once "lib/PasswordHash.php";
require_once "Service.php";

/**
 * Description of LoginService
 *
 * @author apreventis
 */
class UserService extends Service {

    private $userDao;
 

    public function __construct() {
        parent::__construct();
        
        $this->userDao = new UserDao($this->mysql->getConnection());
    }

    /**
     * 
     * @param type $credentials
     * @return Object
     * @throws ApplicationException
     */
    public function authenticate($credentials) {

        # If the username or password is empty redirect the user to the login page and display an error.
        if (empty($credentials["username"]) || empty($credentials["password"]))
            throw new ApplicationException("Wrong username or password", 401);

        # User can login with his username or his email address.
        if (!filter_var($credentials["username"], FILTER_VALIDATE_EMAIL))
        {
            $user = $this->userDao->getUserByUsername(trim($credentials["username"]));
        }
        else
        {
            $user = $this->userDao->getUserByEmail(trim($credentials["username"]));
        }

        # if the user does not exist
        if (!$user)
            throw new ApplicationException("Wrong username or password", 401);
        # if the user is deactivated
        else if ($user->is_active == false)
            throw new ApplicationException("Your account has been deactivated. <br/> Please, contact the system administrator.", 401);
        # if the user have too many failed logins deactivate him and redirect.
        else if ($user->failed_logins > 9) {
            $this->userDao->deactivateUser($user->id);

            throw new ApplicationException("Your account has been deactivated. <br/> Please, contact the system administrator.", 401);
        }
        # if the password is incorect increase the failed login counter and redirect
        else if (!validate_password($credentials["password"], $user->password_hash)) {
            $this->userDao->increamentFailedLoginCounter($credentials["username"]);

            throw new ApplicationException("Wrong username or password", 401);
        }
        # if the credentials are correct store the user in the session and reset the failed login counter.
        else {
            $this->userDao->resetFailedLoginCounter($user->id);
            # unset some fields before returning the object.
            unset($user->is_active);
            unset($user->password_hash);
            unset($user->failed_logins);

            return $user;
        }
    }

    /**
     * 
     * @param type $user
     */
    public function login($user) 
    {

        

        if(isset($user))
        {
            $_SESSION["user"] = $user;
        }
        else
        {
           throw new ApplicationException("Login Failed", 401);
        }
           
    }

    /**
     * 
     */
    public function logout() {

        unset($_SESSION["user"]);
        session_destroy();
    }

    /**
     * 
     * @param type $user
     * @return Object
     * @throws ApplicationException
     */
    public function createrUser($user) {
        # remove extra space on username and email
        $user["username"] = trim($user["username"]);
        $user["email"] = trim($user["email"]);

        # check provided data validity
        if (empty($user["username"]))
            throw new ApplicationException("Username is empty.", 401);
        else if ((empty($user["password"]) || empty($user["password_repeat"])))
            throw new ApplicationException("Password is empty.", 401);
        else if ($user["password"] !== $user["password_repeat"])
            throw new ApplicationException("The passwords do not match.", 401);
        else if (strlen($user["password"]) < 6)
            throw new ApplicationException("Password is too short.", 401);
        else if (strlen($user["username"]) > 64 || strlen($user["username"]) < 2)
            throw new ApplicationException("Username has wrong length. Must be over 2 characters and less than 64.", 401);
        else if (!preg_match('/^[a-zA-Z0-9-_\.]{2,64}$/i', $user["username"]))
            throw new ApplicationException("Username is in wrong format. Use only letters, numbers, \".\", \"-\" or \"_\".", 401);
        else if (empty($user["email"]))
            throw new ApplicationException("Email is empty.", 401);
        else if (strlen($user["email"]) > 100)
            throw new ApplicationException("Email is too long", 401);
        else if (!filter_var($user["email"], FILTER_VALIDATE_EMAIL))
            throw new ApplicationException("Invalid email format", 401);
        # finally if all the above checks are ok
        else if ($this->userDao->usernameExists($user["username"]))
            throw new ApplicationException("Username is used by another user. Please choose another username.", 409);
        else if ($this->userDao->emailExists($user["email"]))
            throw new ApplicationException("Email address is already registered. Please choose another email address.", 409);
        

        $password_hash = create_hash($user["password"]);
        $user_id = $this->userDao->createUser(array(
            "first_name" => $user["first_name"],
            "last_name" => $user["last_name"],
            "username" => $user["username"],
            "password_hash" => $password_hash,
            "email" => $user["email"],
            "register_ip" => filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP),
            "role" => $user["role"]));

        # remove the passwords for safety before returning the array
        unset($user["password"], $user["password_repeat"]);
        $user["id"] = $user_id;

        return $user;
    }

    /**
     * 
     * @return Array
     */
    public function getUsers($filters) {

        # get all users
        $users = $this->userDao->getUsers($filters);

        return $users;
    }

    /**
     * 
     * @param type $id
     * @return Object
     */
    public function getUserById($id) {

        $user = $this->userDao->getUserById($id);
        
        if(!$user)
            throw new ApplicationException("User not found.", 404);
        
        # remove the password for safety before returning the array
        unset($user->password_hash);

        return $user;
    }

    /**
     * 
     * @param type $id
     * @return Object
     */
    public function updateUser($id, $user) {

        if (empty($user["username"]))
            throw new ApplicationException("Username is empty.", 401);
        else if (empty($user["password"]))
            throw new ApplicationException("Password is empty.", 401);
        else if (strlen($user["password"]) < 6)
            throw new ApplicationException("Password is too short.", 401);
        else if (strlen($user["username"]) > 64 || strlen($user["username"]) < 2)
            throw new ApplicationException("Username has wrong length. Must be over 2 characters and less than 64.", 401);
        else if (!preg_match('/^[a-zA-Z0-9-_\.]{2,64}$/i', $user["username"]))
            throw new ApplicationException("Username is in wrong format. Use only letters, numbers, \".\", \"-\" or \"_\".", 401);
        else if (empty($user["email"]))
            throw new ApplicationException("Email is empty.", 401);
        else if (strlen($user["email"]) > 100)
            throw new ApplicationException("Email is too long", 401);
        else if (!filter_var($user["email"], FILTER_VALIDATE_EMAIL))
            throw new ApplicationException("Invalid email format", 401);

        # encrypt the password before storing it to the database
        $user["password_hash"] = create_hash($user["password"]);

        $this->userDao->updateUser($id, $user);
        # remove the password for safety before returning the array
        unset($user["password_hash"]);

        return $user;
    }
    
    
    /**
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteUser($id){
        
        if(!$this->userDao->deleteUser($id))
            throw new ApplicationException("User not deleted.", 500);
        
        return true;
    }

}
