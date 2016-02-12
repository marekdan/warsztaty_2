<?php

/*

CREATE TABLE Messages (
    id int AUTO_INCREMENT,
    sender_id int,
    receiver_id int,
    message varchar(255),
    message_date datetime,
    status int default 1,
    PRIMARY KEY (id),
    FOREIGN KEY (sender_id) REFERENCES Users (id)
    ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES Users (id)
    ON DELETE CASCADE
    );

*/

class Message {

    static private $connection;
    private $id;
    private $senderId;
    private $receiverId;
    private $message;
    private $date;
    private $status;

    static public function SetConnection(mysqli $newConnection) {
        Message::$connection = $newConnection;
    }

    static public function sendMessage($senderId, $receiverId, $newMessage, $newDate) {
        $sql = "INSERT INTO Messages (sender_id, receiver_id, message, message_date) VALUES ('$senderId', '$receiverId', '$newMessage', '$newDate')";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            echo 'Wiadomość wysłana';
        }
        else {
            echo 'Wystąpił problem z wysłaniem wiadomości';
        }
    }

    public function loadAllReceivedMessages($userId) {
        $ret = [];
        $sql = "SELECT * FROM Messages WHERE receiver_id='$userId' ORDER BY message_date desc";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                $message = new Message($row['id'], $row['sender_id'], $row['receiver_id'], $row['message'], $row['message_date'], $row['status']);
                $ret[] = $message;
            }

            return $ret;
        }

        return false;
    }

    public function loadAllSendMessages($userId) {
        $ret = [];
        $sql = "SELECT * FROM Messages WHERE sender_id='$userId' ORDER BY message_date desc";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                $message = new Message($row['id'], $row['sender_id'], $row['receiver_id'], $row['message'], $row['message_date'], $row['status']);
                $ret[] = $message;
            }

            return $ret;
        }

        return false;
    }

    public function __construct($newId, $newSenderId, $newReceiverId, $newMessage, $newDate, $newStatus) {
        $this->id = $newId;
        $this->setSenderId($newSenderId);
        $this->setReceiverId($newReceiverId);
        $this->setMessage($newMessage);
        $this->setDate($newDate);
        $this->setStatus($newStatus);
    }

    public function saveToDb() {
        $sql = "UPDATE Messages SET status='$this->status' WHERE id = '$this->id'";
        $result = self::$connection->query($sql);
        if ($result === true) {
            return true;
        }

        return false;
    }

    public function setSenderId($newSenderId) {
        $this->senderId = $newSenderId;
    }

    public function setReceiverId($newReceiverId) {
        $this->receiverId = $newReceiverId;
    }

    public function setMessage($newMessage) {
        $this->message = $newMessage;
    }

    public function setDate($newDate) {
        $this->date = $newDate;
    }

    public function setStatus($newStatus) {
        $this->status = $newStatus;
    }

    public function getId() {
        return $this->id;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function getReceiverId() {
        return $this->receiverId;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getDate() {
        return $this->date;
    }

    public function getStatus() {
        return $this->status;
    }

}