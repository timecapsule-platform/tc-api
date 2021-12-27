<?php

require_once "dao/Dao.php";
require_once "exceptions/ApplicationException.php";

class UserDao extends Dao {

    public static $TABLE_NAME = "user";
    public static $ALLOWED_FILTERS = array("id", "first_name", "middle_name",
        "last_name", "gender", "username", "email", "is_active", "failed_logins",
        "register_ip", "datetime_registered", "role", "limit", "offset"
    );
    private $connection;

    public function __construct($conn) {
        parent::__construct();
        $this->connection = $conn;
    }

    /**
     * Creates a new user
     * @return The user id.
     * */
    public function createUser($user) {

        $queryString = "
            INSERT INTO 
            `user` (first_name, last_name, username, password_hash, email, register_ip, role)
            VALUES
            (:first_name, :last_name, :username, :password_hash, :email, :register_ip, :role)
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":first_name", $user["first_name"], PDO::PARAM_STR);
        $query->bindValue(":last_name", $user["last_name"], PDO::PARAM_STR);
        $query->bindValue(":username", $user["username"], PDO::PARAM_STR);
        $query->bindValue(":password_hash", $user["password_hash"], PDO::PARAM_STR);
        $query->bindValue(":email", $user["email"], PDO::PARAM_STR);
        $query->bindValue(":register_ip", $user["register_ip"], PDO::PARAM_STR);
        $query->bindValue(":role", $user["role"], PDO::PARAM_STR);
        $query->execute();

        return $this->connection->lastInsertId();
    }

    /**
     * Update Username
     * */
    public function updateUsername($id, $username) {

        $queryString = "
            UPDATE `user`
            SET username = :username
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":username", $username, PDO::PARAM_STR);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

    /**
     * Update user email
     * */
    public function updateEmail($id, $email) {
        $queryString = "
            UPDATE `user`
            SET email = :email
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":email", $email, PDO::PARAM_STR);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

    /**
     * Update password hash
     * */
    public function updatePassword($id, $password_hash) {
        $queryString = "
            UPDATE `user`
            SET password_hash = :password_hash
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":password_hash", $password_hash, PDO::PARAM_STR);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

    /**
     * Delete user
     * */
    public function deleteUser($id) {

        $queryString = "
            DELETE FROM `user`
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

    /**
     * Increment failed login attempts
     * */
    public function increamentFailedLoginCounter($username) {

        $queryString = "
            UPDATE `user`
            SET failed_logins = failed_logins + 1
            WHERE username = :username OR email = :username
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":username", $username, PDO::PARAM_STR);
        $result = $query->execute();
        
        return  $result;
    }

    /**
     * Reset failed login counter to 0
     * */
    public function resetFailedLoginCounter($id) {
        $queryString = "
            UPDATE `user`
            SET failed_logins = 0
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

    /**
     * 
     * Checks if a user is active;
     *
     * */
    public function isActiveUser($id) {

        $queryString = "
            SELECT is_active
            FROM `user`
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        return $result->is_active == true;
    }

    /**
     * 
     * Activate User (also sets failed_logins = 0).
     *
     * */
    public function activateUser($id) {

        $queryString = "
            UPDATE `user`
            SET is_active = 1, failed_logins = 0
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

    /**
     * 
     * Deactivate User
     *
     * */
    public function deactivateUser($id) {

        $queryString = "
            UPDATE `user`
            SET is_active = 0
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

    /**
     * Get user by by username
     * */
    public function getUserByUsername($username) {

        $queryString = "
            SELECT id, name, surname, email, role, 
            is_active, password_hash, failed_logins
            FROM `user`
            WHERE username = :username
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":username", $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        return $result;
    }

    /**
     * Get user by by username
     * */
    public function getUserByEmail($email) {

        $queryString = "
            SELECT id, name, surname, email, role, 
            is_active, password_hash, failed_logins
            FROM `user`
            WHERE email = :email
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":email", $email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        return $result;
    }

    /**
     * Get user by id
     * */
    public function getUserById($id) {

        $queryString = "
            SELECT *
            FROM `user`
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        return $result;
    }

    /**
     * Check if username exists
     * */
    public function usernameExists($username) {

        $queryString = "
            SELECT id
            FROM `user`
            WHERE username = :username
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":username", $username, PDO::PARAM_STR);
        $query->execute();

        return $query->rowCount() > 0;
    }

    /**
     * Check if email exists
     * */
    public function emailExists($email) {

        $queryString = "
            SELECT id
            FROM `user`
            WHERE email = :email
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":email", $email, PDO::PARAM_STR);
        $query->execute();

        return $query->rowCount() > 0;
    }

    /**
     * Get Users filtered
     * @return array
     */
    public function getUsers($filters) {

        # get the column names that will be used as filters
        $column_names = array_keys($filters);

        /**
         * To ensure that the filters contain ONLY column names, we calculate 
         * the difference between the two arrays [A] - [B]. If the difference is
         * not the empty set, then there are filters that are not included in
         * the table's columns.
         */
        if (count(array_diff($column_names, static::$ALLOWED_FILTERS)))
            throw new ApplicationException("Invalid query parameters.", 400);
        
        $queryString = $this->queryBuilder
                ->select("user", "id", "first_name", "middle_name", "last_name",
                        "gender", "username", "email", "is_active", 
                        "failed_logins", "register_ip", "datetime_registered",
                        "role")
                ->from("user")
                ->filter($filters)
                ->build();

        $query = $this->connection->prepare($queryString);

        foreach ($filters as $key => $value) {
            switch ($key) {
                # the INT valued columns
                case "failed_logins" || "is_active":
                    $query->bindValue(":" . $key, $value, PDO::PARAM_INT);
                    break;
                # The rest of the columns
                default:
                    $query->bindValue(":" . $key, $value, PDO::PARAM_STR);
                    break;
            }
        }
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Update User
     * */
    public function updateUser($id, $user) {

        $queryString = "
            UPDATE user
            SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, 
            gender = :gender, username = :username, password_hash = :password_hash,
            email = :email, is_active = :is_active, failed_logins = :failed_logins, 
            register_ip = :register_ip, datetime_registered = :datetime_registered,
            role = :role
            WHERE id = :id
        ";

        $query = $this->connection->prepare($queryString);
        $query->bindValue(":first_name", $user["first_name"], PDO::PARAM_STR);
        $query->bindValue(":middle_name", $user["middle_name"], PDO::PARAM_STR);
        $query->bindValue(":last_name", $user["last_name"], PDO::PARAM_STR);
        $query->bindValue(":gender", $user["gender"], PDO::PARAM_STR);
        $query->bindValue(":username", $user["username"], PDO::PARAM_STR);
        $query->bindValue(":password_hash", $user["password_hash"], PDO::PARAM_STR);
        $query->bindValue(":email", $user["email"], PDO::PARAM_STR);
        $query->bindValue(":is_active", $user["is_active"], PDO::PARAM_INT);
        $query->bindValue(":failed_logins", $user["failed_logins"], PDO::PARAM_INT);
        $query->bindValue(":register_ip", $user["register_ip"], PDO::PARAM_STR);
        $query->bindValue(":datetime_registered", $user["datetime_registered"], PDO::PARAM_STR);
        $query->bindValue(":role", $user["role"], PDO::PARAM_STR);
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }

}
