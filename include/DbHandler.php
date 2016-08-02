<?php
/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Aries Suson
 * 
 */
class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    public function getEvent()
    {
        $stat="Approved";

        //$stmt = $this->conn->prepare("SELECT eventID,eventTitle,eventStart,eventEnd FROM event WHERE eventStatus= ?");
        $stmt = $this->conn->prepare("SELECT eventID,eventTitle,eventStart,eventEnd FROM event");
        //$stmt->bind_param("s", $stat);

        $stmt->execute();

        $stmt->bind_result($eventID,$eventTitle,$eventStart,$eventEnd);

        $stmt->store_result();

        if ($stmt->num_rows > 0) {  
             while ($stmt->fetch()) {   
                    $event_array[] = array(
                        'id' => $eventID,
                        'title' => $eventTitle,
                        'start' => $eventStart,
                        'end' => $eventEnd,
                    );
                    
                }
                $stmt->close();
                return $event_array;
        } else {
            $stmt->close();
            return NULL;
        }
    }
    public function insertEvent($name,$email,$mobile,$subject, $start, $end)
    {
        $stmt = $this->conn->prepare("INSERT INTO `eventreq` (`eventReqName`, `eventReqEmail`, `eventReqMobile`, `eventReqSubject`) VALUES (?, ?, ?, ?)");

        $stmt->bind_param("ssss", $name, $email, $mobile, $subject);

        $result = $stmt->execute();

        

        // Check for successful insertion
        if ($result) {

            $last_id = mysqli_insert_id($this->conn); 

            $status="Pending";

            $stmt = $this->conn->prepare("INSERT INTO `event` (`eventID`, `eventTitle`, `eventStart`, `eventEnd`, `eventStatus`) VALUES (?, ?, ?, ?, ?)");

            $stmt->bind_param("sssss", $last_id, $subject, $start, $end, $status);

            $result = $stmt->execute();

            return "INSERTED";
        } else {           
            return NULL;
        }
        $stmt->close();
    }
    public function getCalendarData($eventID)
    {
        //$stmt = $this->conn->prepare("SELECT eventID,eventTitle,eventStart,eventEnd FROM event WHERE eventStatus= ?");
        $stmt = $this->conn->prepare("SELECT a.eventID id,a.eventReqName name,a.eventReqEmail email,a.eventReqMobile mobile,a.eventReqSubject subject,b.eventStart start, b.eventEnd end, b.eventStatus status FROM `eventreq`as a JOIN `event` as b on a.eventID=b.eventID WHERE a.eventID= ?");
        
        $stmt->bind_param("s", $eventID);

        $stmt->execute();

        $stmt->bind_result($id,$name,$email,$mobile, $subject, $start, $end, $status);

        $stmt->store_result();

        if ($stmt->num_rows > 0) {  
             while ($stmt->fetch()) {   
                    $event_array[] = array(
                        'id' => $id,
                        'title' => $name,
                        'email' => $email,
                        'mobile' => $mobile,
                        'subject' => $subject,
                        'start' => $start,
                        'end' => $end,
                        'status' => $status,
                    );
                    
                }
                $stmt->close();
                return $event_array;
        } else {
            $stmt->close();
            return NULL;
        }
    }
}



