<?php

/*

CREATE TABLE Tweets (
    id int AUTO_INCREMENT,
    user_id int,
    tweet varchar(140),
    post_date datetime,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users (id)
    ON DELETE CASCADE
    );

*/

class Tweet {

    static private $connection;
    private $id;
    private $userId;
    private $text;
    private $date;

    static public function SetConnection(mysqli $newConnection) {
        Tweet::$connection = $newConnection;
    }

    static public function create($userId, $tweet, $date) {
        $sql = "INSERT INTO Tweets (user_id, tweet, post_date) VALUES ($userId, '$tweet', '$date')";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            echo 'Tweet został dodany';
        }
        else {
            echo 'Wystąpił problem z dodaniem tweeta';
        }
    }

    public function loadSingleTweet($tweetId) {
        $sql = "SELECT * FROM Tweets WHERE id='$tweetId' ";
        $result = self::$connection->query($sql);

        if ($result !== false) {
            $row = $result->fetch_assoc();
            $tweet = new Tweet($row['id'], $row['user_id'], $row['tweet'], $row['post_date']);

            return $tweet;
        }

        return false;
    }

    public function loadAllTweets($userId) {
        $ret = [];
        $sql = "SELECT * FROM Tweets WHERE user_id='$userId' ORDER BY post_date DESC";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                $ret[] = $row;
            }

            return $ret;
        }

        return false;
    }

    public function __construct($newId, $newUserId, $newText, $newDate) {
        $this->id = $newId;
        $this->setUserId($newUserId);
        $this->setText($newText);
        $this->setDate($newDate);
    }

    public function getId() {
        return $this->id;
    }

    public function userId() {
        return $this->userId;
    }

    public function getText() {
        return $this->text;
    }

    public function getDate() {
        return $this->date;
    }

    public function setUserId($newUserId) {
        $this->userId = $newUserId;
    }

    public function setText($newText) {
        $this->text = $newText;
    }

    public function setDate($newDate) {
        $this->date = $newDate;
    }

}