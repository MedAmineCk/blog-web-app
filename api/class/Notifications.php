<?php
class Notification
{

    // Connection
    private $conn;

    // Table
    private $db_table = "notifications";

    // Columns
    public $id_notification;
    public $id_admin;
    public $target;
    public $notification;
    public $is_open;
    public $notifCount;
    public $id_target;
    public $content;
    public $datetime;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getLinkedNotifications()
    {
        $sqlQuery = "SELECT * FROM notifications WHERE target = :target AND id_target = :id_target ORDER BY id_notification DESC";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_target = htmlspecialchars(strip_tags($this->id_target));
        $this->target = htmlspecialchars(strip_tags($this->target));

        // bind data
        $stmt->bindParam(":id_target", $this->id_target);
        $stmt->bindParam(":target", $this->target);

        $stmt->execute();
        return $stmt;
    }

    // GET Count
    public function getSingleNotificationsCount()
    {
        $sqlQuery = "SELECT COUNT(id_notification) as notifCount FROM notifications WHERE target = :target AND id_target = :id_target AND is_open = false;";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_target = htmlspecialchars(strip_tags($this->id_target));
        $this->target = htmlspecialchars(strip_tags($this->target));

        // bind data
        $stmt->bindParam(":id_target", $this->id_target);
        $stmt->bindParam(":target", $this->target);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->notifCount = $dataRow['notifCount'];
    }

    // UPDATE
    public function updateNotification()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        first_name = IF(LENGTH(:first_name)=0, first_name, :first_name),
                        last_name = IF(LENGTH(:last_name)=0, last_name, :last_name),
                        phone_number = IF(LENGTH(:phone_number)=0, phone_number, :phone_number),
                        CIN = IF(LENGTH(:CIN)=0, CIN, :CIN),
                        city = IF(LENGTH(:city)=0, city, :city),
                        address = IF(LENGTH(:address)=0, address, :address),
                        email = IF(LENGTH(:email)=0, email, :email),
                        password = IF(LENGTH(:password)=0, password, :password),
                        profile_pic = IF(LENGTH(:profile_pic)=0, profile_pic, :profile_pic)
                    WHERE
                        id_notification = :id_notification";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_notification = htmlspecialchars(strip_tags($this->id_notification));
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->CIN = htmlspecialchars(strip_tags($this->CIN));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->profile_pic = htmlspecialchars(strip_tags($this->profile_pic));

        // bind data
        $stmt->bindParam(":id_notification", $this->id_notification);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":CIN", $this->CIN);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":profile_pic", $this->profile_pic);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteNotification()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_notification = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_notification = htmlspecialchars(strip_tags($this->id_notification));

        $stmt->bindParam(1, $this->id_notification);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    //CREATE NOTIFICATION
    public function createNotification()
    {
        $sqlQuery = "INSERT INTO " . $this->db_table . "
                    SET
                        target = :target,
                        id_target = :id_target,
                        content = :content,
                        datetime = :datetime
                        ;";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->target = htmlspecialchars(strip_tags($this->target));
        $this->id_target = htmlspecialchars(strip_tags($this->id_target));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->datetime = htmlspecialchars(strip_tags($this->datetime));

        // bind data
        $stmt->bindParam(":target", $this->target);
        $stmt->bindParam(":id_target", $this->id_target);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":datetime", $this->datetime);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
