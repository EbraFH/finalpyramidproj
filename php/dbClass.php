<?php
require_once("userClass.php");
require_once("tournamentClass.php");
class dbClass
{
private $host;
private $db;
private $charset;
private $user;
private $pass;
private $opt = array(
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
private $connection;
// public function __construct(string $host= "localhost", string $db = "user4602DB",
// string $charset = "utf8", string $user = "user4602DB_user", string $pass = "12345678")
public function __construct(string $host= "localhost", string $db = "phppyramid",
string $charset = "utf8", string $user = "root", string $pass = "")
{
$this->host = $host;
$this->db = $db;
$this->charset = $charset;
$this->user = $user;
$this->pass = $pass;
}
private function connect()
{//this function gets the variables (host,db,charset)
$dns = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
$this->connection = new PDO($dns, $this->user, $this->pass, $this->opt);
}
public function disconnect()
{
$this->connection = null;
}
//USER SIGNUP
public function insertUsers(userClass $User){
  $this->connect();
  //before adding the user to the databse we have to check if he is already registered.
  //creating a variable that has the value false which indicates that user
  //is not in database
  $isRegistered = false;
  //getting queries from the data base and passing them to the variable result.
  $result = $this->connection -> prepare("SELECT * FROM users");
  $result->execute();
  //looping over all the queries in the database 
  while($row = $result-> fetch(PDO::FETCH_ASSOC))
  {
    //checking if the user id/phoneNumber/email is found in the database
    if($User->getUserId() == $row['userId']){
      $isRegistered = true;//if found our boolean variable will be set to true
      echo "ID is already in use! ";
    }
    if($User->getUserEmail() == $row['userEmail']){
      $isRegistered = true;//if found our boolean variable will be set to true
      echo "Email is already in use!";
    }
    if($User->getUserPhone() == $row['userPhone']){
      $isRegistered = true;//if found our boolean variable will be set to true
      echo "PhoneNumber is already in use!";
    }
  }
    //adding the user to the database if not found
    //passing the query and the parameters separatly to prevent "hackers" from accessing our queries
    if(!$isRegistered){
      //hashing user password
      //prepare is a function that sends a query with parameters to database without executing.
      $hashPassword = password_hash($User->getUserPassword(), PASSWORD_DEFAULT);
      $statement = $this->connection->prepare("INSERT INTO users VALUES(:userId,
      :userName,
      :userPassword,
      :userPhone,
      :userBirthDay,
      :userAddress,
      :userEmail,
      :userRole,
      :userStatus,
      :resetpswd
      )");
      //creating an associative array that will hold the values 
      $user_parameters = [":userId"=> $User->getUserId(),":userName"=>$User->getUserName(),
      ":userPassword"=> $hashPassword, ":userPhone"=>$User->getUserPhone(),
      ":userBirthDay"=>$User->getUserBirthDay(),
      ":userAddress"=>$User->getUserAddress(),
      ":userEmail"=>$User->getUserEmail(),
      ":userRole"=>"Player",
      ":userStatus"=>"Active",
      ":resetpswd"=>NULL];
      //executing the queries
      $statement->execute($user_parameters);
      echo "Registered succesfully. Login Now!";
    }
  $this->disconnect();
}
//USER LOGIN
public function userLogin($userId, $pass){
  //function that returns true or false 
  //function checks if the user ID and password are found in the database
  $this->connect();
  //getting queries from the data base and passing them to the variable result.
  //here result is a binary variable
  $result = $this->connection ->prepare("SELECT * FROM users where userId=:userId"); 
  $result->execute(['userId'=>$userId]);
  $row = $result-> fetch(PDO::FETCH_ASSOC);
  //checking if the user password input is correct with the hashed password in the database
    if(password_verify($pass , $row['userPassword'])){
      $User = new userClass($row['userId'],$row['userName'],$pass,$row['userPhone'],$row['userAddress'],$row['userBirthDay'],$row['userEmail'],$row['userRole'],$row['userStatus']);
      echo $row['userRole'];
      $this->disconnect();
      return $User;
    }
    //delete later after fix , admin
    // if($pass == $row['userPassword']){
    //   $User = new userClass($row['userId'],$row['userName'],$pass,$row['userPhone'],$row['userBirthDay'],$row['userAddress'],$row['userEmail'],$row['userRole'],$row['userStatus']);
    //   $this->disconnect();
    //   echo $row['userRole'];
    //   return $User;
    // }
    $this->disconnect();
  return ;
  }

public function buildTable($result, $rowNums)
    {
        //This function builds the tables
        $this->connect();
        if ($rowNums > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $userid = $row['userId'];
//        class="'.$row['userStatus'].'"
                echo '<tr>';
                echo '<td>' . $row['userId'] . '</td>';
                echo '<td>' . $row['userName'] . '</td>';
                echo '<td>' . $row['userPhone'] . '</td>';
                echo '<td>' . $row['userBirthDay'] . '</td>';
                echo '<td>' . $row['userAddress'] . '</td>';
                echo '<td>' . $row['userEmail'] . '</td>';
                echo '<td>' . $row['userRole'] . '</td>';
                if ($row['userStatus'] == 'Active') {

                    echo '<td> <span class="badge bg-success">' . $row['userStatus'] . '</span></td>';
                } else {
                    echo '<td> <span class="badge bg-danger">' . $row['userStatus'] . '</span></td>';
                }

                echo '<td><button type="button" value="' . $row['userId'] . '" class="editUserBtn btn btn-success">Edit</button></td>';
                if ($row['userStatus'] == 'Active') {
                    echo '<td><button type="button" class="DeActiveUserBtn btn btn-danger" data-id="' . $userid . '">disable</button></td>';
                } else {
                    echo '<td><button type="button" class="activeUserBtn btn btn-success" data-id="' . $userid . '">Activate</button></td>';
                }
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td>' . 'No User Found' . '</td>';
            echo '</tr>';
        }
        $this->disconnect();
        return;
    }

/*Start of  ADMIN VIEW-REGISTER FUNCTIONS*/
public function getAllUsersInfo(){
    //A function that gets all active users data from the database and prints them inside the table
    $this->connect();
    $result = $this->connection -> prepare("SELECT * FROM users WHERE userStatus='Active'"); 
    $result->execute();
    $rowNums = $result->rowCount();
    $this->buildTable($result,$rowNums);
  }
  public function getUserByDate($startDate,$endDate){
    //this function gets all users accroding to staring and ending date
    $this->connect();
    $result = $this->connection->prepare("SELECT * FROM users where userBirthDay BETWEEN '$startDate' AND '$endDate'  
      ");
    $result->execute();
    $rowNums = $result->rowCount();
    $this->buildTable($result,$rowNums);
  }
  public function AdminInsertUsers(userClass $User){
     //this function is for view-register page it adds users to the data 
    $this->connect();
    $isRegistered = false;
    $result = $this->connection -> query("SELECT * FROM users");
    while($row = $result-> fetch(PDO::FETCH_ASSOC))
    {
      //checking if the user id/phoneNumber/email is found in the database
      if($User->getUserId() == $row['userId'] || $User->getUserEmail() == $row['userEmail']|| $User->getUserPhone() == $row['userPhone']){
        $isRegistered = true;//if found our boolean variable will be set to true
      }
    }
    if(!$isRegistered){
      $hashPassword = password_hash($User->getUserPassword(), PASSWORD_DEFAULT);
      $statement = $this->connection->prepare("INSERT INTO users VALUES(:userId,
      :userName,
      :userPassword,
      :userPhone,
      :userBirthDay,
      :userAddress,
      :userEmail,
      :userRole,
      :userStatus,
      :resetpswd
      )");
      //creating an associative array that will hold the values 
      $user_parameters = [":userId"=> $User->getUserId(),":userName"=>$User->getUserName(),
      ":userPassword"=> $hashPassword, ":userPhone"=>$User->getuserPhone(),
      ":userBirthDay"=>$User->getUserBirthDay(),
      ":userAddress"=>$User->getUserAddress(),
      ":userEmail"=>$User->getUserEmail(),
      ":userRole"=>$User->getUserRole(),
      ":userStatus"=>$User->getuserStatus(),
      ":resetpswd"=>NULL];
      //executing the queries
      $statement->execute($user_parameters);
      $res = [
        'status' => 200,
        'message' => 'User Created Succefully'
      ];
      echo json_encode($res);
      return false;
    }
    else{
      $res = [
        'status' => 500,
        'message' => 'User already Exists'
      ];
       echo json_encode($res);
      return false;
    }
    $this->disconnect();
  }
  public function AdminModalUser(UserClass $User){
      //this function is for view-register page it gets the user information from form 
      $this->connect();
      $Id = $User->getUserId();
      $Name=$User->getUserName();
      $Password= $User->getUserPassword();
      $Phone=$User->getuserPhone();
      $BirthDay=$User->getUserBirthDay();
      $Address=$User->getUserAddress();
      $Email=$User->getUserEmail();
      $Role=$User->getUserRole();
      $Status = $User->getUserStatus();
        if($Id == NULL || $Name == NULL ||$Password == NULL ||$BirthDay == NULL ||$Address == NULL ||$Email == NULL ||$Role == NULL ||$Status == NULL){
          $res = [
            'status'=> 422,
            'message'=> 'All fields are mandatory'
          ];
          echo json_encode($res);
          return false;
        }
        else{
          $User = new userClass($Id,$Name,$Password,$Phone,$BirthDay,$Address,$Email,$Role,$Status);
          $this->AdminInsertUsers($User);//adding the user to the database
        }
    $this->disconnect();
  }
  public function getUser($userId){
    $this->connect();
    $query = $this->connection->prepare("SELECT * FROM users WHERE userId = '$userId'");
    $query->execute();
    $user = $query-> fetch(PDO::FETCH_ASSOC);
    $res = [
      'status' => 200,
      'message' => 'User Fetch Successfully by id'
      ,'data' => $user
    ];
    echo json_encode($res);
    return false;
    $this->disconnect();
  }
  public function updateUser(userClass $User){
     $this->connect();
      $Id = $User->getUserId();
      $Name=$User->getUserName();
      $Password= $User->getUserPassword();
      $Phone=$User->getuserPhone();
      $BirthDay=$User->getUserBirthDay();
      $Address=$User->getUserAddress();
      $Email=$User->getUserEmail();
      $Role=$User->getUserRole();
      $Status = $User->getUserStatus();
      if($Id == NULL || $Name == NULL ||$Password == NULL ||$BirthDay == NULL ||$Address == NULL ||$Email == NULL ||$Role == NULL ||$Status == NULL){
        $res = [
            'status'=> 422,
            'message'=> 'All fields are mandatory'
          ];
          echo json_encode($res);
          return false;
      }
      else{
      $hashPassword = password_hash($User->getUserPassword(), PASSWORD_DEFAULT);
      $statement = $this->connection->prepare("UPDATE users SET userId=:userId,
       userName=:userName, userPassword=:userPassword, userPhone=:userPhone, userBirthDay=:userBirthDay,userAddress=:userAddress,userEmail=:userEmail,userRole=:userRole,userStatus=:userStatus WHERE userId = '$Id'");
      
      $user_parameters = [":userId"=> $Id,":userName"=>$Name,
      ":userPassword"=> $hashPassword, ":userPhone"=>$Phone,
      ":userBirthDay"=>$BirthDay,
      ":userAddress"=> $Address,
      ":userEmail"=>$Email,
      ":userRole"=>$Role,
      ":userStatus"=>$Status];
      $statement->execute($user_parameters);
      $res = [
        'status'=> 200,
        'message'=> 'User updated succesffully'
        ];
      echo json_encode($res);
      $this->disconnect();
      return false;
      }
  }
  public function getCurrentUser($Id){
    $this->connect();
    $query = $this->connection->prepare("SELECT * FROM users WHERE userId = '$Id'");
    $query->execute();
    $user = $query-> fetch(PDO::FETCH_ASSOC);
    $res = [
      'message' => 'User Fetch Successfully by id'
      ,'data' => $user
    ];
    echo json_encode($res);
    return false;
    $this->disconnect();
  }
  public function deActivateUser($user_id)
    {
        //This function deActivates User in the view-registered Admin panel
        $this->connect();
        $inActiveStr = "In-Active";
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE userId=:userid");
        $stmt->execute(array(':userid' => $user_id));
        $row = $stmt->fetch();
        $str = $user_id;
        if ($row['userStatus'] == $inActiveStr) {
            $res = [
                'status' => 500,
                'message' => 'User is already In-Active'
            ];
            $this->disconnect();
            echo json_encode($res);
            return false;
        } else {
            $statement = $this->connection->prepare("UPDATE users SET userStatus=:userStatus WHERE userId = '$user_id'");
            $statement->execute(['userStatus' => $inActiveStr]);
            $res = [
                'status' => 200,
                'message' => 'User has been De-Activated'
            ];
            $this->disconnect();
            echo json_encode($res);
            return false;
        }
    }
    public function ActivateUser($user_id)
    {
        //This function reActivates User in the view-registered Admin panel
        $this->connect();
        $inActiveStr = "Active";
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE userId=:userid");
        $stmt->execute(array(':userid' => $user_id));
        $row = $stmt->fetch();
        $str = $user_id;
        if ($row['userStatus'] == $inActiveStr) {
            $res = [
                'status' => 500,
                'message' => 'User is already Active'
            ];
            $this->disconnect();
            echo json_encode($res);
            return false;
        } else {
            $statement = $this->connection->prepare("UPDATE users SET userStatus=:userStatus WHERE userId = '$user_id'");
            $statement->execute(['userStatus' => $inActiveStr]);
            $res = [
                'status' => 200,
                'message' => 'User has been Activated'
            ];
            $this->disconnect();
            echo json_encode($res);
            return false;
        }
    }
  public function fetchBy($selectedOption){
    //this function gets the selected option value and gets its information from the database to create a table 
    //that fits the requirment
    $this->connect();
    $statement = $this->connection->prepare("SELECT * from users WHERE userRole = '$selectedOption' OR userStatus = '$selectedOption'");
    $statement->execute();
    $rowNums = $statement->rowCount();
    $this->buildTable($statement,$rowNums);
  }
/*#end of ADMIN VIEW-REGISTER FUNCTIONS*/
/*DASH BOARD FUNCTIONS*/
public function getTotalUsersCount(){
  //this function gets the total registered users 
  $this->connect();
  $statement= $this->connection->prepare("SELECT * FROM users WHERE userStatus='Active'");
  $statement->execute();
  $rowNums = $statement->rowCount();
  echo '<h4 class="mb-0">'.$rowNums.'</h4>';
  $this->disconnect();
  return  ;
}
public function getTotalTournamentManagerCount(){
  //this function gets all the registered tournament managers
  $this->connect();
  $statement= $this->connection->prepare("SELECT * FROM users WHERE userStatus='Active' AND userRole='Tournament Manager'");
  $statement->execute();
  $rowNums = $statement->rowCount();
  echo '<h4 class="mb-0">'.$rowNums.'</h4>';
  $this->disconnect();
  return  ;
}
public function getMyTotalActiveTournaments($userId){
   $this->connect();
  $statement= $this->connection->prepare("SELECT * FROM tournaments WHERE tournamentStatus='Active' AND tournamentId IN (SELECT tournamentId from tournamentmanagers WHERE userId=:userId)");
  $statement->execute(['userId'=>$userId]);
  $rowNums = $statement->rowCount();
  echo '<h4 class="mb-0">'.$rowNums.'</h4>';
  $this->disconnect();
  return  ;
}
public function getTotalActiveTournaments(){
  //this function gets the total of all the active tournaments
  $this->connect();
  $statement= $this->connection->prepare("SELECT * FROM tournaments WHERE tournamentStatus='Active'");
  $statement->execute();
  $rowNums = $statement->rowCount();
  echo '<h4 class="mb-0">'.$rowNums.'</h4>';
  $this->disconnect();
  return  ;
}
public function getTotalFinishedTournaments(){
  //this function gets the total of the finished tournaments
  $this->connect();
  $statement= $this->connection->prepare("SELECT * FROM tournaments WHERE tournamentEndDate >= CURDATE()");
  $statement->execute();
  $rowNums = $statement->rowCount();
  echo '<h4 class="mb-0">'.$rowNums.'</h4>';
  $this->disconnect();
  return  ;
}
/*END OF DASH BOARD FUNCTIONS*/
/*START OF VIEW-TOURNAMENTS*/
public function buildTournamentTable($result,$rowNums){
  //This function builds the tables
    $this->connect();
    if($rowNums > 0){
    while($row = $result-> fetch(PDO::FETCH_ASSOC)){ 
          echo '<tr class="'.$row['tournamentStatus'].'">';
          echo '<td>' . $row['tournamentId'].'</td>';
          echo '<td>' . $row['tournamentName'].'</td>';
          echo '<td>' . $row['tournamentRegistrationDate'].'</td>';
          echo '<td>' . $row['tournamentStartDate'].'</td>';
          echo '<td>' . $row['tournamentEndDate'].'</td>';
          echo '<td>' . $row['tournamentPlace'].'</td>';
          echo '<td>' . $row['tournamentPrize'].'</td>';
          echo '<td>' . $row['tournamentWinner'].'</td>';
          echo '<td><button type="button" value="'.$row['tournamentId'].'" class="editTournamentBtn btn btn-success">Edit</button></td>';
          echo '<td><button type="button" data-bs-toggle="modal" data-bs-target="#ViewTournamentModal" class="ladderBtn btn btn-danger">
          View-Ladder
          </button></td>';
        echo '</tr>';
      }
    }
    else{
      echo '<tr>';  
      echo '<td>'. 'No Tournaments Found'. '</td>';  
      echo '</tr>'; 
    }
    $this->disconnect();
    return  ;
  }
public function getAllTournaments(){
    //this function gets all the tournaments from the database and creates a table
    $this->connect();
    $result = $this->connection -> prepare("SELECT * FROM tournaments where tournamentStatus=:tournamentStatus"); 
    $result->execute(['tournamentStatus'=>'Active']);
    $rowNums = $result->rowCount();
    $this->buildTournamentTable($result,$rowNums);
  }

 //==========================================================
 //TOURNAMENT FUNCTIONS
 //Creating new tournament and adding it to the database
public function AdminInsertTournament(tournament $tournament, $userId){
  //function that adds a tournament to the database if it doesn't exist
   $this->connect();
   $tournamentExists = false;
  //getting queries from the data base and passing them to the variable result.
    $result = $this->connection -> query("SELECT * FROM tournaments");
    //looping over all the queries in the database 
    while($row = $result-> fetch(PDO::FETCH_ASSOC))
    {
      //Checking if the tournament already exists
      if($tournament->getTournamentName() == $row['tournamentName'] ){
        $tournamentExists = true;//if found our boolean variable will be set to true
      }
    }
    //adding the Tournament to the database if it is not found
    if(!$tournamentExists){
      $statement = $this->connection->prepare("INSERT INTO tournaments VALUES(:tournamentId,
      :tournamentName,
      :tournamentParticipant,
      :tournamentRegistrationDate,
      :tournamentStartDate,
      :tournamentEndDate,
      :tournamentPlace,
      :tournamentPrize,
      :tournamentWinner,
      :tournamentStatus	
      )");
      $manager_parameters = [":tournamentId"=> $tournament->getTournamentId(),":userId"=>$userId];
      //creating an associative array that will hold the values 
      $tournament_parameters = [":tournamentId"=> $tournament->getTournamentId(),":tournamentName"=>$tournament->getTournamentName(),
      ":tournamentParticipant"=> $tournament->getTournamentParticipant(), ":tournamentRegistrationDate"=>$tournament->gettournamentRegistrationDate(),
      ":tournamentStartDate"=>$tournament->getTournamentStartDate(),
      ":tournamentEndDate"=>$tournament->getTournamentEndDate(),
      ":tournamentPlace"=>$tournament->getTournamentPlace(),
      ":tournamentPrize"=>$tournament->getTournamentPrize(),
      ":tournamentWinner"=>$tournament->getTournamentWinner(),
      ":tournamentStatus"=>$tournament->getTournamentStatus()];
      //executing the queries
      $statement->execute($tournament_parameters);
      $second_statement = $this->connection->prepare("INSERT INTO tournamentmanagers VALUES(:tournamentId, :userId)");//query to insert to tournamentmanagers table after creating a tournament
      $second_statement->execute($manager_parameters);//exxecuting the query for tournamentmanagers table
      $res = [
        'status' => 200,
        'message' => 'Tournament Created Succefully'
      ];
      echo json_encode($res);
      return false;
    }
    else{
      $res = [
        'status' => 422,
        'message' => 'Tournament already Exists'
      ];
       echo json_encode($res);
      return false;
    }
  $this->disconnect();
 }
public function getTournament($Id){
  //function that gets all the tournaments that has that certain id from the database
    $this->connect();
    $query = $this->connection->prepare("SELECT * FROM tournaments WHERE tournamentId = '$Id'");
    $query->execute();
    $tournament = $query-> fetch(PDO::FETCH_ASSOC);
    $res = [
      'status' => 200,
      'message' => 'Tournament Fetch Successfully by id'
      ,'data' => $tournament
    ];
    echo json_encode($res);
    return false;
    $this->disconnect();
  }
public function getMyTournaments($Id){
  //function that gets all the tournaments that a certain tournamentmanager made
    $this->connect();
    $query = $this->connection-> prepare("SELECT * FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId=:userId AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournamentStatus=:tournamentStatus");
    $query->execute(['userId'=>$Id,'tournamentStatus'=>'Active']);
    $rowNums = $query->rowCount();
    $this->buildTournamentTable($query,$rowNums);
  }
public function updateTournament(tournament $tournament){
    //function that updates the tournaments in the database
      $this->connect();
      $Id = $tournament->getTournamentId();
      $Name=$tournament->getTournamentName();
      $Participants= $tournament->getTournamentParticipant();
      $CreationDate=$tournament->gettournamentRegistrationDate();
      $StartDate=$tournament->getTournamentStartDate();
      $EndDate=$tournament->getTournamentEndDate();
      $Place=$tournament->getTournamentPlace();
      $Prize=$tournament->getTournamentPrize();
      $Winner=$tournament->getTournamentWinner();
      $Status = $tournament->getTournamentStatus();
      if($Id == NULL || $Name == NULL || $Participants== NULL ||$CreationDate == NULL ||$StartDate == NULL ||$EndDate == NULL ||$Place == NULL ||$Prize == NULL || $Winner == NULL || $Status == NULL){
        $res = [
            'status'=> 422,
            'message'=> 'All fields are mandatory'
          ];
          echo json_encode($res);
          return false;
        }
      else{
      $tournament_parameters = [":tournamentId"=> $Id,":tournamentName"=>$Name,
      ":tournamentParticipant"=>$Participants, ":tournamentRegistrationDate"=>$CreationDate,
      ":tournamentStartDate"=>$StartDate,
      ":tournamentEndDate"=>$EndDate,
      ":tournamentPlace"=>$Place,
      ":tournamentPrize"=>$Prize,
      ":tournamentWinner"=>$Winner,
      ":tournamentStatus"=>$Status]; 
      $statement = $this->connection->prepare("UPDATE tournaments SET
      tournamentId=:tournamentId,
      tournamentName=:tournamentName,
      tournamentParticipant=:tournamentParticipant,
      tournamentRegistrationDate=:tournamentRegistrationDate,
      tournamentStartDate=:tournamentStartDate,
      tournamentEndDate=:tournamentEndDate,
      tournamentPlace=:tournamentPlace,
      tournamentPrize=:tournamentPrize,
      tournamentWinner=:tournamentWinner,
      tournamentStatus=:tournamentStatus WHERE tournamentId = '$Id'");
      $statement->execute($tournament_parameters);
      $res = [
        'status'=> 200,
        'message'=> 'Tournament updated succesffully'
      ];
      echo json_encode($res);
      $this->disconnect();
      return false;
      }
}
public function CreateTournament(tournament $tournament,$userId){
  //function that checks if all the tournaments fields are filled and then creates the tournament in the database
  $this->connect();
  if($tournament->getTournamentName() == NULL || $tournament->getTournamentParticipant() == NULL ||$tournament->getTournamentStartDate() == NULL || $tournament->getTournamentEndDate() == NULL ||  $tournament->getTournamentPlace() == NULL || $tournament->getTournamentPrize() == NULL){
    $res = [
            'status'=> 422,
            'message'=> 'All fields are mandatory'
          ];
          echo json_encode($res);
          return false;
  }
  if($tournament->getTournamentStartDate() > $tournament->getTournamentEndDate()){
    $res = [
            'status'=> 422,
            'message'=> 'Starting date can not be bigger than end date'
          ];
          echo json_encode($res);
          return false;
  }
  else{
    $this->AdminInsertTournament($tournament,$userId);
  }
  $this->disconnect();
 }
 public function tournamentFetchBy($selectedOption){
    //this function gets the selected option value and gets its information from the database to create a table 
    //that fits the requirment
    $this->connect();
    $currDate = new DateTime();
    $result = $currDate->format('Y-m-d');
    if($selectedOption == 'Finished Tournaments'){
    $statement = $this->connection->prepare("SELECT * from tournaments WHERE tournamentStatus=:tournamentStatus");
    $statement->execute(['tournamentStatus'=>'In-Active']);
    $rowNums = $statement->rowCount();
    }
    else if ($selectedOption == 'Active Tournaments'){
    $statement = $this->connection->prepare("SELECT * from tournaments WHERE tournamentStatus=:tournamentStatus");
    $statement->execute(['tournamentStatus'=>'Active']);
    $rowNums = $statement->rowCount();
    }
    else{
      $statement = $this->connection->prepare("SELECT * from tournaments WHERE tournamentstartDate >'$result'");
      $statement->execute();
      $rowNums = $statement->rowCount();
    }
    $this->buildTournamentTable($statement,$rowNums);
  }
  public function getTodaysTournaments(){
    //Function that returns 10 Tournaments that will happen at this day
    $this-> connect();
    $currDate = new DateTime();
    $result = $currDate->format('Y-m-d');
    $statement = $this->connection->prepare("SELECT tournamentName from tournaments WHERE tournamentStatus='Active' AND tournamentStartDate='$result'");
    $statement->execute();
    $rowNums = $statement->rowCount();
    if($rowNums > 0){
    echo '<table class="table">';
    while($row = $statement-> fetch(PDO::FETCH_ASSOC)){
          echo '<tr>';
          echo '<td>' . $row['tournamentName'].'</td>';
      }
        echo '</tr>';
      echo '</table>';
  }
}
public function getMyupcomingGames($userId){
  $this-> connect();
  $currDate = new DateTime();
  $result = $currDate->format('Y-m-d');
  $statement = $this->connection->prepare("SELECT *
    FROM games
    WHERE gameDate > NOW() AND playerAId=:userId or PlayerBId=:userId
    LIMIT 5;");
  $countrows = 0;
  $statement->execute(['userId'=>$userId]);
  $rowNums = $statement->rowCount();
  $array = array();
  echo '<table class="table">';
  if ($rowNums > 0) {
      echo '<th>Date</th>';
      echo '<th>Players</th>';
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
          $playerA = $row['playerAId'];
          $playerB = $row['playerBId'];
          $stmt1= $this->connection->prepare("SELECT userName FROM users WHERE userId ='$playerA' OR userId='$playerB'");
          $stmt1->execute();
          while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $array[] = $row1;
          }
          echo '<tr>';
          echo '<td>' . $row['gameDate'] . '</td>';
          echo '<td>' . $array[0]['userName'] . ' ' . 'VS'. ' ' . $array[1]['userName'] . '</td>';
           echo '</tr>';
        }
        $countrows++;
    }
while($countrows < 5){
   echo '<tr>';
    echo '<td>None</td>';
    echo '<td>None</td>';
  echo '</tr>';
    $countrows++;
   }
   echo '</table>';
  }
public function getUpcomingTournamentsNotParticipatedIn($userId){
  //function that gets upcoming tournaments that the player has not participated in yet
   $this-> connect();
    $currDate = new DateTime();
    $result = $currDate->format('Y-m-d');
    $statement = $this->connection->prepare("SELECT *
    FROM tournaments 
    WHERE tournamentStartDate > NOW() AND tournamentId NOT IN (SELECT tournamentId from participants WHERE userId=:userId)
    ORDER BY tournamentStartDate
    LIMIT 5;");
    $statement->execute(['userId'=>$userId]);
    $rowNums = $statement->rowCount();
    $countrows = 0;
    if($rowNums > 0){
    echo '<table class="table">';
    echo '<th>tournament Name</th>';
    echo '<th>Start Date</th>';
    echo '<th>Status</th>';
    while($row = $statement-> fetch(PDO::FETCH_ASSOC)){
          echo '<tr>';
          echo '<td><button type="button" data-bs-toggle="modal" data-bs-target="#ViewTournamentModal" class="ladderBtn btn btn-dark">'.$row['tournamentName'].'</button></td>';
          echo '<td>'.$row['tournamentStartDate'].'</td>';
          echo '<td>'.$row['tournamentStatus'].'</td>';
          $countrows++;
      } 
       while($countrows < 5){
        echo '<td>None</td>';
        echo '<td>None</td>';
        echo '<td>None</td>';
        $countrows++;
      }
        echo '</tr>';
      echo '</table>';
    }
    else{
      echo '<table class="table">';
    echo '<th>tournament Name</th>';
    echo '<th>Start Date</th>';
    echo '<th>Status</th>';
      while($countrows < 5){
        echo '<tr>';
        echo '<td>None</td>';
        echo '<td>None</td>';
        echo '<td>None</td>';
         echo '</tr>';
        $countrows++;
    }
    echo '</table>';
}
}
public function getMyUpcomingTournaments($userId){
   //this function gets the upcoming tournaments and adds them to the dash board
    $this-> connect();
    $currDate = new DateTime();
    $result = $currDate->format('Y-m-d');
    $statement = $this->connection->prepare("SELECT tournamentName
    FROM tournaments 
    WHERE tournamentStartDate > NOW() AND tournamentId IN (SELECT tournamentId from tournamentmanagers WHERE userId=:userId)
    ORDER BY tournamentStartDate
    LIMIT 2;");
    $statement->execute(['userId'=>$userId]);
    $rowNums = $statement->rowCount();
    if($rowNums > 0){
    echo '<table class="table">';
    while($row = $statement-> fetch(PDO::FETCH_ASSOC)){
          echo '<tr>';
          echo '<td><button type="button" data-bs-toggle="modal" data-bs-target="#ViewTournamentModal" class="ladderBtn btn btn-dark">'.$row['tournamentName'].'</button></td>';
      } 
        echo '</tr>';
      echo '</table>';
    }
}
public function getUpcomingTournaments(){
    //this function gets the upcoming tournaments and adds them to the dash board
    $this-> connect();
    $currDate = new DateTime();
    $result = $currDate->format('Y-m-d');
    $statement = $this->connection->prepare("SELECT tournamentName
    FROM tournaments 
    WHERE tournamentStartDate > NOW() 
    ORDER BY tournamentStartDate
    LIMIT 2;");
    $statement->execute();
    $rowNums = $statement->rowCount();
    if($rowNums > 0){
    echo '<table class="table">';
    while($row = $statement-> fetch(PDO::FETCH_ASSOC)){
          echo '<tr>';
          echo '<td><button type="button" data-bs-toggle="modal" data-bs-target="#ViewTournamentModal" class="ladderBtn btn btn-dark">'.$row['tournamentName'].'</button></td>';
      } 
        echo '</tr>';
      echo '</table>';
    }
  }
public function getMyTournamentsByDate($startDate,$endDate,$Id){
    //this function gets a certain user tournaments accroding to staring and ending date
    $this->connect();
    $result = $this->connection->prepare("SELECT * FROM tournaments,tournamentmanagers where 
    tournamentmanagers.userId='$Id' AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournaments.tournamentRegistrationDate BETWEEN '$startDate' AND '$endDate'");
    $result->execute();
    $rowNums = $result->rowCount();
    $this->buildTournamentTable($result,$rowNums);
  }
public function MytournamentFetchBy($selectedOption,$Id){
    //this function gets the selected option value and gets its information from the database to create a table 
    //that fits the requirment ON THE CURRENT LOGGED IN TOURNAMENT MANAGER
    $this->connect();
    $currDate = new DateTime();
    $result = $currDate->format('Y-m-d');
    if($selectedOption == 'Finished Tournaments'){
    $statement = $this->connection->prepare("SELECT * from tournaments,tournamentmanagers WHERE tournamentmanagers.userId='$Id' AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournaments.tournamentEndDate < '$result'");
    $statement->execute();
    $rowNums = $statement->rowCount();
    }
    else if ($selectedOption == 'Active Tournaments'){
    $statement = $this->connection->prepare("SELECT * from tournaments,tournamentmanagers WHERE tournamentmanagers.userId='$Id' AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournamentStatus='Active'");
    $statement->execute();
    $rowNums = $statement->rowCount();
    }
    else{
      $statement = $this->connection->prepare("SELECT * from tournaments,tournamentmanagers WHERE tournamentmanagers.userId='$Id' AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournamentstartDate >'$result'");
      $statement->execute();
      $rowNums = $statement->rowCount();
    }
    $this->buildTournamentTable($statement,$rowNums);
  }  
public function getMyTournamentsList($Id){
  //function that gets all the tournaments that a certain tournamentmanager made and adds them to a selection list
    $this->connect();
    $currDate = new DateTime();
    $result = $currDate->format('Y-m-d');
    $query = $this->connection-> prepare("SELECT tournamentName FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId='$Id' AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournaments.tournamentEndDate > '$result'");
    $query->execute();
   
    while($row = $query-> fetch(PDO::FETCH_ASSOC)){
      echo '<option value='.$row['tournamentName'].'>' . $row['tournamentName'].'</option>';
    }
    $this->disconnect();
    return  ;
}
public function viewTmanagers(){
  $this->connect();
  $managers = $this->connection->prepare("SELECT userName,users.userId,userPhone FROM users WHERE users.userRole =:userRole and users.userStatus=:userStatus");
  $managers->execute(['userRole'=>"Tournament Manager",'userStatus'=>"Active"]);
  while($manager = $managers-> fetch(PDO::FETCH_ASSOC)){ 
    $tournamentCount = $this->connection->prepare("SELECT COUNT(*) FROM tournamentmanagers WHERE userId=:userId");
    $tournamentCount->execute(['userId'=>$manager['userId']]);
    $count = $tournamentCount -> fetch(PDO::FETCH_ASSOC);
    echo '<tr>';
          echo '<td>'. $manager['userName'].'</td>';
          echo '<td>' . $manager['userId'].'</td>';
          echo '<td>' . $manager['userPhone'].'</td>';
          echo '<td>' .$count['COUNT(*)'][0].'</td>';
    echo '</tr>';
  }
  $this->disconnect();
  return ;
}
public function getTournamentParticipants($selectedOption, $Id){
  //function that gets all tournament participants after the selection of the tournament from the option list
  $this->connect();
  //getting the participants in ascending order
  $statement = $this->connection->prepare("SELECT * from rankings WHERE   rankings.tournamentId IN (SELECT tournaments.tournamentId FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId = '$Id' AND tournaments.tournamentName = '$selectedOption')
AND rankings.playerId  IN(SELECT userId from participants WHERE tournamentId IN(SELECT tournaments.tournamentId FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId = '$Id' AND tournaments.tournamentName = '$selectedOption')) ORDER BY rankingNum ASC");
  $statement->execute();
  $rowNums = $statement->rowCount();
  if($rowNums > 0){
    echo'<button id="AddParticipantbtn" value ='.$selectedOption.'-'.$Id.' type="button" class="btn btn-primary " data-bs-toggle="modal"data-bs-target="#participantModal">
                            Add Participant
                    </button>';
    while($row = $statement-> fetch(PDO::FETCH_ASSOC)){ 
          echo '<tr>';
          echo '<td>'. $row['rankingNum'].'</td>';
          echo '<td>' . $row['playerId'].'</td>';
          echo '<td>' . $row['playerName'].'</td>';
          echo '<td>' .$row['status'].'</td>';
          if($row['status'] == 'Active'){
             echo '<td><button type="button" value="'.$row['playerId']."-" . $selectedOption."-".$row['rankingNum'].'" class="removeParticipantBtn btn btn-success">Remove</button></td>';
          }
        echo '</tr>';
      }
    }
    else{
      echo'<button  name='.$selectedOption.' type="button" class="btn btn-primary" data-bs-toggle="modal"         data-bs-target="#participantModal">
                            Add Participant
                    </button>';
      echo '<tr>';  
      echo '<td>'. 'No Participants Found'. '</td>';  
      echo '</tr>'; 
    }
    $this->disconnect();
    return  ;
  }
public function addParticipant($ParticipantId,$tournament_name,$managerId){
  //this function allows the tournament manager to add a participant to his tournament if he doesn't exist and if tournament
  //already has less than 15 players.
  $maxTournamentParticipants = 15;
  $this->connect();
  //Getting the participant we trying to add from the table to check if he exists
  $statement = $this->connection->prepare("SELECT * from participants,users,tournaments,tournamentmanagers WHERE tournamentmanagers.userId='$managerId' AND tournaments.tournamentName='$tournament_name' AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournaments.tournamentId = participants.tournamentId AND participants.userId = '$ParticipantId'");
  //getting the amount of participants in the tournament
  $tournament_participants = $this->connection->prepare("SELECT count(userId) from participants WHERE tournamentId IN(SELECT tournaments.tournamentId FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId = '$managerId' AND tournaments.tournamentName = '$tournament_name')");
  $tournament_participants->execute();
  $statement->execute();
  $row = $tournament_participants-> fetch(PDO::FETCH_ASSOC);
  //checking if the participant exists in the tournament
  if($statement->rowCount() > 0){
     $res = [
        'status' => 422,
        'message' => 'Participant Already in Tournament.'
      ];
      echo json_encode($res);
      return false;
    }
    //checking if the tournament participants amount has reached the maximum
    else if ($row['count(userId)'] == $maxTournamentParticipants){
    $res = [
        'status' => 422,
        'message' => 'Tournament has Reached the maximum amount of Participants'
      ];
      echo json_encode($res);
      return false;
    }
    else{
      //getting the tournament Id of the tournament we trying to add the participant into
      $tournamentId = $this->connection->prepare("SELECT tournaments.tournamentId FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId = '$managerId' AND tournaments.tournamentName = '$tournament_name'");
      $tournamentId->execute();
      $tournamentIdFetch = $tournamentId -> fetch(PDO::FETCH_ASSOC);
      $tournamentIdVariable = $tournamentIdFetch['tournamentId'];
      //getting the user information to check if the id is valid
      $userExists = $this->connection->prepare("SELECT * FROM users WHERE userId = '$ParticipantId'");
      $userExists->execute();
      //checking if the id is valid
      if($userExists->rowCount() > 0){
      //inserting the participant to the participants table
      $Pstatement = $this->connection->prepare("INSERT INTO participants VALUES(:userId, :tournamentId)");
      $participant_parameters = [":userId"=>$ParticipantId,":tournamentId"=>$tournamentIdFetch['tournamentId']];
      $Pstatement->execute($participant_parameters);
      //inserting the participant to the rankings table
      $ranking_statement = $this->connection->prepare("INSERT INTO rankings VALUES(:rankingNum,:tournamentId,:playerId,:playerName,:status)");
      $player = $userExists-> fetch(PDO::FETCH_ASSOC);
      $getRanks = $this->connection->prepare("SELECT count(rankingNum) FROM rankings WHERE tournamentId = '$tournamentIdVariable' AND status='Active'");
      $getRanks->execute();
      $fetchGetRanks = $getRanks -> fetch(PDO::FETCH_ASSOC);
      $ranking_parameters = [":rankingNum"=>(int)$fetchGetRanks['count(rankingNum)']+1,":tournamentId"=>$tournamentIdFetch['tournamentId'],":playerId"=>$ParticipantId,":playerName"=>$player['userName'],":status"=>"Active"];
      $ranking_statement->execute($ranking_parameters);
      $res = [
        'status' => 200,
        'message' => 'Participant has been added.'
      ];
       echo json_encode($res);
       $this->disconnect();
      return false;
    }
    else{
      $res = [
        'status' => 422,
        'message' => 'Participant Id does not exist.'
      ];
       echo json_encode($res);
       $this->disconnect();
      return false;
    }
    }
  }
public function buildRunningTournamentLadderList($Id,$role){
  //function that builds for view-ladder the tournament names tables.
  $this->connect();
  $currDate = new DateTime();
  $result = $currDate->format('Y-m-d');
  if($role == 'Tournament Manager'){
  $query = $this->connection-> prepare("SELECT * FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId='$Id' AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournaments.tournamentEndDate > '$result'");
  }
  else if(($role == 'Admin')){
    $query = $this->connection-> prepare("SELECT DISTINCT users.userId,users.userName,tournaments.tournamentName,tournaments.tournamentId from tournaments,users,tournamentmanagers
    where users.userId = tournamentmanagers.userId AND tournaments.tournamentId = tournamentmanagers.tournamentId AND tournaments.tournamentEndDate > '$result'");
  }
  else{
    $query = $this->connection-> prepare("SELECT * FROM tournaments WHERE tournaments.tournamentId IN (SELECT tournamentId FROM participants WHERE userId='$Id')AND tournamentId IN (SELECT tournamentId from rankings WHERE playerId='$Id' AND status='Active')");
  }
  $query->execute();
  while($row = $query-> fetch(PDO::FETCH_ASSOC)){
    echo '<tr>';
    if($role == 'Admin'){
      echo '<td>'.$row['userName'].'</td>';
    }
    echo '<td><button type="button" value="'.$row['tournamentId'].'" class="tournamentbtn btn btn-dark">'.$row['tournamentName'].'</button></td>';
    echo '</tr>';
  }
  $this->disconnect();
  return  ;
}
public function quitTournament($userId,$tournamentId,$playerRank){
   $this->connect();
   //a query to update the rank of the disabled participant
   $disablePlayer = $this->connection->prepare("UPDATE rankings SET rankingNum=:rankingNum,tournamentId=:tournamentId,playerId=:playerId,status=:status WHERE tournamentId = '$tournamentId' AND playerId = '$userId'");
   //query to count the InActive players in the tournament
   $count_inActive = $this->connection->prepare("SELECT count(status) FROM rankings where tournamentId = '$tournamentId' AND status = 'InActive'");
   $count_inActive->execute();
   $fetch_inActiveCount = $count_inActive-> fetch(PDO::FETCH_ASSOC);
   //update the ranking table for the active players.
   $getParticipants = $this->connection->prepare("SELECT * FROM rankings WHERE tournamentId='$tournamentId' AND rankingNum > '$playerRank' ");
   $getParticipants->execute();
   //query that updates the brackets according to the disabled participant.
   while( $fetch_getParticipants = $getParticipants-> fetch(PDO::FETCH_ASSOC)){
    $playerId = $fetch_getParticipants['playerId'];
    $updateRanks = $this->connection->prepare("UPDATE rankings SET rankingNum=:rankingNum WHERE tournamentId = '$tournamentId' AND status='Active' AND rankingNum > '$playerRank' AND playerId = $playerId");
    $updateRanks_parameters = ["rankingNum"=>(int)$fetch_getParticipants['rankingNum'] - 1];
    $updateRanks->execute($updateRanks_parameters);
   }
  $disablePlayer_parameters = ["rankingNum"=>15-(int)$fetch_inActiveCount['count(status)'] , "tournamentId"=>$tournamentId,"playerId"=>$userId,"status"=>"InActive"];
  $disablePlayer->execute($disablePlayer_parameters);
   $res = [
        'status' => 200,
        'message' => 'you have been removed.'
      ];
       echo json_encode($res);
       $this->disconnect();
      return false;
}
public function  disableParticipant ($participantId,$tournamentName,$tournamentManager,$playerRank){
  //function that disables the player from the tournament and updates the tournament bracket
   $this->connect();
   //query to get the tournament Id we want to use
   $tournamentId = $this->connection->prepare("SELECT tournaments.tournamentId FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId = '$tournamentManager' AND tournaments.tournamentName = '$tournamentName'");
   $tournamentId->execute();
   $fetchT_id = $tournamentId-> fetch(PDO::FETCH_ASSOC);
   $TournamentId_var = $fetchT_id['tournamentId'];//a variable to use in the queries 
   //a query to update the rank of the disabled participant
   $disablePlayer = $this->connection->prepare("UPDATE rankings SET rankingNum=:rankingNum,tournamentId=:tournamentId,playerId=:playerId,status=:status WHERE tournamentId = '$TournamentId_var' AND playerId = '$participantId'");
   //query to count the InActive players in the tournament
   $count_inActive = $this->connection->prepare("SELECT count(status) FROM rankings where tournamentId = '$TournamentId_var' AND status = 'InActive'");
   $count_inActive->execute();
   $fetch_inActiveCount = $count_inActive-> fetch(PDO::FETCH_ASSOC);
   //update the ranking table for the active players.
   $getParticipants = $this->connection->prepare("SELECT * FROM rankings WHERE tournamentId IN(SELECT tournaments.tournamentId FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId = '$tournamentManager' AND tournaments.tournamentName = '$tournamentName') AND rankingNum > '$playerRank' ");
   $getParticipants->execute();
   //query that updates the brackets according to the disabled participant.
   while( $fetch_getParticipants = $getParticipants-> fetch(PDO::FETCH_ASSOC)){
    $playerId = $fetch_getParticipants['playerId'];
    $updateRanks = $this->connection->prepare("UPDATE rankings SET rankingNum=:rankingNum WHERE tournamentId = '$TournamentId_var' AND status='Active' AND rankingNum > '$playerRank' AND playerId = $playerId");
    $updateRanks_parameters = ["rankingNum"=>(int)$fetch_getParticipants['rankingNum'] - 1];
    $updateRanks->execute($updateRanks_parameters);
   }
  $disablePlayer_parameters = ["rankingNum"=>15-(int)$fetch_inActiveCount['count(status)'] , "tournamentId"=>$TournamentId_var,"playerId"=>$participantId,"status"=>"InActive"];
  $disablePlayer->execute($disablePlayer_parameters);
  $res = [
        'status' => 200,
        'message' => 'Participant has been disabled.'
      ];
       echo json_encode($res);
       $this->disconnect();
      return false;
}
public function getTournamentBracket($tournamentId,$userId,$userRole){
  //function that builds the tournament bracket according to the picked tournament
  $this->connect();
  $tournament = $this->connection->prepare("SELECT * FROM rankings where tournamentId = '$tournamentId' ORDER BY rankingNum asc");
  $tournament->execute();
  if ($tournament->rowCount() == 0){
    echo '<h1 style="text-align:center;">No participants yet</h1>';
  }
  else{ 
    while($row = $tournament->fetch(PDO::FETCH_ASSOC)){
      $test [] = $row;
    }
    // $test2 = array_column($test,'rankingNum');
    // print_r($test2); 
    echo '<div  class = "tId row 1 my-1">';
   if(in_array("1",array_column($test,'rankingNum'))){
      $rank = array_search('1',array_column($test,'rankingNum'));
      echo '<div class="block 1"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 1"><h1>'."None".'</h1></div>';
    }
    echo '</div>';
    echo '<div class = "row 2 my-1">';
    if(in_array("2",array_column($test,'rankingNum'))){
      $rank = array_search('2',array_column($test,'rankingNum'));
      echo '<div class="block 2"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 2"><h1>'."None".'</h1></div>';
    }
    if(in_array("3",array_column($test,'rankingNum'))){
      $rank = array_search('3',array_column($test,'rankingNum'));
      echo '<div class="block 3"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 3"><h1>'."None".'</h1></div>';
    }
   echo '</div>';  
   echo '<div class = "row 3 my-1">';
     if(in_array("4",array_column($test,'rankingNum'))){
      $rank = array_search('4',array_column($test,'rankingNum'));
      echo '<div class="block 4"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 4"><h1>'."None".'</h1></div>';
    }
    if(in_array("5",array_column($test,'rankingNum'))){
      $rank = array_search('5',array_column($test,'rankingNum'));
      echo '<div class="block 5"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 5"><h1>'."None".'</h1></div>';
    }
    if(in_array("6",array_column($test,'rankingNum'))){
      $rank = array_search('6',array_column($test,'rankingNum'));
      echo '<div class="block 6"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 6"><h1>'."None".'</h1></div>';
    }
     echo '</div>';
     echo '<div class ="row 4 my-1">';
    if(in_array("7",array_column($test,'rankingNum'))){
      $rank = array_search('7',array_column($test,'rankingNum'));
      echo '<div class="block 7"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 7"><h1>'."None".'</h1></div>';
    }
    if(in_array("8",array_column($test,'rankingNum'))){
      $rank = array_search('8',array_column($test,'rankingNum'));
      echo '<div class="block 8"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 8"><h1>'."None".'</h1></div>';
    }
    if(in_array("9",array_column($test,'rankingNum'))){
      $rank = array_search('9',array_column($test,'rankingNum'));
      echo '<div class="block 9"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 9"><h1>'."None".'</h1></div>';
    }
    if(in_array("10",array_column($test,'rankingNum'))){
      $rank = array_search('10',array_column($test,'rankingNum'));
      echo '<div class="block 10"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 10"><h1>'."None".'</h1></div>';
    }
    echo '</div>';
      echo '<div class="row 5 my-1">';
      /*===============================================*/
    if(in_array("11",array_column($test,'rankingNum'))){
      $rank = array_search('11',array_column($test,'rankingNum'));
      echo '<div class="block 11"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 11"><h1>'."None".'</h1></div>';
    }
    if(in_array("12",array_column($test,'rankingNum'))){
      $rank = array_search('12',array_column($test,'rankingNum'));
      echo '<div class="block 12"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 12"><h1>'."None".'</h1></div>';
    }
    if(in_array("13",array_column($test,'rankingNum'))){
      $rank = array_search('13',array_column($test,'rankingNum'));
      echo '<div class="block 13"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 13"><h1>'."None".'</h1></div>';
    }
    if(in_array("14",array_column($test,'rankingNum'))){
      $rank = array_search('14',array_column($test,'rankingNum'));
      echo '<div class="block 14"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 14"><h1>'."None".'</h1></div>';
    }
    if(in_array("15",array_column($test,'rankingNum'))){
      $rank = array_search('15',array_column($test,'rankingNum'));
      echo '<div class="block 15"><button class="participant btn-outline-danger btn-lg" data-bs-toggle="modal" data-bs-target="#ladderParticipantModal" value="'.$test[$rank]['playerId'].' '.$tournamentId.' ">'.$test[$rank]['playerName'].'</button></div>';
    }
    else{
      echo '<div class="block 15"><h1>'."None".'</h1></div>';
    }
    echo '</div>';
    if($userRole =='Player')
        foreach($test as $player)
          if($player['playerId'] == $userId)
        echo '<button class="quitTournament btn btn-danger" value='.$userId.'-'.$tournamentId.'-'.$player['rankingNum'].'>Quit</button>';
  }
  $this->disconnect();
  return ;
}

public function ladderPageFetchBy($selectedOption,$role,$id){
  //this function gets the fetched tournaments in the ladder page for admin and tournament managers
  $this->connect();
   $currDate = new DateTime();
  $result = $currDate->format('Y-m-d');
  if($role == 'Admin'){
    if($selectedOption == 'Finished Tournaments'){
    $statement = $this->connection->prepare("SELECT DISTINCT users.userId,users.userName,tournaments.tournamentName,tournaments.tournamentId from tournaments,users,tournamentmanagers
    where users.userId = tournamentmanagers.userId AND tournaments.tournamentId = tournamentmanagers.tournamentId AND tournaments.tournamentEndDate < '$result'");
    $statement->execute();

    }
    else if ($selectedOption == 'Active Tournaments'){
    $statement = $this->connection->prepare("SELECT DISTINCT users.userId,users.userName,tournaments.tournamentName,tournaments.tournamentId from tournaments,users,tournamentmanagers
    where users.userId = tournamentmanagers.userId AND tournaments.tournamentId = tournamentmanagers.tournamentId AND tournaments.tournamentEndDate > '$result'");
    $statement->execute();
    }
  }
  else if($role == 'Tournament Manager'){
    if($selectedOption == 'Finished Tournaments'){
    $statement = $this->connection->prepare("SELECT * FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId='$id' AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournaments.tournamentEndDate < '$result'");
    $statement->execute();

    }
    else if ($selectedOption == 'Active Tournaments'){
    $statement = $this->connection->prepare("SELECT * FROM tournaments,tournamentmanagers WHERE tournamentmanagers.userId='$id' AND tournamentmanagers.tournamentId = tournaments.tournamentId AND tournaments.tournamentEndDate > '$result'");
    $statement->execute();
    }
  }
  else{
    if($selectedOption == 'Finished Tournaments'){
    $statement = $this->connection->prepare("SELECT * FROM tournaments WHERE tournaments.tournamentId IN (SELECT tournamentId FROM participants WHERE userId='$id') AND tournaments.tournamentEndDate < '$result'");
    $statement->execute();
    }
    else if ($selectedOption == 'Active Tournaments'){
    $statement = $this->connection->prepare("SELECT * FROM tournaments WHERE tournaments.tournamentId IN (SELECT tournamentId FROM participants WHERE userId='$id') AND tournaments.tournamentEndDate > '$result'");
    $statement->execute();
    }
  }
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
  echo '<tr>';
    if($role == 'Admin'){
      echo '<td>'.$row['userName'].'</td>';
    }
  echo '<td><button type="button" value="'.$row['tournamentId'].'" class="tournamentbtn btn btn-dark">'.$row['tournamentName'].'</button></td>';
  echo '</tr>';
  }
  $this->disconnect();
}


/*START OF VIEW-GAMES*/
public function getMyMatches($tournamentId,$role,$userId){
  $this->connect();
  if($role != 'Player'){
  $stmt = $this->connection->prepare("SELECT * FROM games WHERE tournamentId='$tournamentId'");
  }
  else{
    $stmt = $this->connection->prepare("SELECT * FROM games WHERE tournamentId='$tournamentId' AND gameStatus='Active' AND (playerAId='$userId' OR playerBId='$userId')");
  }
  $stmt->execute();
  $array = array();
  if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $playerA = $row['playerAId'];
          $playerB = $row['playerBId'];
          $stmt1= $this->connection->prepare("SELECT userName FROM users WHERE userId ='$playerA' OR userId='$playerB'");
          $stmt1->execute();
          while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $array[] = $row1;
          }
          echo '<tr>';
          echo '<td>' . $row['gameId'] . '</td>';
          echo '<td>' . $row['gameDate'] . '</td>';
          echo '<td>' . $array[0]['userName'] . ' ' . 'VS'. ' ' . $array[1]['userName'] . '</td>';
          if($role != 'Player'){
          echo '<td>' . $row['gameWinner'] . '</td>';
          }else{
            echo '<td><button  data-bs-toggle="modal" data-bs-target="#WinnerModal" id="won" class="wonGame btn btn-success"  value="'.$row['gameId'].'">Won</button></td>';
            echo '<td><button  id="lost" class="lostGame btn btn-danger"  value="'.$row['gameId'].'">Lost</button></td>';
            echo '<td><button  class="CancelGame btn btn-danger"  value="'.$row['gameId'].'">Cancel</button></td>';
          }
          echo '</tr>';
    } 
  }
    else {
            echo '<tr>';
            echo '<td>' . 'No Games Found' . '</td>';
            echo '</tr>';
  }
  $this->disconnect();
  return ;
}
public function getgamesByDate($startDate,$endDate){
    //function that gets all games according to a certain date
    $this->connect();
    $stmt = $this->connection->prepare("SELECT * FROM games where gameDate BETWEEN '$startDate' AND '$endDate'  
      ");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $playerA = $row['playerAId'];
          $playerB = $row['playerBId'];
          $stmt1= $this->connection->prepare("SELECT userName FROM users WHERE userId ='$playerA' OR userId='$playerB'");
          $stmt1->execute();
          while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $array[] = $row1;
          }
          echo '<tr>';
          echo '<td>' . $row['gameId'] . '</td>';
          echo '<td>' . $row['gameDate'] . '</td>';
          echo '<td>' . $array[0]['userName'] . ' ' . 'VS'. ' ' . $array[1]['userName'] . '</td>';
          if($role != 'Player'){
          echo '<td>' . $row['gameWinner'] . '</td>';
          }
          echo '</tr>';
    } 
  }
    else {
            echo '<tr>';
            echo '<td>' . 'No Games Found' . '</td>';
            echo '</tr>';
   }
  }
//get suspended matches
public function suspendedMatches($role,$userId){
  $this->connect();
  if($role == 'Admin'){
    $stmt = $this->connection->prepare("SELECT * FROM games WHERE gameStatus=:gameStatus");
    $stmt->execute(['gameStatus'=>'Suspended']);
  }
  elseif($role == 'Tournament Manager'){
    $stmt = $this->connection->prepare("SELECT * FROM games WHERE gameStatus=:gameStatus AND tournamentId in (SELECT tournamentId from tournamentmanagers WHERE tournamentmanagers.userId=:userId)");
    $stmt->execute(['gameStatus'=>'Suspended','userId'=>$userId]);
  }
  else{//for player
    $stmt = $this->connection->prepare("SELECT * FROM games WHERE gameStatus=:gameStatus AND (playerAId=:userId OR playerBId=:userId)");
    $stmt->execute(['gameStatus'=>"Suspended",'userId'=>$userId]);
  }
  $array = array();
  if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $playerA = $row['playerAId'];
          $playerB = $row['playerBId'];
          $stmt1= $this->connection->prepare("SELECT userName FROM users WHERE userId ='$playerA' OR userId='$playerB'");
          $stmt1->execute();
          while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $array[] = $row1;
          }
          echo '<tr>';
          echo '<td>' . $row['gameId'] . '</td>';
          echo '<td>' . $row['gameDate'] . '</td>';
          echo '<td>' . $array[0]['userName'] . ' ' . 'VS'. ' ' . $array[1]['userName'] . '</td>';
          if($role != 'Player'){
          echo '<td>' . $row['gameWinner'] . '</td>';
          }
          echo '</tr>';
    } 
  }
    else {
            echo '<tr>';
            echo '<td>' . 'No Games Found' . '</td>';
            echo '</tr>';
  }
  $this->disconnect();
  return ;
}
//cancel a match
public function cancelMatch($gameId){
  $this->connect();
  $statement = $this->connection->prepare("UPDATE games SET gameStatus=:gameStatus WHERE gameId = '$gameId'");
  $statement->execute(['gameStatus' => 'Cancelled']);
  $getFirstPlayerEmail = $this->connection->prepare("SELECT * FROM users where userId IN (SELECT playerAId from games WHERE gameId=:gameId)");
  $getSecondPlayer = $this->connection->prepare("SELECT * FROM users where userId IN (SELECT playerBId from games WHERE gameId=:gameId)");
  $getFirstPlayerEmail->execute(['gameId'=>$gameId]);
  $getSecondPlayer->execute(['gameId'=>$gameId]);
  $firstPlayer=$getFirstPlayerEmail->fetch(PDO::FETCH_ASSOC);
  $email = $firstPlayer['userEmail'];
  $playerName = $firstPlayer['userName'];
  $secondPlayer=$getSecondPlayer->fetch(PDO::FETCH_ASSOC);
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
  $headers .= "From:<notification@gmail.com>\n";
  $postedemail = $secondPlayer['userEmail'];
  $subject = "Your Match has been cancelled By your rival";
  $message = "";
  $message = "We sorry to announce that your rival".$playerName. " has cancelled the match". "<br>";
  mail($postedemail, $subject, $message, $headers);
  $res = [
      'status' => 200,
      'message' => 'Match has been cancelled'
    ];
    echo json_encode($res);
    $this->disconnect();
    return false;
}
/*END OF VIEW-GAMES*/

/*START OF VIEW-PARTICIPANTS FOR ADMIN*/
public function getAllTournamentsAndManagers(){
  
  $this->connect();
  $stmt =$this->connection->prepare("SELECT DISTINCT users.userId,users.userName,tournaments.tournamentName,tournaments.tournamentId from tournaments,users,tournamentmanagers
where users.userId = tournamentmanagers.userId AND tournaments.tournamentId = tournamentmanagers.tournamentId");
  $stmt->execute();
  while( $row=$stmt->fetch(PDO::FETCH_ASSOC)){
    echo '<tr>';
    echo '<td>'.$row['userName'].'</td>';
    echo '<td><button data-bs-toggle="modal" data-bs-target="#AdminparticipantModal" class=getAdminParticipants name='.$row['tournamentName'].' value="'.$row['tournamentId'].'-'.$row['userId'].'">'.$row['tournamentName'].'</button></td>';
    echo '</tr>';
  }
  
}
/*END OF VIEW-PARTICIPANTS FOR ADMIN*/
public function loadChartData($fromDate,$toDate,$role,$userId){
        $data=array();
        $this->connect();
        if($role != 'Tournament Manager'){
        if(is_null($fromDate) && is_null($toDate)){
          $sql="SELECT count(*) as tournaments, tournamentRegistrationDate FROM `tournaments` group by tournamentRegistrationDate order by tournamentRegistrationDate";
        }
        else{
          $sql="SELECT count(*) as tournaments, tournamentRegistrationDate FROM `tournaments` WHERE tournamentRegistrationDate BETWEEN '$fromDate' AND '$toDate' group by tournamentRegistrationDate order by tournamentRegistrationDate";
        }
      }
      else{
        if(is_null($fromDate) && is_null($toDate)){
          $sql="SELECT count(*) as tournaments, tournamentRegistrationDate FROM `tournaments` WHERE tournamentId IN(SELECT tournamentId from tournamentmanagers WHERE userId='$userId')group by tournamentRegistrationDate order by tournamentRegistrationDate";
        }
        else{
          $sql="SELECT count(*) as tournaments, tournamentRegistrationDate FROM `tournaments` WHERE tournamentId IN(SELECT tournamentId from tournamentmanagers WHERE userId='$userId') AND tournamentRegistrationDate BETWEEN '$fromDate' AND '$toDate' group by tournamentRegistrationDate order by tournamentRegistrationDate";
        }
      }
        if($result=$this->connection->query($sql)){
            while( $row=$result->fetch(PDO::FETCH_ASSOC)){
                $data[]=$row;
            }
            echo json_encode($data);
        }else{
            return "failed to load sql data";
        }
    }
public function resetPassword($email)
    {
        $this->connect();
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE userEmail=:email");
        $stmt->execute(array(':email' => $email));
        $is_exist = $stmt->rowCount();
        $six_digit_random_number = random_int(100000, 999999);
        if ($is_exist > 0) {
            $statement = $this->connection->prepare("UPDATE users SET resetpswd=:restePswd WHERE userEmail = :email");
            $statement->execute(['restePswd' => $six_digit_random_number, 'email' => $email]);
            $password_link = "http://localhost/tournamentsite/index.php?resetpswd=" . $six_digit_random_number . '&email=' . $email;
            $this->sendPasswordToEmail($email, $password_link);
            $this->disconnect();
        } else {
            echo "email not found";
            $this->disconnect();
        }
    }//resetPassword
 public function sendPasswordToEmail($email, $password_link)
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From:<notification@gmail.com>\n";
        $postedemail = $email;
        $subject = "Reset Password Email";
        $message = "";
        $message = "Here is your password reset link" . "<br>";
        $message .= "Please click on this password reset link " . $password_link;
        if (mail($postedemail, $subject, $message, $headers)) {
            echo "true";
        } else {
            echo "false";
        }

    }//sendPasswordToEmail
public function verifySendPassword($password, $email)
    {
        $this->connect();
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE userEmail=:email and resetpswd=:restPswd");
        $stmt->execute(array('email' => $email, 'restPswd' => $password));
        $is_verify_password = $stmt->rowCount();
        if ($is_verify_password > 0) {
            return true;
            $this->disconnect();
        } else {
            return false;
            $this->disconnect();
        }
    }

 public function updatePassword($password, $email)
    {
        $this->connect();
        if (!empty($password) && !empty($email)) {
            $statement = $this->connection->prepare("UPDATE users SET userPassword=:Pswd WHERE userEmail = :email");
            $statement->execute([':Pswd' => $password, ':email' => $email]);
            echo "true";
            $this->disconnect();
        } else {
            echo "false";
            $this->disconnect();
        }
    }
/*START OF PLAYER INTERFACE*/
    /*START OF VIEW-LADDER*/
public function activeTournaments(){
  $this->connect();
  $currDate = new DateTime();
  $result = $currDate->format('Y-m-d');
  $query = $this->connection-> prepare("SELECT DISTINCT tournaments.tournamentId,users.userName,tournaments.tournamentName,tournaments.tournamentRegistrationDate,tournaments.tournamentStartDate,tournaments.tournamentEndDate,tournaments.tournamentPlace,tournaments.tournamentPrize from tournaments,users,tournamentmanagers
    where users.userId = tournamentmanagers.userId AND tournaments.tournamentId = tournamentmanagers.tournamentId AND tournaments.tournamentStartDate >= '$result'");
  $query->execute();
  if($query->rowCount() > 0){
  while($row = $query-> fetch(PDO::FETCH_ASSOC)){
    echo '<tr>';
          echo '<td>' . $row['userName'].'</td>';
          echo '<td>' . $row['tournamentName'].'</td>';
          echo '<td>' . $row['tournamentRegistrationDate'].'</td>';
          echo '<td>' . $row['tournamentStartDate'].'</td>';
          echo '<td>' . $row['tournamentEndDate'].'</td>';
          echo '<td>' . $row['tournamentPlace'].'</td>';
          echo '<td>' . $row['tournamentPrize'].'</td>';
          echo '<td><button type="button" value="'.$row['tournamentId'].'" class="joinTournamentBtn btn btn-success">Join</button></td>';
          echo '<td><button type="button" data-bs-toggle="modal" data-bs-target="#ViewTournamentModal" value="'.$row['tournamentId'].'" class="ladderBtn btn btn-danger">
          View-Ladder
          </button></td>';
        echo '</tr>';
      }
    }
    else{
      echo '<tr>';  
      echo '<td>'. 'No Tournaments Found'. '</td>';  
      echo '</tr>'; 
    }
  $this->disconnect();
}
public function joinTournament($playerId,$tId){
  //this function allowed the player to join a tournament in the view-ladder Tournament.
  $maxTournamentParticipants = 15;
  $this->connect();
  //query to select a user if he participated in a certain tournament
  $statement = $this->connection->prepare("SELECT * FROM participants WHERE userId='$playerId' AND tournamentId='$tId'");
  $statement->execute();
  if($statement->rowCount() > 0){
    $res = [
      'status' => 500,
      'message'=> 'You have already participated'
    ]; 
    echo json_encode($res);
    $this->disconnect();
    return false;
  }
  //query to get the count of tournament participants in a certain tournaments
  $tournament_participants = $this->connection->prepare("SELECT count(userId) from participants WHERE tournamentId = '$tId'");
  $tournament_participants->execute();
  $row = $tournament_participants-> fetch(PDO::FETCH_ASSOC);
  if($row['count(userId)'] == $maxTournamentParticipants){
    $res = [
      'status' => 500,
      'message'=> 'Tournament has reached its maximum participants'
    ]; 
    echo json_encode($res);
    $this->disconnect();
    return false;
  }
  else{
    //inserting participant into the participants table
    $Pstatement = $this->connection->prepare("INSERT INTO participants VALUES(:userId, :tournamentId)");
    $participant_parameters = [":userId"=>$playerId,":tournamentId"=>$tId];
    $Pstatement->execute($participant_parameters);
    //inserting the participant into the rankings table
    $getRanks = $this->connection->prepare("SELECT count(rankingNum) FROM rankings WHERE tournamentId = '$tId' AND status='Active'");
    $getRanks->execute();
    $fetchGetRanks = $getRanks -> fetch(PDO::FETCH_ASSOC);
    $getUser = $this->connection->prepare("SELECT * FROM users WHERE userId='$playerId'");
    $getUser->execute();
    $currentUser = $getUser-> fetch(PDO::FETCH_ASSOC);
    $ranking_statement = $this->connection->prepare("INSERT INTO rankings VALUES(:rankingNum,:tournamentId,:playerId,:playerName,:status)");
    $ranking_parameters = [":rankingNum"=>(int)$fetchGetRanks['count(rankingNum)']+1,":tournamentId"=>$tId,":playerId"=>$playerId,":playerName"=>$currentUser['userName'],":status"=>"Active"];
    $ranking_statement->execute($ranking_parameters);
    $res = [
        'status' => 200,
        'message' => 'You have been added'
      ];
      echo json_encode($res);
      $this->disconnect();
      return false;
    }
  }
public function getTournamentsByDate($startDate,$endDate){
  $this->connect();
  $result = $this->connection->prepare("SELECT * FROM tournaments WHERE tournamentStartDate BETWEEN '$startDate' AND '$endDate'");
  $result->execute();
  $this->buildTournamentTable($result,$result->rowCount());
}
public function getParticipant($participantId,$tId){
  //function that gets the selected player information in the pyramid 
    $this->connect();
    $query = $this->connection->prepare("SELECT * FROM users,rankings WHERE users.userId = '$participantId' AND rankings.tournamentId='$tId' AND rankings.playerId='$participantId'");
    $query->execute();
    $user = $query-> fetch(PDO::FETCH_ASSOC);
    $res = [
      'status' => 200,
      'message' => 'Participant Fetch Successfully by id'
      ,'data' => $user
    ];
    echo json_encode($res);
    $this->disconnect();
    return false;
  }
public function inviteValidation($AplayerId,$BplayerId,$rivalStatus,$rivalRank,$tId){
  //function that checks if the player that is being invited can be invited for a match
  $this->connect();
  $statement = $this->connection->prepare("SELECT * FROM rankings WHERE tournamentId='$tId' AND playerId='$AplayerId'");
  $statement->execute(); //getting all the information about the first player who is inviting.
  $firstPlayer = $statement-> fetch(PDO::FETCH_ASSOC);
  if($rivalStatus == 'InActive'){
    $res = [
      'status' => 422,
      'message' => 'This player is no longer a participant in this tournament'
    ];
    echo json_encode($res);
    $this->disconnect();
    return false;
  }
  if($firstPlayer['rankingNum'] == $rivalRank){
    $res = [
      'status' => 422,
      'message' => 'You can not invite yourself'
    ];
    echo json_encode($res);
    $this->disconnect();
    return false;
  }
  else if($firstPlayer['rankingNum'] < $rivalRank){
    $res = [
      'status' => 422,
      'message' => 'You can only invite players who are higher rank than you '
    ];
    echo json_encode($res);
    $this->disconnect();
    return false;
  }
  else{
    $res = [
      'status' => 200,
      'message' => 'Player can be invited'
    ];
    echo json_encode($res);
    $this->disconnect();
    return false;
  }
}
public function insertInvitation($AplayerId,$BplayerId,$tId,$matchLocation,$gameDate){
  $this->connect();
  $result = Date('y-m-d', strtotime('+2 days'));//expiration invite date
  $maxGameDate = Date('y-m-d', strtotime('+9 days'));
  // if($result > $gameDate){
    // if($gameDate <= $maxGameDate){
    // $res = [
    //   'status' => 422,
    //   'message' => 'Your invitation Date does not fit the requirments. it should be greater than 2 days from the current date and less than 9 days'
    // ];
    // echo json_encode($res);
    // $this->disconnect();
    // return false;
  // }
  // }
  $statement = $this->connection->prepare("INSERT INTO invitations VALUES(:tournamentId,
      :invitationId,
      :invitationDate,
      :sender,
      :receiver,
      :invitationsStatus,
      :expirationDate
      )");
  //creating an associative array that will hold the values 
  $invitation_parameters = [":tournamentId"=> $tId,
      ":invitationId"=> NULL,
      ":invitationDate"=>$gameDate,
      ":sender"=>$AplayerId,
      ":receiver"=>$BplayerId,
      ":invitationsStatus"=>"Waiting",
      ":expirationDate"=>$result];
  $statement->execute($invitation_parameters);
  $senderName=$this->connection->prepare("SELECT * FROM users where userId='$AplayerId'");
  $senderName->execute();
  $firstPlayer = $senderName-> fetch(PDO::FETCH_ASSOC);
  $stmt = $this->connection->prepare("SELECT * FROM users WHERE userId='$BplayerId'");
  $stmt->execute();
  $secondPlayer = $stmt-> fetch(PDO::FETCH_ASSOC);
  $email=$firstPlayer['userEmail'];
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
  $headers .= "From:<notification@gmail.com>\n";
  $postedemail = $secondPlayer['userEmail'];
  $subject = "You have been invited for a match";
  $message = "";
  $message = $firstPlayer['userName'] ."Has invited you for a match". "<br>";
  $message = "Location:" .$matchLocation . "<br>" . "Please check your invitation games page For the match";
  mail($postedemail, $subject, $message, $headers);
  $res = [
      'status' => 200,
      'message' => 'Player has been invited.'
    ];
    echo json_encode($res);
    $this->disconnect();
    return false;
}
public function buildInvitationTable($invitations,$tournamentNames,$playersNames,$selectedOption,$role='Player')
    {
        //This function builds the table
                echo '<tr>';
                echo '<td>' . $tournamentNames[0]['tournamentName'] . '</td>';
                echo '<td>' . $invitations['invitationDate'] . '</td>';
                echo '<td>' . $playersNames[0]['userName'] . '</td>';
                echo '<td>' . $playersNames[1]['userName'] . '</td>';
                echo '<td>' . $invitations['expirationDate'] . '</td>';
                // if ($selectedOption == "Sent") 
                  echo '<td>' . $invitations['invitationsStatus'] . '</td>';
                if($role == 'Player')  
                    if ($selectedOption == "Received") {
                        echo '<td><button type="button" value="'.$invitations['invitationId'].'-'.$invitations['receiver'].'-'.$invitations['tournamentId'].'" class="AcceptInvBtn btn btn-success">Accept</button></td>';
                        echo '<td><button type="button" value="' . $invitations['invitationId'].'-'.$invitations['receiver'].'-'.$invitations['tournamentId'].'" class="DeclineInvBtn btn btn-danger">Decline</button></td>';
                    } else {
                      echo '<td></td>';
                      echo '<td></td>';
                    }
    }
public function fetchInvitationBy($playerId,$selectedOption){
  //this function gets the fetched invitations in the games-invitations page for player
  $this->connect();
  if($selectedOption == 'Sent'){
    $sender = $this->connection->prepare("SELECT * FROM invitations WHERE invitations.sender='$playerId' ORDER BY invitationsStatus asc");
    $sender->execute();
    $countRow = $sender->rowCount();
    if($countRow > 0){
    while($invitation = $sender->fetch(PDO::FETCH_ASSOC)){
      $tournamentId = $invitation['tournamentId'];
      $playerAId = $invitation['sender'];
      $playerBId = $invitation['receiver'];
      $tournamentName = $this->connection->prepare("SELECT tournamentName from tournaments WHERE tournamentId=' $tournamentId'");
      $pSender = $this->connection->prepare("SELECT userName from users WHERE userId='$playerAId'");
      $pReceiver = $this->connection->prepare("SELECT userName from users WHERE userId='$playerBId'");
      $tournamentName->execute();
      $pSender->execute();
      $pReceiver->execute();
      while($row = $tournamentName->fetch(PDO::FETCH_ASSOC)){
      $tournamentsNames [] = $row;
      }
      while(($senderRow = $pSender->fetch(PDO::FETCH_ASSOC)) && ($receiverRow = $pReceiver->fetch(PDO::FETCH_ASSOC))){
        $playersNames [] = $senderRow;
        $playersNames [] = $receiverRow;
      }
      $this->buildInvitationTable($invitation,$tournamentsNames,$playersNames,$selectedOption);
    }
  }
    else{
        echo '<tr>';
        echo'<td>No invitations available</td>';
        echo '</tr>';
      }
}
 else if ($selectedOption == 'Received'){
    $receiver = $this->connection->prepare("SELECT * FROM invitations WHERE invitations.receiver='$playerId' AND invitations.invitationsStatus='Waiting'");
    $receiver->execute();
    $rowCount = $receiver->rowCount();
    if($rowCount > 0){
      while($invitation = $receiver->fetch(PDO::FETCH_ASSOC)){
      $tournamentId = $invitation['tournamentId'];
      $playerAId = $invitation['sender'];
      $playerBId = $invitation['receiver'];
      $tournamentName = $this->connection->prepare("SELECT tournamentName from tournaments WHERE tournamentId=' $tournamentId'");
      $pSender = $this->connection->prepare("SELECT userName from users WHERE userId='$playerAId'");
      $pReceiver = $this->connection->prepare("SELECT userName from users WHERE userId='$playerBId'");
      $tournamentName->execute();
      $pSender->execute();
      $pReceiver->execute();
      while($row = $tournamentName->fetch(PDO::FETCH_ASSOC)){
      $tournamentsNames [] = $row;
      }
      while(($senderRow = $pSender->fetch(PDO::FETCH_ASSOC)) && ($receiverRow = $pReceiver->fetch(PDO::FETCH_ASSOC))){
        $playersNames [] = $senderRow;
        $playersNames [] = $receiverRow;
      }
      $this->buildInvitationTable($invitation,$tournamentsNames,$playersNames,$selectedOption);
       }
     }
    }
  }
  public function fetchAdminInvitationBy($adminRole,$selectedOption){
    //this function fetches invitations for Admin interface
     $this->connect();
    if($selectedOption == 'Waiting')
      $getInvitation = $this->connection->prepare("SELECT * FROM invitations WHERE invitations.invitationsStatus=:selectedOption");
    elseif($selectedOption =='Accepted')
      $getInvitation = $this->connection->prepare("SELECT * FROM invitations WHERE invitations.invitationsStatus=:selectedOption");
    else
        $getInvitation = $this->connection->prepare("SELECT * FROM invitations WHERE invitations.invitationsStatus=:selectedOption");
    $getInvitation->execute(["selectedOption"=>$selectedOption]);
    if($getInvitation->rowCount() > 0){
    while($invitation = $getInvitation->fetch(PDO::FETCH_ASSOC)){
      $tournamentId = $invitation['tournamentId'];
      $playerAId = $invitation['sender'];
      $playerBId = $invitation['receiver'];
      $tournamentName = $this->connection->prepare("SELECT tournamentName from tournaments WHERE tournamentId=:tournamentId");
      $pSender = $this->connection->prepare("SELECT userName from users WHERE userId=:userId");
      $pReceiver = $this->connection->prepare("SELECT userName from users WHERE userId=:userId");
      $tournamentName->execute(['tournamentId'=>$tournamentId]);
      $pSender->execute(['userId'=>$playerAId]);
      $pReceiver->execute(['userId'=>$playerBId]);
      while($row = $tournamentName->fetch(PDO::FETCH_ASSOC)){
      $tournamentsNames [] = $row;
      }
      while(($senderRow = $pSender->fetch(PDO::FETCH_ASSOC)) && ($receiverRow = $pReceiver->fetch(PDO::FETCH_ASSOC))){
        $playersNames [] = $senderRow;
        $playersNames [] = $receiverRow;
      }
      $this->buildInvitationTable($invitation,$tournamentsNames,$playersNames,$selectedOption,$adminRole);
    }
  }
    else{
        echo '<tr>';
        echo'<td>No invitations available</td>';
        echo '</tr>';
      }
}
public function acceptInvitation($invId,$accepterId){
  //function that updates invitation to accepted and creates a match
  $this->connect();
  //updating invitation
  $statement = $this->connection->prepare("UPDATE invitations SET invitationsStatus=:invitationsStatus WHERE  invitationId= '$invId'");
  $statement->execute(['invitationsStatus' => 'Accepted']);
  //get the invitation
  $getInv = $this->connection->prepare("SELECT * FROM invitations WHERE invitationId='$invId'");
  $getInv->execute();
  $invitation = $getInv->fetch(PDO::FETCH_ASSOC);
  //add a match 
  $createMatch = $this->connection->prepare("INSERT INTO games VALUES(:gameId,:gameDate,:tournamentId,:playerAId,:playerBId,:gameScore,:gameWinner,:gameLoser,:gameStatus)");
  $match_parameters = [":gameId"=>NULL,"gameDate"=>$invitation['invitationDate'],":tournamentId"=>$invitation['tournamentId'],":playerAId"=>$invitation['sender']
  ,":playerBId"=>$invitation['receiver'],":gameScore"=>NULL,":gameWinner"=>NULL,":gameLoser"=>NULL,":gameStatus"=>"Active"];
  $createMatch->execute($match_parameters);
  $senderIdParam = $invitation['sender'];
  //send email to the sender
  $senderEmail = $this->connection->prepare("SELECT userEmail FROM users WHERE userId='$senderIdParam'");
  $senderEmail->execute();
  $email = $senderEmail->fetch(PDO::FETCH_ASSOC);
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
  $headers .= "From:<notification@gmail.com>\n";
  $postedemail = $email['userEmail'];
  $subject = "Your rival has accepted your match invitation";
  $message = $firstPlayer['userName'] ."Has Accepted your match invitation". "<br>";
  $message = "Please check games page for the updating games list";
  mail($postedemail, $subject, $message, $headers);
  $res = [
      'status' => 200,
      'message' => 'Match has been Accepted ,opponent has been notified ,Check your games page.'
    ];
    echo json_encode($res);
    $this->disconnect();
    return false;
} 
//decline invitation.
public function declineInvitation($invId,$accepterId){
  //function that updates invitation to accepted and creates a match
  $this->connect();
  //updating invitation
  $statement = $this->connection->prepare("UPDATE invitations SET invitationsStatus=:invitationsStatus WHERE  invitationId= '$invId'");
  $statement->execute(['invitationsStatus' => 'Declined']);
  //get the invitation
  $getInv = $this->connection->prepare("SELECT * FROM invitations WHERE invitationId='$invId'");
  $getInv->execute();
  $invitation = $getInv->fetch(PDO::FETCH_ASSOC);
  //notify the sender that invite has been declined
  $senderIdParam = $invitation['sender'];
  //send email to the sender
  $senderEmail = $this->connection->prepare("SELECT userEmail FROM users WHERE userId='$senderIdParam'");
  $senderEmail->execute();
  $email = $senderEmail->fetch(PDO::FETCH_ASSOC);
  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
  $headers .= "From:<notification@gmail.com>\n";
  $postedemail = $email['userEmail'];
  $subject = "Your rival has Declined your match invitation";
  $message = $firstPlayer['userName'] ."Has Declined your match invitation". "<br>"; //fix
  $message = "Please check games page for the updating games list";
  mail($postedemail, $subject, $message, $headers);
  $res = [
      'status' => 200,
      'message' => 'Match has been Declined , opponent has been notified.'
    ];
    echo json_encode($res);
    $this->disconnect();
    return false;
} 
//filter invitations by date for admin interface
public function filterAdminInvitationsDate($fromDate,$toDate){
  $this->connect();
  $invitations = $this->connection->prepare("SELECT * FROM invitations WHERE invitationDate BETWEEN '$fromDate' AND '$toDate'");
  $invitations->execute();
  if($invitations->rowCount() > 0){
    while($invitation = $invitations->fetch(PDO::FETCH_ASSOC)){
      $tournamentId = $invitation['tournamentId'];
      $playerAId = $invitation['sender'];
      $playerBId = $invitation['receiver'];
      $tournamentName = $this->connection->prepare("SELECT tournamentName from tournaments WHERE tournamentId=:tournamentId");
      $pSender = $this->connection->prepare("SELECT userName from users WHERE userId=:userId");
      $pReceiver = $this->connection->prepare("SELECT userName from users WHERE userId=:userId");
      $tournamentName->execute(['tournamentId'=>$tournamentId]);
      $pSender->execute(['userId'=>$playerAId]);
      $pReceiver->execute(['userId'=>$playerBId]);
      while($row = $tournamentName->fetch(PDO::FETCH_ASSOC)){
      $tournamentsNames [] = $row;
      }
      while(($senderRow = $pSender->fetch(PDO::FETCH_ASSOC)) && ($receiverRow = $pReceiver->fetch(PDO::FETCH_ASSOC))){
        $playersNames [] = $senderRow;
        $playersNames [] = $receiverRow;
      }
      $this->buildInvitationTable($invitation,$tournamentsNames,$playersNames,"NONE","Admin");
    }
  }
  $this->disconnect();
return ;
}
public function filterInvitations($fromDate, $toDate,$userId){
  //Function that filters the sent invitations according to date
  $this->connect();
  $sender = $this->connection->prepare("SELECT * FROM invitations WHERE invitations.sender='$userId' AND invitationDate BETWEEN '$fromDate' AND '$toDate' ");
  $sender->execute();
  $countRow = $sender->rowCount();
  if($countRow > 0){
  while($invitation = $sender->fetch(PDO::FETCH_ASSOC)){
      $tournamentId = $invitation['tournamentId'];
      $playerAId = $invitation['sender'];
      $playerBId = $invitation['receiver'];
      $tournamentName = $this->connection->prepare("SELECT tournamentName from tournaments WHERE tournamentId=' $tournamentId'");
      $pSender = $this->connection->prepare("SELECT userName from users WHERE userId='$playerAId'");
      $pReceiver = $this->connection->prepare("SELECT userName from users WHERE userId='$playerBId'");
      $tournamentName->execute();
      $pSender->execute();
      $pReceiver->execute();
      while($row = $tournamentName->fetch(PDO::FETCH_ASSOC)){
      $tournamentsNames [] = $row;
      }
      while(($senderRow = $pSender->fetch(PDO::FETCH_ASSOC)) && ($receiverRow = $pReceiver->fetch(PDO::FETCH_ASSOC))){
        $playersNames [] = $senderRow;
        $playersNames [] = $receiverRow;
      }
      $selectedOption='Sent';
      $this->buildInvitationTable($invitation,$tournamentsNames,$playersNames,$selectedOption);
    }
}
$this->disconnect();
return ;
}
public function setGameWinner($match,$points,$playerId){
   //if the loser was already known and the winner entered his points
    $statement = $this->connection->prepare("UPDATE games SET gameScore=:gameScore , gameWinner=:gameWinner , gameStatus=:gameStatus WHERE gameId =:gameId");
    $statement->execute(['gameScore'=>$points,'gameWinner'=>$playerId,'gameStatus'=>"Finished",'gameId'=>$match['gameId']]);//update the game
     //updating the ranks if the id of the winner is the same id of who made the invitation, else ranks will stay the same.
     //ranks only be switched if the inviter won
    if($playerId == $match['playerAId']){
      //get the ranks of both of the players.
      $playerA = $this->connection->prepare("SELECT rankingNum from rankings WHERE tournamentId=:tournamentId AND playerId=:playerId");
      $playerA->execute(['tournamentId'=>$match['tournamentId'],'playerId'=>$match['playerAId']]);
      $rankA = $playerA->fetch(PDO::FETCH_ASSOC);
      $playerB = $this->connection->prepare("SELECT rankingNum from rankings WHERE tournamentId=:tournamentId AND playerId=:playerId");
      $playerB->execute(['tournamentId'=>$match['tournamentId'],'playerId'=>$match['playerBId']]);
      $rankB = $playerA->fetch(PDO::FETCH_ASSOC);
      //update rank for the first player
      $updateRanks = $this->connection->prepare("UPDATE rankings SET rankingNum=:rankingNum WHERE tournamentId=:tournamentId AND playerId=:playerId");
      $updateRanks->execute(['rankingNum'=>$rankB['rankingNum'],'tournamentId'=>$match['tournamentId'],'playerId'=>$match['playerAId']]);
      //update rank for the second player
      $updateRanks->execute(['rankingNum'=>$rankA['rankingNum'],'tournamentId'=>$match['tournamentId'],'playerId'=>$match['playerBId']]);
    }
    if($playerId == $match['playerBId']){
      $res = ['status' => 200,'message' => 'Congratulations on winning your match'];
    }
    else{
      $res = ['status' => 200,'message' => 'Result has been submitted, thanks for participating'];
    }
    echo json_encode($res);
    $this->disconnect();
    return false;
}
public function suspendMatch($match){
  $statement = $this->connection->prepare("UPDATE games SET gameStatus=:gameStatus WHERE gameId =:gameId");
  $statement->execute(['gameStatus'=>"Suspended",'gameId'=>$match['gameId']]);//update the game to suspended
  $res = ['status' => 422,'message' => 'Match has been suspended, Tournament manager will handle it.'];
  echo json_encode($res);
  $this->disconnect();
  return false;
}
public function gameResult($gameId,$playerId,$points=0){
  //function that decides who won in a certain match.
  $this->connect();
  $getMatch = $this->connection->prepare("SELECT * FROM games WHERE gameId='$gameId'");
  $getMatch->execute();
  $match = $getMatch->fetch(PDO::FETCH_ASSOC);
  if($playerId == $match['playerAId']){  //first submitted result.
    if($points!=0){
        if(empty($match['gameScore'])){
          $statement = $this->connection->prepare("UPDATE games SET gameScore=:gameScore WHERE gameId = '$gameId'");
          $statement->execute(['gameScore'=>$points]);
          $res = ['status' => 200,'message' => 'Your points have been submitted waiting for the second player to submit.'];
          echo json_encode($res);
          $this->disconnect();
          return false;
        }
        else{//if the player inserts more than 1 result
          $res = ['status' => 422,'message' => 'You can only submit your points once per match'];
          echo json_encode($res);
          $this->disconnect();
          return false;
        }
      }//end of second if
      else{//hoster of the match lost.
        $statement = $this->connection->prepare("UPDATE games SET gameLoser=:gameLoser WHERE gameId =:gameId");
        $statement->execute(['gameLoser'=>$playerId,'gameId'=>$match['gameId']]);//update the game loser.
      }
  }//end of first if
  //if the person who got invited tries to submit first
  else if($playerId != $match['playerAId'] && (empty($match['gameScore']) && empty($match['gameLoser']))){
    $res = ['status' => 422,'message' => 'please wait for the hoster of this match to submit first.'];
    echo json_encode($res);
    $this->disconnect();
    return false;
  }
  else{
    //if he pressed on won button
    if($points!=0){
      if(empty($match['gameScore'])){
        $this->setGameWinner($match,$points,$playerId);
      }
      else{//if both of the players click on win
        $this->suspendMatch($match);
      }
    }
    else{
      if(empty($match['gameLoser'])){//if the second player lost
        $statement = $this->connection->prepare("UPDATE games SET gameLoser=:gameLoser WHERE gameId =:gameId");
        $statement->execute(['gameLoser'=>$playerId,'gameId'=>$match['gameId']]);
        $this->setGameWinner($match,$match['gameScore'],$match['playerAId']);
      }
      else{
        //if both of the players click on win
        $this->suspendMatch($match);
      }
    }
  }
}
  /*END OF VIEW-LADDER*/
/*END OF PLAYER INTERFACE*/

//function after logging in will be executed
public function endTournament($userId){
  $this->connect();
  //get all tournaments related to the tournamentmanager
  $getTournaments = $this->connection->prepare("SELECT * FROM tournaments WHERE tournamentId IN (SELECT tournamentId from tournamentmanagers WHERE 
  userId=:userId) AND tournamentEndDate <= CURRENT_DATE");
  $getTournaments->execute(['userId'=>$userId]);
  if($getTournaments->rowCount() > 0){
   while($row = $getTournaments-> fetch(PDO::FETCH_ASSOC)){
    $getRank1 = $this->connection->prepare("SELECT playerName from rankings WHERE rankingNum=:rankingNum AND tournamentId=:tournamentId");
    $getRank1->execute(['rankingNum'=>'1','tournamentId'=>$row['tournamentId']]);
    $rank1 = $getRank1-> fetch(PDO::FETCH_ASSOC);
    if($getRank1->rowCount() > 0){
    $updateTournaments = $this->connection->prepare("UPDATE tournaments SET tournamentWinner=:tournamentWinner , tournamentStatus=:tournamentStatus WHERE
    tournamentId=:tournamentId AND tournamentEndDate <= CURRENT_DATE");
    $updateTournaments->execute(['tournamentWinner'=>$rank1['playerName'],'tournamentStatus'=>'In-Active','tournamentId'=>$row['tournamentId']]);
    }
   }
  }
  $this->disconnect();
}
//check if invitations are expired.
public function expireInvitation($userId){
  $this->connect();
  $updateInvitations = $this->connection->prepare("UPDATE invitations SET invitationsStatus=:invitationsStatus WHERE sender=:userId AND expirationDate <= CURRENT_TIMESTAMP AND invitationsStatus=:currentStatus");
  $updateInvitations->execute(['invitationsStatus'=>'Expired','userId'=>$userId,'currentStatus'=>"Waiting"]);
  $this->disconnect();
}
//check expired games.
public function expiredGames($userId,$userEmail){
  $this->connect();
  $games = $this->connection->prepare("SELECT * FROM games WHERE playerAId=:userId");
  $games->execute(['userId'=>$userId]);
  while($game = $games-> fetch(PDO::FETCH_ASSOC)){
  if($game['gameDate'] > date("Y-m-d") && $game['gameStatus'] == 'Active'){
    $email=$firstPlayer['userEmail'];
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From:<notification@gmail.com>\n";
    $postedemail = $userEmail;
    $subject = "You have a finished match with no submitted results";
    $message = "";
    $message = "Please check your games matches.";
    mail($postedemail, $subject, $message, $headers);
  }
}
}
public function pdfUsers(){
  $pdf = new FPDF('p','mm','a4');
  $pdf->SetFont('arial','b','14');
   $pdf->Addpage();
  $pdf->cell('40','10','test','1','0','C');
  $pdf->cell('40','10','test','1','0','C');
  $pdf->cell('40','10','test','1','0','C');
  $pdf->cell('40','10','test','1','0','C');
  $pdf->Output();
}
}
?>