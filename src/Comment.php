<?php

/*

CREATE TABLE Comments (
    id int AUTO_INCREMENT,
    tweet_id int,
    user_id int,
    comment_text varchar(60),
    comment_date datetime,
    PRIMARY KEY (id),
    FOREIGN KEY (tweet_id) REFERENCES Tweets (id)
    ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users (id)
    ON DELETE CASCADE
    );

*/

class Comment {

    static private $connection;
    private $id;
    private $userId;
    private $postId;
    private $commentDate;
    private $text;

    static public function SetConnection(mysqli $newConnection) {
        Comment::$connection = $newConnection;
    }

    static public function addComment($tweetId, $userId, $newMessage, $newDate) {
        $sql = "INSERT INTO Comments (tweet_id, user_id, comment_text, comment_date) VALUES ('$tweetId', '$userId', '$newMessage', '$newDate')";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            echo 'Komentarz został dodany';
        }
        else {
            echo 'Wystąpił problem z dodaniem komentarza';
        }
    }

    public function loadAllComments($tweetId) {
        $ret = [];
        $sql = "SELECT * FROM Comments WHERE tweet_id='$tweetId' ORDER BY comment_date desc";
        $result = self::$connection->query($sql);
        if ($result !== false) {
            while ($row = $result->fetch_assoc()) {
                $comment = new Comment($row['id'], $row['user_id'], $row['tweet_id'], $row['comment_date'], $row['comment_text']);
                $ret[] = $comment;
            }

            return $ret;
        }
    }

    public function __construct($newId, $newUserId, $newPostId, $newCommentDate, $newText) {
        $this->id = $newId;
        $this->setUserId($newUserId);
        $this->setPostId($newPostId);
        $this->setCommentDate($newCommentDate);
        $this->setText($newText);
    }

    public function setUserId($newUserId) {
        $this->userId = $newUserId;
    }

    public function setPostId($newPostId) {
        $this->postId = $newPostId;
    }

    public function setCommentDate($newCommentDate) {
        $this->commentDate = $newCommentDate;
    }

    public function setText($newText) {
        $this->text = $newText;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getPostId() {
        return $this->postId;
    }

    public function getCommentDate() {
        return $this->commentDate;
    }

    public function getText() {
        return $this->text;
    }

}