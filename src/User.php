<?php

/*

CREATE TABLE Users(
    id int AUTO_INCREMENT,
    name varchar(255),
    email varchar(255) UNIQUE,
    password char(60),
    description varchar(255),
    PRIMARY KEY (id)
    );

 */

class User {

    private $id;
    private $name;
    private $email;
    private $description;

    static private $connection;

    static public function SetConnection(mysqli $newConnection) {
        User::$connection = $newConnection;
    }

    static public function RegisterUser($newName, $newEmail, $password1, $password2, $newDescription) {
        if ($password1 !== $password2) {
            return false;
        }

        $options = [
            'cost' => 11,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
        ];
        $hassedPassword = password_hash($password1, PASSWORD_BCRYPT, $options);
        $sql = "INSERT INTO Users(name, email, password, description)
                VALUES ('$newName', '$newEmail', '$hassedPassword', '$newDescription')";

        $result = self::$connection->query($sql);
        if ($result === true) {
            $newUser = new User(self::$connection->insert_id, $newName, $newEmail, $newDescription);

            return $newUser;
        }

        return false;
    }

    static public function logInUser($email, $password) {
        $sql = "SELECT * FROM Users WHERE email like '$email'";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $isPasswordOk = password_verify($password, $row["password"]);
                if ($isPasswordOk === true) {
                    $user = new User($row['id'], $row['name'], $row['email'], $row['description']);

                    return $user;
                }
            }
        }

        return false;
    }

    static public function getUserById($byId) {
        $sql = "SELECT * FROM Users WHERE id='$byId'";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $user = new User($row['id'], $row['name'], $row['email'], $row['description']);

                return $user;
            }
        }
    }

    static public function GetAllUsers() {
        $ret = [];
        $sql = "SELECT * FROM Users";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $user = new User($row['id'], $row['name'], $row['email'], $row['description']);
                    $ret[] = $user;
                }

                return $ret;
            }
        }
    }

    static public function updateDescription($userId, $newDesc) {
        $sql = "UPDATE Users SET description='$newDesc' WHERE id ='$userId'";
        $result = self::$connection->query($sql);
        if ($result === true) {
            return true;
        }

        return false;
    }

    public function saveToDb() {
        $sql = "UPDATE Users SET description='$this->description' WHERE id = '$this->id'";
        $result = self::$connection->query($sql);
        if ($result === true) {
            return true;
        }

        return false;
    }

    static public function updatePassword($userId, $newPassword) {
        $options = [
            'cost' => 11,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
        ];
        $hassedPassword = password_hash($newPassword, PASSWORD_BCRYPT, $options);

        $sql = "UPDATE Users SET password='$hassedPassword' WHERE id ='$userId'";
        $result = self::$connection->query($sql);
        if ($result === true) {
            return true;
        }

        return false;
    }

    static public function deleteUser($userId) {
        $sql = "DELETE FROM Users WHERE id='$userId'";
        $result = self::$connection->query($sql);
        if ($result === true) {
            return true;
        }

        return false;
    }

    public function __construct($newId, $newName, $newEmail, $newDescription) {
        $this->id = intval($newId);
        $this->name = $newName;
        $this->email = $newEmail;
        $this->setDescription($newDescription);
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($newDescription) {
        if (is_string($newDescription)) {
            $this->description = $newDescription;
        }
    }

}
