<?php
/*
 * File: ScholarlySync - School Information Tracking & Email Notifications
 * Owner: John Lyric S. Demegillo
 * Developer: John Lyric S. Demegillo
 * Created Date: June 15 2024
 * This code is owned by John Lyric S. Demegillo. It is not for sale or unauthorized distribution.
 * For inquiries or to report issues, contact 09469177465.
 */

/*
 * Description: Email notification credentials
 * Email: scholarlysync320@gmail.com
 * Phone: 09162477235
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/PHPMailer/src/Exception.php';
require '../assets/PHPMailer/src/PHPMailer.php';
require '../assets/PHPMailer/src/SMTP.php'; 

require_once("rest.inc.php");

ini_set('memory_limit', '1024M');

function loadEnv($path)
{
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);

        $key = trim($key);
        $value = trim($value);

        // remove quotes
        $value = trim($value, "\"'");

        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

// Load .env file
loadEnv(__DIR__ . '/.env');

class API extends REST
{ 
    private $sitsdb;

    public function __construct()
    {
        parent::__construct();        // Init parent contructor
        $this->dbConnect();            // Initiate Database connection
    }
    /*
	*  Connect to Database
	*/
    private function dbConnect()
    {
        $this->sitsdb = new mysqli(
            getenv('DB_SERVER'),
            getenv('DB_USER'),
            getenv('DB_PASSWORD'),
            getenv('DB_NAME')
        );

        if ($this->sitsdb->connect_error) {
            die("Database Connection Failed: " . $this->sitsdb->connect_error);
        }

        // MAILER SETUP
        $this->mailer = new PHPMailer(true);

        $this->mailer->Username = getenv('MAIL_USERNAME');
        $this->mailer->Password = getenv('MAIL_PASSWORD');
    }

    public function processApi()
    {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['x'])));
        if ((int)method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404);
    }

    private function login_user()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        date_default_timezone_set('Asia/Manila');
        $userObj = json_decode(file_get_contents("php://input"), true);
        $userObj = str_replace("'", "`", $userObj);

        $username = (string)$userObj["username"];
        $password = (string)$userObj["password"];
        $hash = md5($password);

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            user.UserID,
            user.UserTypeText,
            user.UserTypeRID,
            user.UserLoginStatus,
            CONCAT (ud.FirstName,' ',SUBSTRING(ud.MiddleName, 1, 1), '. ', ud.LastName) AS userName,
            CONCAT (SUBSTRING(ud.FirstName, 1,8),'.', SUBSTRING(ud.LastName, 1, 1),'.') AS shortName,
            ud.Photo,
            ud.EmailAddress,
            ud.UserNumber,
            ud.UserStatus
            FROM user
            INNER JOIN user_data ud ON ud.UserID = user.UserID 
            WHERE BINARY user.UserName = '$username' AND BINARY user.PassWD = '$hash' LIMIT 1"
        );
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $error = array('status' => "error", "msg" => "Invalid username or password!");
            $this->response($this->json($error), 404);
        }
        $stmt->close();
        $this->sitsdb->close();
    }

    private function student_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $dept = (int)$this->_request["dept"];
        $course = (int)$this->_request["course"];
        $year = (int)$this->_request["year"];

        if ($dept > 0) {
            $deptfilter = "AND ud.DepartmentID = '$dept'";
        } else {
            $deptfilter = "";
        }
        if ($course > 0) {
            $coursefilter = "AND ud.CourseID = '$course'";
        } else {
            $coursefilter = "";
        }
        if ($year > 0) {
            $yearfilter = "AND ud.YrSectionID = '$year'";
        } else {
            $yearfilter = "";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            ud.UserID AS userid,
            ud.UserNumber AS usernum,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname,
            ud.DateOfBirth AS dob,
            ud.Gender AS gender,
            ud.EmailAddress AS email,
            ud.UserStatus AS userstatus,
            dp.DeptName AS dept,
            courses.CourseCode AS course,
            CONCAT(ys.Years,'-',ys.Section) AS yas
            FROM user_data ud
            LEFT JOIN department dp ON dp.DepartmentID = ud.DepartmentID
            LEFT JOIN year_section ys ON ys.YrSectionID  = ud.YrSectionID
            LEFT JOIN courses ON courses.CourseID  = ud.CourseID
            WHERE ud.Deleted = 0 
            AND ud.UserType = 1 $deptfilter $coursefilter $yearfilter
            ORDER BY ud.UserID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function student_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];
        $stmt = $this->sitsdb->prepare("SELECT 
            ud.UserID AS studid,
            ud.UserNumber AS studnum,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname,
            ud.DateOfBirth AS dob,
            ud.Gender AS gender,
            ud.EmailAddress AS email,
            ud.PhoneNumber AS pnumber,
            ud.Address AS studaddress,
            ud.City AS city,
            ud.Barangay AS brgy,
            ud.DepartmentID AS department,
            ud.YrSectionID AS yas,
            ud.CourseID AS studcourse,
            ud.UserStatus AS studstatus,
            ud.Photo AS studphoto,
            ud.Deleted AS deleted,
            ud.BenefID AS benefid,
            ud.ScholarshipID AS schid,
            dp.DeptName AS dpname,
            cs.CourseName AS csname,
            CONCAT(ys.Years, ' ',ys.Section) AS ysname
        FROM user_data ud 
        LEFT JOIN department dp ON dp.DepartmentID = ud.DepartmentID
        LEFT JOIN year_section ys ON ys.YrSectionID = ud.YrSectionID
        LEFT JOIN courses cs ON cs.CourseID = ud.CourseID
        WHERE ud.UserID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function student_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $stud_obj = json_decode(file_get_contents("php://input"), true);
        $stud_obj = str_replace("'", "`", $stud_obj);

        $studnum = (string)$stud_obj["studnum"];
        $fname = (string)$stud_obj["fname"];
        $mname = (string)$stud_obj["mname"];
        $lname = (string)$stud_obj["lname"];
        $email = (string)$stud_obj["email"];
        $pnumber = (string)$stud_obj["pnumber"];
        $dob = (string)$stud_obj["dob"];
        $address = (string)$stud_obj["address"];
        $brgy = (string)$stud_obj["brgy"];
        $city = (int)$stud_obj["city"];
        $gender = (int)$stud_obj["gender"];
        $course = (int)$stud_obj["course"];
        $yas = (int)$stud_obj["yas"];
        $department =  (int)$stud_obj["department"];
        $studstatus = (int)$stud_obj["studstatus"];
        $benefid = (int)$stud_obj["benefid"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO user_data SET 
                UserNumber='$studnum', 
                FirstName='$fname', 
                MiddleName='$mname', 
                LastName='$lname', 
                DateOfBirth='$dob', 
                Gender='$gender', 
                EmailAddress='$email',
                PhoneNumber='$pnumber', 
                `Address`='$address', 
                City='$city', 
                Barangay='$brgy', 
                DepartmentID='$department',
                YrSectionID='$yas', 
                CourseID='$course',
                UserStatus='$studstatus',
                BenefID='$benefid',
                UserType = 1;"
        );
        $stmt->execute();

        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {

            $passwd = md5($studnum);

            $stmt1 = $this->sitsdb->prepare(
                "INSERT INTO user SET 
                    UserID='$last_id', 
                    UserName='$lname',
                    PassWD='$passwd', 
                    PassWDText='$studnum', 
                    UserTypeText='Student', 
                    UserTypeRID=1;"
            );
            $stmt1->execute();

            $success = array('status' => "success", "user_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function student_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $stud_obj = json_decode(file_get_contents("php://input"), true);
        $stud_obj = str_replace("'", "`", $stud_obj);

        $studid = (string)$stud_obj["studid"];
        $studnum = (string)$stud_obj["studnum"];
        $fname = (string)$stud_obj["fname"];
        $mname = (string)$stud_obj["mname"];
        $lname = (string)$stud_obj["lname"];
        $email = (string)$stud_obj["email"];
        $pnumber = (string)$stud_obj["pnumber"];
        $dob = (string)$stud_obj["dob"];
        $address = (string)$stud_obj["address"];
        $brgy = (string)$stud_obj["brgy"];
        $city = (int)$stud_obj["city"];
        $gender = (int)$stud_obj["gender"];
        $course = (int)$stud_obj["course"];
        $yas = (int)$stud_obj["yas"];
        $department =  (int)$stud_obj["department"];
        $studstatus = (int)$stud_obj["studstatus"];
        $benefid = (int)$stud_obj["benefid"];
        $schid = (int)$stud_obj["schid"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE user_data SET 
                UserNumber='$studnum', 
                FirstName='$fname', 
                MiddleName='$mname', 
                LastName='$lname', 
                DateOfBirth='$dob', 
                Gender='$gender', 
                EmailAddress='$email',
                PhoneNumber='$pnumber', 
                `Address`='$address', 
                City='$city', 
                Barangay='$brgy', 
                DepartmentID='$department',
                YrSectionID='$yas', 
                CourseID='$course',
                BenefID='$benefid',
                ScholarshipID ='$schid',
                UserStatus='$studstatus'
                WHERE UserID = ?;"
        );
        $stmt->bind_param("i", $studid);
        $stmt->execute();

        if($benefid > 0 && $schid > 0){
            
            $stmt2 = $this->sitsdb->prepare("SELECT COUNT(UserID) AS checkrs FROM scholars WHERE UserID = '$studid' AND Deleted = 0");
            $stmt2->execute();

            $result = $stmt2->get_result();
            $row = $result->fetch_assoc();

            if ($row['checkrs'] > 0) {
                $checkrs = "1";
            } else {
                $checkrs = "0";
                $stmt3 = $this->sitsdb->prepare(
                    "INSERT INTO scholars SET 
                    ScholarshipID=6, 
                    UserID='$studid',
                    AplID = 0,
                    ApprovedDate = NOW(),
                    AddedType = 1,
                    AddedTypeText = 'Beneficiary',
                    ScholarStatus = 1;"
                );
                $stmt3->execute();
            }
        }else{
            $checkrs = "1";
        }

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            
            $stmt4 = $this->sitsdb->prepare(
                    "SELECT 
                    sp.ScholarshipName,
                    st.TypeName
                    FROM scholarship sp
                    LEFT JOIN scholarship_type st ON st.TypeID = sp.ScholarshipID
                    WHERE ScholarshipID = '$schid';"
                );
            $stmt4->execute();
            $result = $stmt4->get_result();
            $row = $result->fetch_assoc();

            $success = array('status' => "success", "scholars" => $checkrs, "schlist" => $row);
            $this->response($this->json($success), 200);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function student_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $stud_obj = json_decode(file_get_contents("php://input"), true);
        $stud_obj = str_replace("'", "`", $stud_obj);

        $id = (int)$stud_obj["id"];
        $stmt = $this->sitsdb->prepare("UPDATE user_data SET Deleted = 1 WHERE UserID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function student_compliance()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];
        $fromdate = (string)$this->_request["fromdate"];
        $todate = (string)$this->_request["todate"];

        if($fromdate != '' && $todate != ''){
            $datefilter = "AND (fr.filedate BETWEEN '$fromdate' AND '$todate')";
        }else{
            $datefilter = "";
        }
        
        $stmt = $this->sitsdb->prepare("SELECT 
            fr.FileNames AS filenames,
            fr.FileSize AS filesize,
            fr.FileType AS filetype,
            fr.FileDate AS filedate,
            fr.FileLocation AS flocation,
            fr.AplID as aplid,
            ap.AplStatus AS astatus,
            sp.ScholarshipName AS schname
        FROM filemanager fr 
        LEFT JOIN Scholarship sp ON sp.ScholarshipID = fr.ScholarshipID
        LEFT JOIN applications ap ON ap.AplID  = fr.AplID
        WHERE fr.Deleted = 0 
        AND fr.UserID = $id $datefilter");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }

    private function employee_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $department = (int)$this->_request["department"];
        $usertype = (int)$this->_request["usertype"];

        if ($department > -1) {
            $filterdp = "AND ud.DepartmentID = '$department'";
        } else {
            $filterdp = "";
        }
        if ($usertype > -1) {
            $filterut = "AND ud.UserType = '$usertype'";
        } else {
            $filterut = "";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            ud.UserID AS userid,
            ud.UserNumber AS usernum,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname,
            ud.DateOfBirth AS dob,
            ud.Gender AS gender,
            ud.UserType AS usertype,
            ud.EmailAddress AS email,
            dp.DeptName AS dept
            FROM user_data ud
            LEFT JOIN department dp ON dp.DepartmentID = ud.DepartmentID
            WHERE ud.Deleted = 0 
            AND ud.UserType != 1 $filterdp $filterut
            ORDER BY ud.UserID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function employee_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];
        $stmt = $this->sitsdb->prepare("SELECT 
            ud.UserID AS userid,
            ud.UserNumber AS usernum,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname,
            ud.DateOfBirth AS dob,
            ud.Gender AS gender,
            ud.EmailAddress AS email,
            ud.PhoneNumber AS pnumber,
            ud.Address AS useraddress,
            ud.City AS city,
            ud.Barangay AS brgy,
            ud.DepartmentID AS department,
            ud.UserDate AS userdate,
            ud.JobTitle AS jobtitle,
            ud.UserType AS usertype,
            ud.UserStatus AS userstatus,
            ud.Photo AS photo,
            ud.Deleted AS deleted
        FROM user_data ud WHERE ud.UserID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function employee_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $stud_obj = json_decode(file_get_contents("php://input"), true);
        $stud_obj = str_replace("'", "`", $stud_obj);


        $usernum = (string)$stud_obj["usernum"];
        $fname = (string)$stud_obj["fname"];
        $mname = (string)$stud_obj["mname"];
        $lname = (string)$stud_obj["lname"];
        $email = (string)$stud_obj["email"];
        $pnumber = (string)$stud_obj["pnumber"];
        $dob = (string)$stud_obj["dob"];
        $address = (string)$stud_obj["address"];
        $brgy = (string)$stud_obj["brgy"];
        $hrdata = (string)$stud_obj["hrdata"];
        $jobtitle = (string)$stud_obj["jobtitle"];
        $hrdata = (string)$stud_obj["hrdata"];
        $city = (int)$stud_obj["city"];
        $gender = (int)$stud_obj["gender"];
        $department =  (int)$stud_obj["department"];
        $userstatus = (int)$stud_obj["userstatus"];
        $usertype = (int)$stud_obj["usertype"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO user_data SET 
                UserNumber='$usernum', 
                FirstName='$fname', 
                MiddleName='$mname', 
                LastName='$lname', 
                DateOfBirth='$dob', 
                Gender='$gender', 
                EmailAddress='$email',
                PhoneNumber='$pnumber', 
                `Address`='$address', 
                City='$city', 
                Barangay='$brgy', 
                DepartmentID='$department',
                UserDate='$hrdata',
                JobTitle='$jobtitle',
                UserType='$usertype',
                UserStatus='$userstatus';"
        );
        $stmt->execute();


        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {

            $passwd = md5($usernum);

            $usertypes = [
                0 => "Administrator",
                2 => "Coaches",
                3 => "Department Heads",
            ];

            $usertypetext = $usertypes[$usertype] ?? "Teacher";

            $stmt1 = $this->sitsdb->prepare(
                "INSERT INTO user SET 
                    UserID='$last_id', 
                    UserName='$lname',
                    PassWD='$passwd', 
                    PassWDText='$usernum', 
                    UserTypeText='$usertypetext', 
                    UserTypeRID='$usertype';"
            );
            $stmt1->execute();
            $success = array('status' => "success", "user_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function employee_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $stud_obj = json_decode(file_get_contents("php://input"), true);
        $stud_obj = str_replace("'", "`", $stud_obj);


        $userid = (string)$stud_obj["userid"];
        $usernum = (string)$stud_obj["usernum"];
        $fname = (string)$stud_obj["fname"];
        $mname = (string)$stud_obj["mname"];
        $lname = (string)$stud_obj["lname"];
        $email = (string)$stud_obj["email"];
        $pnumber = (string)$stud_obj["pnumber"];
        $dob = (string)$stud_obj["dob"];
        $address = (string)$stud_obj["address"];
        $brgy = (string)$stud_obj["brgy"];
        $hrdata = (string)$stud_obj["hrdata"];
        $jobtitle = (string)$stud_obj["jobtitle"];
        $hrdata = (string)$stud_obj["hrdata"];
        $city = (int)$stud_obj["city"];
        $gender = (int)$stud_obj["gender"];
        $department =  (int)$stud_obj["department"];
        $userstatus = (int)$stud_obj["userstatus"];
        $usertype = (int)$stud_obj["usertype"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE user_data SET 
                UserNumber='$usernum', 
                FirstName='$fname', 
                MiddleName='$mname', 
                LastName='$lname', 
                DateOfBirth='$dob', 
                Gender='$gender', 
                EmailAddress='$email',
                PhoneNumber='$pnumber', 
                `Address`='$address', 
                City='$city', 
                Barangay='$brgy', 
                DepartmentID='$department',
                JobTitle='$jobtitle', 
                UserDate='$hrdata',
                UserStatus='$userstatus',
                UserType='$usertype'
                WHERE UserID = ?;"
        );
        $stmt->bind_param("i", $userid);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function employee_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $user_obj = json_decode(file_get_contents("php://input"), true);
        $user_obj = str_replace("'", "`", $user_obj);

        $id = (int)$user_obj["id"];
        $stmt = $this->sitsdb->prepare("UPDATE user_data SET Deleted = 1 WHERE UserID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    private function scheme_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $schtype = (int)$this->_request["schtype"];
        $schstatus = (int)$this->_request["schstatus"];

        if ($schtype > 0) {
            $typefilter = "AND sch.ScholarshipType = '$schtype'";
        } else {
            $typefilter = "";
        }

        if ($schstatus > 0) {
            $stsfilter = "AND sch.ScholarshipStatus = '$schstatus'";
        } else {
            $stsfilter = "";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            sch.ScholarshipID AS schid,
            sch.ScholarshipName AS schname,
            sch.ScholarshipStatus AS schstatus, 
            sch.AwardAmount AS awardamnt,
            sch.AwardDate AS awardate,
            sch.Criteria AS criteria,
            sch.DocsRequired AS docrequired,
            sch.FundingSource AS fundsource,
            sch.CreatedBy AS createdby,
            sch.CreatedAt AS createdat,
            sch.UpdatedAt AS updatedat,

            st.TypeName AS typename,
            st.Description AS descriptions,
            
            CONCAT (ud.FirstName,' ',SUBSTRING(ud.MiddleName, 1, 1), '. ', ud.LastName) AS userName,
            CONCAT (SUBSTRING(ud.FirstName, 1,8),'.', SUBSTRING(ud.LastName, 1, 1),'.') AS shortName

            FROM scholarship sch
            LEFT JOIN scholarship_type st ON st.TypeID = sch.ScholarshipType
            LEFT JOIN user_data ud ON ud.UserID = sch.CreatedBy
            WHERE sch.Deleted = 0 $typefilter $stsfilter 
            ORDER BY sch.ScholarshipID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function scheme_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            sch.ScholarshipID AS schid,
            sch.ScholarshipName AS schname,
            sch.ScholarshipType AS schtype,
            sch.ScholarshipStatus AS schstatus, 
            sch.AwardAmount AS awardamnt,
            sch.AwardDate AS awardate,
            sch.Criteria AS criteria,
            sch.DocsRequired AS docrequired,
            sch.FundingSource AS fundsource,
            sch.CreatedBy AS createdby,
            sch.CreatedAt AS createdat,
            sch.UpdatedAt AS updatedat,
            sch.ResponSch AS respsch,
            st.TypeName AS schtypename,
            ud.EmailAddress AS email,
            ud.LastName AS lname
            FROM scholarship sch
            LEFT JOIN scholarship_type st ON st.TypeID = sch.ScholarshipType
            LEFT JOIN user_data ud ON ud.UserID = sch.ResponSch
            WHERE sch.ScholarshipID = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function scheme_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sch_obj = json_decode(file_get_contents("php://input"), true);
        $sch_obj = str_replace("'", "`", $sch_obj);


        $schname = (string)$sch_obj["schname"];
        $schtype = (int)$sch_obj["schtype"];
        $createdby = (int)$sch_obj["createdby"];
        $respsch = (int)$sch_obj["respsch"];
        $schdate = (string)$sch_obj["schdate"];
        $schstatus = (string)$sch_obj["schstatus"];
        $amount = (string)$sch_obj["amount"];
        $criteria = (string)$sch_obj["criteria"];
        $docrequired = (string)$sch_obj["docrequired"];
        $fundsource = (string)$sch_obj["fundsource"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO scholarship SET 
                ScholarshipName='$schname', 
                ScholarshipType='$schtype', 
                ScholarshipStatus='$schstatus', 
                AwardAmount='$amount', 
                AwardDate='$schdate', 
                Criteria='$criteria', 
                FundingSource='$fundsource', 
                DocsRequired='$docrequired',
                ResponSch ='$respsch',
                CreatedBy='$createdby';"
        );
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {
            $success = array('status' => "success", "sch_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function scheme_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sch_obj = json_decode(file_get_contents("php://input"), true);
        $sch_obj = str_replace("'", "`", $sch_obj);


        $schid = (int)$sch_obj["schid"];
        $schname = (string)$sch_obj["schname"];
        $schtype = (int)$sch_obj["schtype"];
        $respsch = (int)$sch_obj["respsch"];
        $schdate = (string)$sch_obj["schdate"];
        $schstatus = (string)$sch_obj["schstatus"];
        $amount = (string)$sch_obj["amount"];
        $criteria = (string)$sch_obj["criteria"];
        $docrequired = (string)$sch_obj["docrequired"];
        $fundsource = (string)$sch_obj["fundsource"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE scholarship SET 
                ScholarshipName='$schname', 
                ScholarshipType='$schtype', 
                ScholarshipStatus='$schstatus', 
                AwardAmount='$amount', 
                AwardDate='$schdate', 
                Criteria='$criteria', 
                FundingSource='$fundsource', 
                ResponSch ='$respsch',
                DocsRequired='$docrequired'
                WHERE ScholarshipID = ?;"
        );
        $stmt->bind_param("i", $schid);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function scheme_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sch_obj = json_decode(file_get_contents("php://input"), true);
        $sch_obj = str_replace("'", "`", $sch_obj);

        $id = (int)$sch_obj["id"];
        $stmt = $this->sitsdb->prepare("UPDATE scholarship SET Deleted = 1 WHERE ScholarshipID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    private function application_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $status = (int)$this->_request["status"];
        $userid = (int)$this->_request["userid"];
        $usertype = (int)$this->_request["usertype"];
        $schtype = (int)$this->_request["schtype"];
        $startDate = (string)$this->_request["startDate"];
        $endDate = (string)$this->_request["endDate"];

        if ($schtype != -1) {
            $filsctype = "AND se.TypeID = '$schtype'";
        } else {
            $filsctype = "";
        }
        if ($status != -1) {
            $filStatus = "AND app.AplStatus = '$status'";
        } else {
            $filStatus = "";
        }
        if ($usertype == 1) {
            $filuser = "AND app.UserID = '$userid'";
        } else {
            $filuser = "";
        }
        if ($startDate != "" || $endDate != "") {
            $filDate = "AND app.AplDate BETWEEN '$startDate' AND '$endDate'";
        } else {
            $filDate = "";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            app.AplID AS aplid,
            app.ScholarshipID AS schid,
            app.AplDate AS apldate,
            app.AplStatus AS aplstatus,
            app.UserID AS userid,

            sch.ScholarshipName AS schname,    
            ud.Photo AS photo,
            ud.EmailAddress AS email,

            se.TypeName AS schtype,   
            CONCAT (ud.FirstName,' ',SUBSTRING(ud.MiddleName, 1, 1), '. ', ud.LastName) AS userName,
            CONCAT (SUBSTRING(ud.FirstName, 1,8),'.', SUBSTRING(ud.LastName, 1, 1),'.') AS shortName

            FROM applications app
            LEFT JOIN scholarship sch ON sch.ScholarshipID = app.ScholarshipID
            LEFT JOIN scholarship_type se ON se.TypeID = sch.ScholarshipType
            LEFT JOIN user_data ud ON ud.UserID = app.UserID
            WHERE app.Deleted = 0 $filStatus $filDate $filuser $filsctype
            ORDER BY app.AplID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function application_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            app.AplID AS aplid,
            app.ScholarshipID AS schid,
            app.UserID AS userid,
            app.HHIncome AS hincome,
            app.ReceivingFinancialAid AS crfa,
            app.ExistingScholarship AS dyhes,
            app.AplSchID AS aplschid,
            app.AplDate AS apldate,
            app.WhyApply AS whyapply,
            app.Messages AS msgcnrn,
            app.AplStatus AS aplstatus,
            app.FileSubmitted AS flsubmit,
            app.FieldStudy AS major,
            app.Gpa AS gpa,
            app.PreviousAcademic AS paia,
            app.NoIndependents AS ndh,
            app.ReasonFinancial AS rfn,
            app.Remarks AS remarks
            FROM applications app
            WHERE app.AplID = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function application_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $appobj = json_decode(file_get_contents("php://input"), true);
        $appobj = str_replace("'", "`", $appobj);


        $userid = (int)$appobj["userid"];
        $schid = (int)$appobj["schid"];
        $aplschid = (int)$appobj["aplschid"];
        $aplstatus = (int)$appobj["aplstatus"];
        $crfa = (int)$appobj["crfa"];
        $dyhes = (int)$appobj["dyhes"];
        $ndh = (float)$appobj["ndh"];

        $major = (string)$appobj["major"];
        $gpa = (float)$appobj["gpa"];
        $paia = (string)$appobj["paia"];
        $hincome = (float)$appobj["hincome"];
        $rfn = (string)$appobj["rfn"];
        $msgcnrn = (string)$appobj["msgcnrn"];
        $wayapp = (string)$appobj["wayapp"];

       
        $check = $this->sitsdb->prepare(
            "SELECT COUNT(AplID) AS checks FROM applications
            WHERE UserID = '$userid'
            AND ScholarshipID = '$schid'
            AND Deleted = 0
            AND AplStatus NOT IN (4, 5)"
        );
        $check->execute();

        $result = $check->get_result();
        $row = $result->fetch_assoc();

        if ($row['checks'] > 0) {
            $applied = array('status' => "applied");
            $this->response($this->json($applied), 200);
        } else {
            $stmt = $this->sitsdb->prepare(
                "INSERT INTO applications SET 
                ScholarshipID='$schid', 
                UserID='$userid', 
                HHIncome='$hincome', 
                ReceivingFinancialAid='$crfa', 
                ExistingScholarship='$dyhes',
                AplSchID='$aplschid',
                WhyApply='$wayapp',
                Messages='$msgcnrn',
                AplStatus='$aplstatus',
                NoIndependents='$ndh',
                FieldStudy='$major',
                Gpa='$gpa',
                PreviousAcademic='$paia',
                ReasonFinancial='$rfn'
                ;"
            );
            $stmt->execute();
            $affected_rows = $stmt->affected_rows;
            $last_id = $this->sitsdb->insert_id;

            if ($affected_rows == 1) {
                $success = array('status' => "success", "apl_id" => $last_id);
                $this->response($this->json($success), 200);
            } else {
                $arr = $stmt->error;
                print_r($arr);
            }
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function application_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $appobj = json_decode(file_get_contents("php://input"), true);
        $appobj = str_replace("'", "`", $appobj);


        $userid = (int)$appobj["userid"];
        $schid = (int)$appobj["schid"];
        $appid = (int)$appobj["appid"];
        $aplschid = (int)$appobj["aplschid"];
        $aplstatus = (int)$appobj["aplstatus"];
        $crfa = (int)$appobj["crfa"];
        $dyhes = (int)$appobj["dyhes"];
        $ndh = (float)$appobj["ndh"];

        $major = (string)$appobj["major"];
        $gpa = (float)$appobj["gpa"];
        $paia = (string)$appobj["paia"];
        $hincome = (float)$appobj["hincome"];
        $rfn = (string)$appobj["rfn"];
        $msgcnrn = (string)$appobj["msgcnrn"];
        $wayapp = (string)$appobj["wayapp"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE applications SET 
                ScholarshipID='$schid', 
                UserID='$userid', 
                HHIncome='$hincome', 
                ReceivingFinancialAid='$crfa', 
                ExistingScholarship='$dyhes',
                AplSchID='$aplschid',
                WhyApply='$wayapp',
                Messages='$msgcnrn',
                AplStatus='$aplstatus',
                NoIndependents='$ndh',
                FieldStudy='$major',
                Gpa='$gpa',
                PreviousAcademic='$paia',
                ReasonFinancial='$rfn'
                WHERE AplID = '$appid';"
        );
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;

        if ($affected_rows == 1) {
            $arr = $stmt->success;
            print_r($arr);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function application_status()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $app_obj = json_decode(file_get_contents("php://input"), true);
        $app_obj = str_replace("'", "`", $app_obj);


        $aplid = (int)$app_obj["aplid"];
        $schid = (int)$app_obj["schid"];
        $userid = (int)$app_obj["userid"];
        $reviewer = (int)$app_obj["reviewer"];
        $aplstatus = (int)$app_obj["aplstatus"];
        $remarks = (string)$app_obj["remarks"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE applications SET 
                AplStatus='$aplstatus',
                ReviewerID='$reviewer',
                Remarks = '$remarks'
                WHERE AplID = ?;"
        );
        $stmt->bind_param("i", $aplid);
        $stmt->execute();

        if ($aplstatus == 3) {
            $stmt1 = $this->sitsdb->prepare(
                "INSERT INTO scholars SET 
                ScholarshipID='$schid',
                UserID='$userid',
                AplID='$aplid',
                ApprovedDate=NOW(),
                AddedType=0,
                ScholarStatus=1,
                AddedTypeText='Application Form';"
            );
            $stmt1->execute();

            $stmt2 = $this->sitsdb->prepare(
                "SELECT * FROM applications
                WHERE UserID = '$userid'
                AND Deleted = 0
                AND AplStatus != 3"
            );
            $stmt2->execute();

            $result = $stmt2->get_result();

            while($row = $result->fetch_assoc()){
                $rowaplid = $row['AplID'];

                $stmt3 = $this->sitsdb->prepare(
                "UPDATE applications SET 
                AplStatus=5
                WHERE UserID = '$userid'
                AND AplID = '$rowaplid';");

                $stmt3->execute();
            }
        }

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function application_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $app_obj = json_decode(file_get_contents("php://input"), true);
        $app_obj = str_replace("'", "`", $app_obj);

        $id = (int)$app_obj["id"];
        $stmt = $this->sitsdb->prepare("UPDATE applications SET Deleted = 1 WHERE AplID  = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    private function course_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            courses.CourseID AS courseid,
            courses.CourseName AS coursename,
            courses.CourseCode AS coursecode,
            courses.Description AS desp,
            courses.Credits AS credit,
            courses.Major AS major,
            courses.Deleted AS deleted,
            (SELECT COUNT(ud.UserID) AS totalstudent FROM user_data ud WHERE ud.Deleted = 0 AND ud.UserType = 1 AND ud.CourseID = courses.CourseID) AS totalstudent
            FROM courses
            WHERE courses.Deleted = 0 
            ORDER BY courses.CourseID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function course_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];
        $stmt = $this->sitsdb->prepare("SELECT 
            courses.CourseID AS courseid,
            courses.CourseName AS coursename,
            courses.CourseCode AS coursecode,
            courses.Description AS desp,
            courses.Credits AS credit,
            courses.Major AS major,
            courses.Deleted AS deleted
        FROM courses WHERE courses.CourseID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function course_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $course_obj = json_decode(file_get_contents("php://input"), true);
        $course_obj = str_replace("'", "`", $course_obj);


        $name = (string)$course_obj["name"];
        $code = (string)$course_obj["code"];
        $description = (string)$course_obj["description"];
        $major = (string)$course_obj["major"];
        $credits = (int)$course_obj["credits"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO courses SET 
                CourseName='$name', 
                CourseCode='$code', 
                Description='$description', 
                Major='$major', 
                Credits='$credits';"
        );
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {
            $success = array('status' => "success", "course_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function course_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $course_obj = json_decode(file_get_contents("php://input"), true);
        $course_obj = str_replace("'", "`", $course_obj);


        $courseid = (string)$course_obj["courseid"];
        $name = (string)$course_obj["name"];
        $code = (string)$course_obj["code"];
        $description = (string)$course_obj["description"];
        $major = (string)$course_obj["major"];
        $credits = (int)$course_obj["credits"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE courses SET 
                CourseName='$name', 
                CourseCode='$code', 
                Description='$description', 
                Major='$major', 
                Credits='$credits'
                WHERE CourseID = ?;"
        );
        $stmt->bind_param("i", $courseid);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function course_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $course_obj = json_decode(file_get_contents("php://input"), true);
        $course_obj = str_replace("'", "`", $course_obj);

        $id = (int)$course_obj["id"];
        $stmt = $this->sitsdb->prepare("UPDATE courses SET Deleted = 1 WHERE CourseID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    private function yearsec_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            ys.YrSectionID  AS ysid,
            ys.Years AS years,
            ys.Section AS section,
            ys.Section_name AS sname,
            ys.Section_code AS scode,
            ys.Deleted AS deleted,
            (SELECT COUNT(ud.UserID) AS totalstudent FROM user_data ud WHERE ud.Deleted = 0 AND ud.UserType = 1 AND ud.YrSectionID = ys.YrSectionID) AS totalstudent
            FROM year_section ys
            WHERE ys.Deleted = 0 
            ORDER BY ys.YrSectionID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function yearsec_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];
        $stmt = $this->sitsdb->prepare("SELECT 
            ys.YrSectionID  AS ysid,
            ys.Years AS years,
            ys.Section AS section,
            ys.Section_name AS sname,
            ys.Section_code AS scode,
            ys.Deleted AS deleted
        FROM year_section ys WHERE ys.YrSectionID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function yearsec_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $ys_obj = json_decode(file_get_contents("php://input"), true);
        $ys_obj = str_replace("'", "`", $ys_obj);


        $year = (string)$ys_obj["year"];
        $sec = (string)$ys_obj["sec"];
        $name = (string)$ys_obj["name"];
        $code = (string)$ys_obj["code"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO year_section SET 
                Years='$year', 
                Section='$sec', 
                Section_name='$name', 
                Section_code='$code';"
        );
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {
            $success = array('status' => "success", "ys_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function yearsec_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $ys_obj = json_decode(file_get_contents("php://input"), true);
        $ys_obj = str_replace("'", "`", $ys_obj);


        $ysid = (int)$ys_obj["ysid"];
        $years = (string)$ys_obj["years"];
        $sec = (string)$ys_obj["sec"];
        $name = (string)$ys_obj["name"];
        $code = (string)$ys_obj["code"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE year_section SET 
                Years='$years', 
                Section='$sec', 
                Section_name='$name', 
                Section_code='$code'
                WHERE YrSectionID = ?;"
        );
        $stmt->bind_param("i", $ysid);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function yearsec_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $ys_obj = json_decode(file_get_contents("php://input"), true);
        $ys_obj = str_replace("'", "`", $ys_obj);

        $id = (int)$ys_obj["id"];
        $stmt = $this->sitsdb->prepare("UPDATE year_section SET Deleted = 1 WHERE YrSectionID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    //department
    private function dept_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            dept.DepartmentID AS deptid,
            dept.DeptName AS deptname,
            dept.DeptCode AS deptcode,
            dept.Descriptions AS desciptions,
            dept.UserID AS depthead,
            dept.Deleted AS deleted,
            (SELECT COUNT(ud.UserID) AS totalstudent FROM user_data ud WHERE ud.Deleted = 0 AND ud.UserType = 1 AND ud.DepartmentID = dept.DepartmentID) AS totalstudent
            FROM department dept
            WHERE dept.Deleted = 0 
            ORDER BY dept.DepartmentID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function dept_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];
        $stmt = $this->sitsdb->prepare("SELECT 
            dept.DepartmentID AS deptid,
            dept.DeptName AS deptname,
            dept.DeptCode AS deptcode,
            dept.Descriptions AS descriptions,
            dept.UserID AS depthead,
            dept.Deleted AS deleted
        FROM department dept 
        WHERE dept.DepartmentID = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function dept_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $dept_obj = json_decode(file_get_contents("php://input"), true);
        $dept_obj = str_replace("'", "`", $dept_obj);


        $depthead = (int)$dept_obj["depthead"];
        $name = (string)$dept_obj["name"];
        $code = (string)$dept_obj["code"];
        $desciptions = (string)$dept_obj["desciptions"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO department SET 
                DeptName='$name', 
                DeptCode='$code',
                Descriptions='$desciptions',
                UserID='$depthead';"
        );
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {
            $success = array('status' => "success", "dept_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function dept_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $dept_obj = json_decode(file_get_contents("php://input"), true);
        $dept_obj = str_replace("'", "`", $dept_obj);


        $deptid = (int)$dept_obj["deptid"];
        $depthead = (int)$dept_obj["depthead"];
        $name = (string)$dept_obj["name"];
        $code = (string)$dept_obj["code"];
        $descriptions = (string)$dept_obj["desciptions"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE department SET 
                DeptName='$name', 
                DeptCode='$code', 
                Descriptions='$descriptions',
                UserID='$depthead'
                WHERE DepartmentID = ?;"
        );
        $stmt->bind_param("i", $deptid);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function dept_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $dept_obj = json_decode(file_get_contents("php://input"), true);
        $dept_obj = str_replace("'", "`", $dept_obj);

        $id = (int)$dept_obj["id"];
        $stmt = $this->sitsdb->prepare("UPDATE department SET Deleted = 1 WHERE DepartmentID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    //sholarship type
    private function sch_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            st.TypeID AS typeid,
            st.TypeName AS typename,
            st.Description AS despt
            FROM scholarship_type st
            WHERE st.Deleted = 0 
            ORDER BY st.TypeID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function sch_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];
        $stmt = $this->sitsdb->prepare("SELECT 
            st.TypeID AS typeid,
            st.TypeName AS typename,
            st.Description AS descp,
            st.Deleted AS deleted
        FROM scholarship_type st 
        WHERE st.TypeID  = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function sch_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sch_obj = json_decode(file_get_contents("php://input"), true);
        $sch_obj = str_replace("'", "`", $sch_obj);


        $typename = (string)$sch_obj["typename"];
        $description = (string)$sch_obj["description"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO scholarship_type SET 
                TypeName='$typename', 
                `Description`='$description';"
        );
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {
            $success = array('status' => "success", "dept_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function sch_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sch_obj = json_decode(file_get_contents("php://input"), true);
        $sch_obj = str_replace("'", "`", $sch_obj);


        $typeid = (int)$sch_obj["typeid"];
        $typename = (string)$sch_obj["typename"];
        $description = (string)$sch_obj["description"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE scholarship_type SET 
                TypeName='$typename', 
                `Description`='$description'
                WHERE TypeID = ?;"
        );
        $stmt->bind_param("i", $typeid);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function sch_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sch_obj = json_decode(file_get_contents("php://input"), true);
        $sch_obj = str_replace("'", "`", $sch_obj);

        $id = (int)$sch_obj["id"];
        $stmt = $this->sitsdb->prepare("UPDATE scholarship_type SET Deleted = 1 WHERE TypeID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    //user
    private function user_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $userid = (int)$this->_request["usertype"];


        if ($userid == -1) {
            $filter = "";
        } else {
            $filter = "AND user.UserTypeRID = '$userid'";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            user.*,
            ud.UserNumber,
            ud.FirstName,
            ud.LastName,
            ud.MiddleName
            FROM user
            LEFT JOIN user_data ud ON ud.UserID = user.UserID
            WHERE user.Deleted = 0 $filter
            ORDER BY user.createdAt"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function users_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $userobj = json_decode(file_get_contents("php://input"), true);
        $userobj = str_replace("'", "`", $userobj);


        $userid = (int)$userobj["userid"];
        $usertypeid = (int)$userobj["usertypeid"];
        $username = (string)$userobj["username"];
        $usertypetext = (string)$userobj["usertypetext"];
        $passwd = (string)$userobj["passwd"];
        $hash = md5($passwd);

        $check = $this->sitsdb->prepare("SELECT COUNT(LoginID) AS checking FROM user WHERE UserID = '$userid'");
        $check->execute();

        $result = $check->get_result();

        foreach ($result as $row) {
            $checking = $row["checking"];

            if ($checking > 0) {
                $stmt = $this->sitsdb->prepare(
                    "UPDATE user SET 
                    Deleted = 0
                    WHERE UserID = ?;"
                );
                $stmt->bind_param("i", $userid);
                $stmt->execute();
            } else {

                $stmt = $this->sitsdb->prepare(
                    "INSERT INTO user SET
                    UserID = '$userid',
                    UserName = '$username',
                    PassWDText='$passwd', 
                    UserTypeText='$usertypetext', 
                    UserTypeRID='$usertypeid', 
                    PassWD='$hash';"
                );
                $stmt->execute();
            }
        }


        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function users_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $userobj = json_decode(file_get_contents("php://input"), true);
        $userobj = str_replace("'", "`", $userobj);


        $id = (int)$userobj["id"];
        $npassword = (string)$userobj["npassword"];
        $username = (string)$userobj["username"];
        $hash = md5($npassword);

        $stmt = $this->sitsdb->prepare(
            "UPDATE user SET 
                UserName = '$username',
                PassWDText='$npassword', 
                PassWD='$hash'
                WHERE LoginID = ?;"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function users_switch()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $userobj = json_decode(file_get_contents("php://input"), true);
        $userobj = str_replace("'", "`", $userobj);


        $id = (int)$userobj["id"];
        $status = (int)$userobj["status"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE user SET 
                UserLoginStatus = '$status'
                WHERE LoginID = ?;"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function users_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $userobj = json_decode(file_get_contents("php://input"), true);
        $userobj = str_replace("'", "`", $userobj);


        $id = (int)$userobj["id"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE user SET 
                Deleted = 1
                WHERE LoginID = ?;"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            $this->response($this->json([$success]), 200);
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    //scholars
    private function scholar_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $schlarsts = (int)$this->_request["schlarsts"];
        $schtype = (int)$this->_request["schtype"];
        $ysfrom = (string)$this->_request["ysfrom"];
        $ysto = (string)$this->_request["ysto"];

        if ($schlarsts != 0) {
            $ssfilter = "AND sc.ScholarStatus = '$schlarsts'";
        } else {
            $ssfilter = "";
        }
        if ($schtype != 0) {
            $sctfilter = "AND sp.ScholarshipType = '$schtype'";
        } else {
            $sctfilter = "";
        }

        if ($ysfrom != "" || $ysto != "") {
            $ysfilter = "AND YEAR(sc.ApprovedDate) BETWEEN '$ysfrom' AND '$ysto'";
        } else {
            $ysfilter = "";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            sc.*,
            sp.ScholarshipName,
            st.TypeName,
            ud.FirstName,
            ud.MiddleName,
            ud.LastName,
            ud.UserNumber,
            cs.CourseName,
            cs.CourseCode,
            yn.Years
            FROM scholars sc
            LEFT JOIN scholarship sp ON sp.ScholarshipID = sc.ScholarshipID
            LEFT JOIN scholarship_type st ON st.TypeID = sp.ScholarshipType 
            LEFT JOIN user_data ud ON ud.UserID = sc.UserID
            LEFT JOIN courses cs ON cs.CourseID = ud.CourseID
            LEFT JOIN year_section yn ON yn.YrSectionID = ud.YrSectionID
            WHERE sc.Deleted = 0 $sctfilter $ysfilter $ssfilter
            ORDER BY sc.ApprovedDate DESC"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function scholar_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sch_obj = json_decode(file_get_contents("php://input"), true);
        $sch_obj = str_replace("'", "`", $sch_obj);

        $scholarid = (int)$sch_obj["scholarid"];
        $schstatus = (int)$sch_obj["schstatus"];
        $userid = (int)$sch_obj["userid"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE scholars SET 
                ScholarStatus = $schstatus
                WHERE ScholarID = ?;"
        );
        $stmt->bind_param("i", $scholarid);
        $stmt->execute();

        $stmt1 = $this->sitsdb->prepare(
            "UPDATE user_data SET 
                UserStatus = '$schstatus'
                WHERE UserID = ?;"
        );
        $stmt1->bind_param("i", $userid);
        $stmt1->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            
            $success = "success";
            $this->response($this->json([$success]), 200);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function scholar_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $scholar_obj = json_decode(file_get_contents("php://input"), true);
        $scholar_obj = str_replace("'", "`", $scholar_obj);


        $id = (int)$scholar_obj["id"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE scholars SET 
                Deleted = 1
                WHERE ScholarID = ?;"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            $this->response($this->json([$success]), 200);
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    private function email_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $userid = (int)$this->_request["userid"];
        $usertype = (int)$this->_request["usertype"];
        $fromDate = (string)$this->_request["fromDate"];
        $toDate = (string)$this->_request["toDate"];

        if ($fromDate != "" || $toDate != "") {
            $esfilter = "AND YEAR(es.SentDate) BETWEEN '$fromDate' AND '$toDate'";
        } else {
            $esfilter = "";
        }

        if($usertype == 0){
            $userfilter = "";
        }else{
            $userfilter = "AND ((es.UserID = '$userid' OR es.FromUserID = '$userid') OR es.FromUserID = '$userid')";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            es.ENID AS enid,
            es.RecipientEmail AS email,
            es.FromEmail AS fromemail,
            es.Subjects AS subjects,
            es.EmailBody AS body,
            es.SentDate AS sentdate,
            es.AttachementInfo AS attchment,
            es.EmailType AS notifstatus,
            es.EmailStatus AS emailstatus,
            es.Method AS method,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname
            FROM email_notifications es
            LEFT JOIN user_data ud ON ud.UserID = es.UserID
            WHERE es.Deleted = 0  $esfilter $userfilter
            ORDER BY es.ENID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function email_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $id = (int)$this->_request["id"];

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            es.ENID AS enid,
            es.RecipientEmail AS email,
            es.FromEmail AS fromemail,
            es.FromUserID AS fromuserid,
            es.UserID AS userid,
            es.Subjects AS subjects,
            es.EmailBody AS body,
            es.SentDate AS sentdate,
            es.AttachementInfo AS attchment,
            es.EmailType AS notifstatus,
            es.Method AS method,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname
            FROM email_notifications es
            LEFT JOIN user_data ud ON ud.UserID = es.UserID
            WHERE es.Deleted = 0 
            AND es.ENID = '$id'
            ORDER BY es.ENID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function email_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $concernobj = json_decode(file_get_contents("php://input"), true);
        $concernobj = str_replace("'", "`", $concernobj);

        $fromuserid = (int)$concernobj["fromuserid"];
        $fromemail = (string)$concernobj["fromemail"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO email_notifications SET 
                FromEmail = '$fromemail',
                FromUserID  = '$fromuserid',
                EmailType = 2,
                Method = 'SMTP';"
        );
        $stmt->execute();

        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {
            $success = array('status' => "success", "en_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function email_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $concernobj = json_decode(file_get_contents("php://input"), true);
        $concernobj = str_replace("'", "`", $concernobj);

        $enid = (int)$concernobj["enid"];
        $touserid = (int)$concernobj["touserid"];
        $emailstatus = (int)$concernobj["emailstatus"];
        $toemail = (string)$concernobj["toemail"];
        $subjects = (string)$concernobj["subjects"];
        $body = (string)$concernobj["body"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE email_notifications SET 
                UserID = '$touserid',
                RecipientEmail  = '$toemail',
                Subjects = '$subjects',
                EmailBody = '$body',
                EmailStatus = '$emailstatus'
                WHERE ENID = '$enid';"
        );
        $stmt->execute();


        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            $this->response($this->json([$success]), 200);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function email_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $en_obj = json_decode(file_get_contents("php://input"), true);
        $en_obj = str_replace("'", "`", $en_obj);

        $id = (int)$en_obj["id"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE email_notifications SET 
                Deleted = 1
                WHERE ENID = ?;"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();


        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {

            $stmt1 = $this->sitsdb->prepare("SELECT FileID, FileLocation FROM filemanager WHERE ENID = '$id'");
            $stmt1->execute();

            $result = $stmt1->get_result();

            while($row = $result->fetch_assoc()){
                $fileid = $row['FileID'];
                $flocation = $row['FileLocation'];

                 if (file_exists($flocation)) {
                    if (unlink($flocation)) {
                        $stmt3 = $this->sitsdb->prepare(
                            "UPDATE filemanager SET Deleted = 1
                            WHERE FileID = '$fileid';"
                        );
                        $stmt3->execute();
                    } else {
                    }
                }
                $stmt4 = $this->sitsdb->prepare(
                    "UPDATE filemanager SET Deleted = 1
                    WHERE FileID = '$fileid';"
                );
                $stmt4->execute();
            }
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    //email notifcations
    private function sent_email()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $email_obj = json_decode(file_get_contents("php://input"), true);
        $email_obj = str_replace("'", "`", $email_obj);

        $addaddress = (string)$email_obj["addaddress"];
        $subject = (string)$email_obj["subject"];
        $body = (string)$email_obj["body"];
        $pdfPath = (string)$email_obj["pdfPath"];

        try {
            $this->mailer->isSMTP();
            $this->mailer->Host       = 'smtp.gmail.com';
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = getenv('MAIL_USERNAME');
            $this->mailer->Password   = getenv('MAIL_PASSWORD');
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port       = 587;

            $this->mailer->setFrom('scholarlysync320@gmail.com', 'ScholarlySync');
            $this->mailer->addAddress($addaddress, 'Recipient');

            if ($pdfPath) {
                $this->mailer->addAttachment($pdfPath);
            }

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject; /* 'Application Received - [Scholarship Name]'; */
            $this->mailer->Body = $body;
            $this->mailer->send();
            $result = 'Message has been sent';
        } catch (Exception $e) {
            $result = "Message could not be sent. Mailer Error: " . $this->mailer->ErrorInfo;
        }

        $this->response($this->json([$result]), 200);
    }
    private function emailnotif()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $enobj = json_decode(file_get_contents("php://input"), true);
        $enobj = str_replace("'", "`", $enobj);


        $userid = (int)$enobj["userid"];
        $emailstatus = (int)$enobj["emailstatus"];
        $emailtype = (int)$enobj["emailtype"];
        $fromuserid = (int)$enobj["fromuserid"];
        $email = (string)$enobj["email"];
        $fromemail = (string)$enobj["fromemail"];
        $subject = (string)$enobj["subject"];
        $attachment = (string)$enobj["attachment"];
        $body = (string)$enobj["body"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO email_notifications SET
            UserID = '$userid',
            RecipientEmail = '$email',
            FromEmail = '$fromemail',
            FromUserID = '$fromuserid',
            Subjects='$subject', 
            SentDate= NOW(), 
            SentTimeStamped= NOW(), 
            EmailStatus= '$emailstatus', 
            EmailType= '$emailtype', 
            EmailBody='$body', 
            AttachementInfo='$attachment';"
        );
        $stmt->execute();

        $stmt->close();
        $this->sitsdb->close();
    }

    // check student if already apply on this scholarship
    private function checkstudapply()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $studid = (int)$this->_request["studid"];
        $schid = (int)$this->_request["schid"];

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            COUNT(AplID) AS studapply,
            AplStatus AS aplstatus,
            FileSubmitted AS flsubmit,
            AplID AS aplid
            FROM applications app
            WHERE app.Deleted = 0 
            AND app.UserID = '$studid'
            AND app.ScholarshipID = '$schid'"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    //image upload
    private function upload_file()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $targetDir = '../uploads/';

        // Create the target directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Get the uploaded file information
        $file = $_FILES['imageFile'];

        $photo_id = $_POST["photo_id"];
        $filename = basename($file['name']);
        // $targetPath = $targetDir . $filename;

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newName = uniqid() . '.' . $extension;
        $newPath = $targetDir . $newName;

        // Move the uploaded file to the target location
        if (move_uploaded_file($file['tmp_name'], $newPath)) {
            $stmt = $this->sitsdb->prepare(
                "UPDATE user_data SET Photo = '$newName'
                WHERE UserID = '$photo_id';"
            );
            $stmt->execute();

            if (!$stmt->execute()) {
                $error = "error";
                return $error;
            } else {
                echo "Image uploaded successfully!";
            }
            $stmt->close();
            $this->sitsdb->close();
        } else {
            echo "Image upload failed.";
        }
    }
    private function remove_img()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $img_obj = json_decode(file_get_contents("php://input"), true);
        $img_obj = str_replace("'", "`", $img_obj);

        $photo_id = (int)$img_obj["photo_id"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE user_data SET Photo = null
            WHERE UserID = '$photo_id';"
        );
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    //file upload

    //pdf upload
    private function upload_pdf()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $targetDir = '../uploads/formpdf/';

        // Create the target directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Get the uploaded file information
        $file = $_FILES['file'];

        $filename = basename($file['name']);

        $newPath = $targetDir . $filename;


        // $this->response($this->json([$newPath]), 200);

        // Move the uploaded file to the target location
        if (move_uploaded_file($file['tmp_name'], $newPath)) {
            $this->response($this->json(["PDF uploaded successfully"]), 200);
        } else {
            $this->response($this->json(["PDF upload failed"]), 200);
        }
    }
    private function file_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $appid = (int)$this->_request["appid"];
        $enid = (int)$this->_request["enid"];
        $userid = (int)$this->_request["userid"];

        if($appid > 0){
            $schfilter = "AND fm.AplID ='$appid'";
        }else{
            $schfilter = "";
        }
        if($enid > 0){
            $enfilter = "AND fm.ENID ='$enid'";
        }else{
            $enfilter = "";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT
            fm.FileID,
            fm.UserID,
            fm.ScholarshipID,
            fm.AplID,
            fm.FileNames,
            fm.FileSize,
            fm.FileType,
            fm.FileDate,
            fm.FileLocation,
            ap.FileSubmitted,
            ap.AplStatus
            FROM filemanager fm
            LEFT JOIN applications ap ON ap.UserID = fm.UserID
            WHERE fm.UserID='$userid' 
            AND fm.Deleted = 0 $schfilter $enfilter
            GROUP BY fm.FileID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function file_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $id = (int)$this->_request["id"];

        $stmt = $this->sitsdb->prepare(
            "SELECT
            fm.FileID,
            fm.UserID,
            fm.ScholarshipID,
            fm.AplID,
            fm.FileNames,
            fm.FileSize,
            fm.FileType,
            fm.FileDate,
            fm.FileLocation
            FROM filemanager fm
            WHERE fm.FileID='$id'
            AND fm.Deleted = 0"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function file_upload()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $targetDir = '../uploads/filesmanager/';

        // Create the target directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Get the uploaded file information
        $file = $_FILES['imageFile'];
        $userid = $_POST['user_id'];
        $schid = $_POST['sch_id'];
        $aplid = $_POST['apl_id'];
        $enid = $_POST['en_id'];

        $fileSize = $file['size'];
        $fileType = $file['type'];

        $filename = basename($file['name']);
        // $targetPath = $targetDir . $filename;

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newName = uniqid() . '.' . $extension;
        $newPath = $targetDir . $newName;

        // Move the uploaded file to the target location
        if (move_uploaded_file($file['tmp_name'], $newPath)) {
            $stmt = $this->sitsdb->prepare(
                "INSERT INTO filemanager (
                    UserID, ScholarshipID, AplID, ENID, FileNames, FileSize, FileType, FileLocation, FileDate
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())"
            );

            if ($stmt) {
                // Bind parameters
                $stmt->bind_param('iiiissss', $userid, $schid, $aplid, $enid, $newName, $fileSize, $fileType, $newPath);

                // Execute the statement
                if ($stmt->execute()) {
                    echo "File uploaded successfully!";
                } else {
                    echo "Error executing query: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Error preparing statement: " . $this->sitsdb->error;
            }

            // Close the database connection
            $this->sitsdb->close();
        } else {
            echo "File upload failed.";
        }
    }
    private function file_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $file_obj = json_decode(file_get_contents("php://input"), true);
        $file_obj = str_replace("'", "`", $file_obj);

        $fileid = (int)$file_obj["fileid"];
        $flocation = (string)$file_obj["flocation"];

        if (file_exists($flocation)) {
            if (unlink($flocation)) {
                $stmt = $this->sitsdb->prepare(
                    "UPDATE filemanager SET Deleted = 1
                    WHERE FileID = '$fileid';"
                );
                $stmt->execute();
            } else {
            }
        }
        $stmt = $this->sitsdb->prepare(
            "UPDATE filemanager SET Deleted = 1
            WHERE FileID = '$fileid';"
        );
        $stmt->execute();
        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function file_submit()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $file_obj = json_decode(file_get_contents("php://input"), true);
        $file_obj = str_replace("'", "`", $file_obj);

        $schid = (int)$file_obj["schid"];
        $userid = (string)$file_obj["userid"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE applications SET FileSubmitted = 1
            WHERE UserID = '$userid'
            AND ScholarshipID = '$schid';"
        );
        $stmt->execute();
        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }
        $stmt->close();
        $this->sitsdb->close();
    }

    //student form scholarship
    private function studform_list()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            sf.StudFormID  AS sfid,
            sf.UserID AS userid,
            sf.FormID AS formid,
            sf.UserNumber AS usernum,
            sf.SFStatus AS sfstatus,
            sf.SFDate as sfdate,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname,
            ud.EmailAddress AS email,
            forms.Title AS subjects
            FROM student_forms sf
            LEFT JOIN user_data ud ON ud.UserID = sf.UserID
            LEFT JOIN forms ON forms.FormID = sf.FormID
            WHERE sf.Deleted = 0 
            AND sf.SFStatus = 0
            ORDER BY sf.StudFormID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function studform_sent()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            sf.StudFormID  AS sfid,
            sf.UserID AS userid,
            sf.UserNumber AS usernum,
            sf.SFStatus AS sfstatus,
            sf.SFDate as sfdate,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname
            FROM student_forms sf
            LEFT JOIN user_data ud ON ud.UserID = sf.UserID
            WHERE sf.Deleted = 0 
            AND sf.SFStatus = 1
            ORDER BY sf.StudFormID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function studform_edit()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $id = (int)$this->_request["id"];

        $stmt = $this->sitsdb->prepare("SELECT 
            sf.StudFormID AS sfid,
            sf.UserID AS userid,
            sf.UserNumber AS usernum,
            sf.FormType AS formtype,
            sf.SFStatus AS sfstatus,
            sf.SFDate as sfdate,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname,
            ud.DateOfBirth AS dob,
            ud.EmailAddress AS email
            FROM student_forms sf
            LEFT JOIN user_data ud ON ud.UserID = sf.UserID
            WHERE sf.Deleted  = 0 AND sf.StudFormID  = $id");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function studform_add()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sf_obj = json_decode(file_get_contents("php://input"), true);
        $sf_obj = str_replace("'", "`", $sf_obj);


        $usernum = (string)$sf_obj["usernum"];
        $userid = (int)$sf_obj["userid"];
        $formid = (int)$sf_obj["formid"];
        $sfstatus = (int)$sf_obj["sfstatus"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO student_forms SET 
                UserNumber='$usernum', 
                UserID='$userid', 
                FormID='$formid', 
                SFStatus='$sfstatus',
                SFDate=NOW();"
        );
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {
            $success = array('status' => "success", "sf_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function studform_update()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sf_obj = json_decode(file_get_contents("php://input"), true);
        $sf_obj = str_replace("'", "`", $sf_obj);


        $id = (int)$sf_obj["id"];
        $sfstatus = (string)$sf_obj["sfstatus"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE student_forms SET 
                SFStatus='$sfstatus'
                WHERE StudFormID = ?;"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function studform_delete()
    {
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $sf_obj = json_decode(file_get_contents("php://input"), true);
        $sf_obj = str_replace("'", "`", $sf_obj);

        $id = (int)$sf_obj["id"];
        $stmt = $this->sitsdb->prepare("UPDATE student_forms SET Deleted = 1 WHERE StudFormID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }

    private function form_list(){
         if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            fs.FormID AS formid,
            fs.ScholarshipID AS schid,
            fs.FormType AS formtype,
            fs.Title AS title,
            fs.Body AS body,
            fs.Deleted as deleted,
            sp.ScholarshipName AS schname
            FROM forms fs
            LEFT JOIN scholarship sp ON sp.ScholarshipID = fs.ScholarshipID
            WHERE fs.Deleted = 0"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function form_edit(){
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $formid = (int)$this->_request["formid"];

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            FormID,
            ScholarshipID,
            Title,
            Body,
            FormLocation,
            Deleted
            FROM forms
            WHERE Deleted = 0
            AND FormID = '$formid'"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function form_add(){
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $form_obj = json_decode(file_get_contents("php://input"), true);
        $form_obj = str_replace("'", "`", $form_obj);

        $schid = (int)$form_obj["schid"];
        $title = (string)$form_obj["title"];
        $body = (string)$form_obj["body"];

        $stmt = $this->sitsdb->prepare(
            "INSERT INTO forms SET 
                ScholarshipID = '$schid',
                Title ='$title',
                Body='$body';"
        );
        $stmt->execute();

        $affected_rows = $stmt->affected_rows;
        $last_id = $this->sitsdb->insert_id;

        if ($affected_rows == 1) {
            $success = array('status' => "success", "form_id" => $last_id);
            $this->response($this->json($success), 200);
        } else {
            $arr = $stmt->error;
            print_r($arr);
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function form_update(){
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $form_obj = json_decode(file_get_contents("php://input"), true);
        $form_obj = str_replace("'", "`", $form_obj);

        $id = (int)$form_obj["id"];
        $schid = (int)$form_obj["schid"];
        $title = (string)$form_obj["title"];
        $body = (string)$form_obj["body"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE forms SET 
                ScholarshipID = '$schid', 
                Title ='$title',
                Body='$body'
                WHERE FormID = '$id';"
        );
        $stmt->execute();

        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function form_delete(){
        if ($this->get_request_method() != 'POST') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }
        $form_obj = json_decode(file_get_contents("php://input"), true);
        $form_obj = str_replace("'", "`", $form_obj);

        $id = (int)$form_obj["id"];

        $stmt = $this->sitsdb->prepare(
            "UPDATE forms SET 
                Deleted = 1
                WHERE FormID = '$id';"
        );
        $stmt->execute();
        
        if (!$stmt->execute()) {
            $error = "error";
            return $error;
        } else {
           unlink("../uploads/formpdf/FORM".$id.".pdf");
            $success = "success";
            return $success;
        }

        $stmt->close();
        $this->sitsdb->close();
    }
    private function get_studemp()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            ud.UserID   AS udid,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname,
            ud.UserNumber AS usernum,
            ud.UserStatus AS userstatus,
            ud.UserType AS usertype
            FROM user_data ud
            LEFT JOIN user u ON ud.UserID = u.UserID
            WHERE ud.Deleted = 0 AND (u.UserID IS NULL OR u.Deleted = 1)
            ORDER BY ud.UserID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function get_users(){
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $userid = (int)$this->_request["userid"];
        // $search = (string)$this->_request["search"];
        $usertype = (int)$this->_request["usertype"];

        if($usertype == 1){
            $userfilter = "AND ud.UserType IN (0, 2, 3, 4)";
        }else{
            $userfilter = "";
        }

        /* if($search != ''){
            $srchfilter = "AND ud.EmailAddress LIKE '%$search%' OR ud.LastName LIKE '%$search%' OR ud.UserNumber LIKE '%$search%'";
        }else{
            $srchfilter = "";
        } */

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            ud.UserID AS userid,
            ud.FirstName AS fname,
            ud.MiddleName AS mname,
            ud.LastName AS lname,
            ud.UserNumber AS usernum,
            ud.UserStatus AS userstatus,
            ud.UserType AS usertype,
            ud.EmailAddress AS email
            FROM user_data ud 
            WHERE ud.Deleted = 0 $userfilter
            ORDER BY ud.UserID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function get_total_scholars()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            COUNT(sr.ScholarID) AS ttqty
            FROM scholars sr
            WHERE sr.Deleted = 0"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function get_total_sch()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            COUNT(ScholarshipID) AS ttqty
            FROM scholarship sp
            WHERE sp.Deleted = 0"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function get_total_emp()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            COUNT(UserID) AS ttqty
            FROM user_data ud
            WHERE ud.Deleted = 0
            AND ud.UserType IN (0, 2, 3, 4)"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function get_total_app()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            COUNT(AplID) AS ttqty
            FROM applications ap
            WHERE ap.Deleted = 0"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }

    //report
    private function report_scholar(){}
    private function report_application(){
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $status = (int)$this->_request["status"];
        $type = (int)$this->_request["type"];
        $fromdate = (string)$this->_request["fromdate"];
        $todate = (string)$this->_request["todate"];

         if ($status != -1) {
            $filstatus = "AND app.AplStatus = '$status'";
        } else {
            $filstatus = "";
        }
        if ($type != 0) {
            $filtype = "AND sch.ScholarshipType = '$type'";
        } else {
            $filtype = "";
        }
       
        if ($fromdate != "" || $todate != "") {
            $filDate = "AND app.AplDate BETWEEN '$fromdate' AND '$todate'";
        } else {
            $filDate = "";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            app.AplID AS aplid,
            app.ScholarshipID AS schid,
            app.AplDate AS apldate,
            app.AplStatus AS aplstatus,
            app.UserID AS userid,
            app.Remarks AS remarks,

            sch.ScholarshipName AS schname,    
            ud.Photo AS photo,
            ud.EmailAddress AS email,

            se.TypeName AS schtype,   
            CONCAT (ud.FirstName,' ',SUBSTRING(ud.MiddleName, 1, 1), '. ', ud.LastName) AS userName,
            CONCAT (ux.FirstName,' ',SUBSTRING(ux.MiddleName, 1, 1), '. ', ux.LastName) AS approveName

            FROM applications app
            LEFT JOIN scholarship sch ON sch.ScholarshipID = app.ScholarshipID
            LEFT JOIN scholarship_type se ON se.TypeID = sch.ScholarshipType
            LEFT JOIN user_data ud ON ud.UserID = app.UserID
            LEFT JOIN user_data ux ON ux.UserID = app.ReviewerID
            WHERE app.Deleted = 0 $filDate $filstatus $filtype
            ORDER BY app.AplID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function report_emailnotif(){
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $fromdate = (string)$this->_request["fromdate"];
        $todate = (string)$this->_request["todate"];

        if ($fromdate != "" || $todate != "") {
            $filDate = "AND en.SentDate BETWEEN '$fromdate' AND '$todate'";
        } else {
            $filDate = "";
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            en.ENID AS enid,
            en.RecipientEmail AS reciptemail,
            en.FromEmail AS fromemail,
            en.SentDate AS sentdate,
            en.AttachementInfo AS attachinfo,
            en.EmailStatus AS emailstatus,
            en.Subjects AS subjects,
            en.Method AS method,
            en.FromEmail AS email
            FROM email_notifications en
            WHERE en.Deleted = 0 $filDate 
            ORDER BY en.ENID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }

    private function home_scheme()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            sch.ScholarshipID AS schid,
            sch.ScholarshipName AS schname,
            sch.ScholarshipStatus AS schstatus, 
            sch.AwardAmount AS awardamnt,
            sch.AwardDate AS awardate,
            sch.Criteria AS criteria,
            sch.DocsRequired AS docrequired,
            sch.FundingSource AS fundsource,
            sch.CreatedBy AS createdby,
            sch.CreatedAt AS createdat,
            sch.UpdatedAt AS updatedat,

            st.TypeName AS typename,
            st.Description AS descriptions,
            
            CONCAT (ud.FirstName,' ',SUBSTRING(ud.MiddleName, 1, 1), '. ', ud.LastName) AS userName,
            CONCAT (SUBSTRING(ud.FirstName, 1,8),'.', SUBSTRING(ud.LastName, 1, 1),'.') AS shortName

            FROM scholarship sch
            LEFT JOIN scholarship_type st ON st.TypeID = sch.ScholarshipType
            LEFT JOIN user_data ud ON ud.UserID = sch.CreatedBy
            WHERE sch.Deleted = 0 AND DATE(sch.CreatedAt) = CURDATE()
            ORDER BY sch.ScholarshipID"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function home_scholars()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            sc.*,
            sp.ScholarshipName,
            st.TypeName,
            ud.FirstName,
            ud.MiddleName,
            ud.LastName,
            ud.UserNumber,
            cs.CourseName,
            cs.CourseCode,
            yn.Years
            FROM scholars sc
            LEFT JOIN scholarship sp ON sp.ScholarshipID = sc.ScholarshipID
            LEFT JOIN scholarship_type st ON st.TypeID = sp.ScholarshipType 
            LEFT JOIN user_data ud ON ud.UserID = sc.UserID
            LEFT JOIN courses cs ON cs.CourseID = ud.CourseID
            LEFT JOIN year_section yn ON yn.YrSectionID = ud.YrSectionID
            WHERE sc.Deleted = 0 AND DATE(sc.ApprovedDate) = CURDATE()
            ORDER BY sc.ApprovedDate DESC"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    private function home_scholars_graduate()
    {
        if ($this->get_request_method() != 'GET') {
            $callback = array("callback" => 'Request method not acceptable');
            $this->response($this->json($callback), 406);
        }

        $stmt = $this->sitsdb->prepare(
            "SELECT 
            sc.*,
            sp.ScholarshipName,
            st.TypeName,
            ud.FirstName,
            ud.MiddleName,
            ud.LastName,
            ud.UserNumber,
            cs.CourseName,
            cs.CourseCode,
            yn.Years,
            yn.Section,
            dt.DeptName
            FROM scholars sc
            LEFT JOIN scholarship sp ON sp.ScholarshipID = sc.ScholarshipID
            LEFT JOIN scholarship_type st ON st.TypeID = sp.ScholarshipType 
            LEFT JOIN user_data ud ON ud.UserID = sc.UserID
            LEFT JOIN courses cs ON cs.CourseID = ud.CourseID
            LEFT JOIN year_section yn ON yn.YrSectionID = ud.YrSectionID
            LEFT JOIN department dt ON dt.DepartmentID  = ud.DepartmentID 
            WHERE sc.Deleted = 0 
            AND sc.ScholarStatus = 3
            ORDER BY sc.ApprovedDate DESC"
        );

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $this->response($this->json($rows), 200);
        } else {
            $this->response('', 204);
        }
        $stmt->close();
        $this->sitsdb->close();
    }
    /** 
     *Encode array into JSON
     */
    public function json($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        }
    }
    // return json with numbers aligning to string
    /*  private function json_num($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_NUMERIC_CHECK);
        }
    } */
}
// initaite api process
$api = new API;
$api->processApi();
