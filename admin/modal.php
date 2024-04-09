<?php
require_once("../php/dbClass.php");
require_once("../php/userClass.php");
require_once("../php/tournamentClass.php");
$db = new dbClass();
session_start();
//view tournament managers in dash board for admin 
if(isset($_POST['viewTm'])){
    $db->viewTmanagers();
}
if(isset($_POST['update_profile'])){
    $User = new userClass($_POST['Id'],$_POST['name'],$_POST['Password'],$_POST['Phone'],$_POST['BirthDay'],$_POST['Address'],$_POST['Email'],$_POST['Role'],"Active");
    $db->updateUser($User);
}
//if the form is set
if(isset($_POST['save_user'])){
    $User = new userClass($_POST['Id'],$_POST['name'],$_POST['Password'],$_POST['Phone'],$_POST['BirthDay'],$_POST['Address'],$_POST['Email'],$_POST['Role'],$_POST['Status']);
    $db->AdminModalUser($User);
}
//update user profile
if(isset($_GET['Currentuser_id'])){
    $db->getCurrentUser($_SESSION['CurrentUser']['userId']);
}
if(isset($_GET['user_id'])){
    $db->getUser($_GET['user_id']);
}
if(isset($_POST['update_user'])){
    $User = new userClass($_POST['Id'],$_POST['name'],$_POST['Password'],$_POST['Phone'],$_POST['BirthDay'],$_POST['Address'],$_POST['Email'],$_POST['Role'],$_POST['Status']);
    $db->updateUser($User);
}
if(isset($_POST['deActivate_user'])){
    $db->deActivateUser($_POST['user_id']);
}
if(isset($_POST['Activate_user'])){
    $db->ActivateUser($_POST['user_id']);
}
if(isset($_POST["from_date"], $_POST["to_date"]))
{
    $startingDate = $_POST["from_date"];
    $endingDate = $_POST["to_date"];
    $db->getUserByDate($startingDate,$endingDate);
}
if(isset($_POST['request']) && (!str_contains($_POST['request'],'Finished Tournaments') && !str_contains($_POST['request'],'Active Tournaments') && !str_contains($_POST['request'],'Upcoming Tournaments'))){
    $request = $_POST['request'];
    $db->fetchBy($request);
}
if (isset($_GET["argument"]) && $_GET["argument"]=='logOut'){
    session_unset();
    session_destroy();
    $link = "http://localhost/finalpyramidproj/index.php";
}
  /*START OF VIEW-TOURNAMENTS FUNCTIONS*/
if(isset($_POST['save_Tournament'])){
    $currDate = new DateTime();
    $result = $currDate->format('Y-m-d');
    $fp = fopen("login.txt","a");
    if($fp != false){
    fputs($fp,$result); 
    fclose($fp);
  } 
    $tournament = new tournament($_POST['tournamentName'],$result,$_POST['tournamentStartDate'],$_POST['tournamentEndDate'],$_POST['tournamentPlace'],$_POST['tournamentPrize']);
    $db->CreateTournament($tournament,$_SESSION['CurrentUser']['userId']);
}
if(isset($_GET['tournament_id'])){
    $db->getTournament($_GET['tournament_id']);
}
if(isset($_POST['update_tournament'])){
    $tournament = new tournament($_POST['tournamentName'],$_POST['tournamenCreationDate'],$_POST['tournamentStartDate'],$_POST['tournamentEndDate'],$_POST['tournamentPlace'],$_POST['tournamentPrize'],$_POST['tournamentWinner'],$_POST['Status'],$_POST['tournamentParticipant'],$_POST['tournamentId']);
    $db->updateTournament($tournament);
}
if(isset($_POST["from_tdate"], $_POST["to_tdate"]))
{
    $startingDate = $_POST["from_tdate"];
    $endingDate = $_POST["to_tdate"];
    if($_SESSION['CurrentUser']['userRole'] == 'Player'){
        $db->getTournamentsByDate($startingDate,$endingDate);
    }
    else{
    $db->getMyTournamentsByDate($startingDate,$endingDate,$_SESSION['CurrentUser']['userId']);
    }
}
//filtering the tournaments in view-tournamentss
if(isset($_POST['request']) && str_contains($_POST['request'],'Tournaments') && $_SESSION['CurrentUser']['userRole'] == "Admin"){
$request = $_POST['request'];
    $db->tournamentFetchBy($request);
}
if(isset($_POST['request']) && str_contains($_POST['request'],'Tournaments') && $_SESSION['CurrentUser']['userRole'] == "Tournament Manager"){
$request = $_POST['request'];
    $db->MytournamentFetchBy($request,$_SESSION['CurrentUser']['userId']);
}
  /*END OF VIEW-TOURNAMENTS FUNCTIONS*/
/*START OF PARTICIPANTS FUNCTIONS*/
if(isset($_POST['requestTournament'])&&$_SESSION['CurrentUser']['userRole'] == "Tournament Manager"){
    $db->getTournamentParticipants($_POST['requestTournament'],$_SESSION['CurrentUser']['userId']);
}
if(isset($_POST['submit_Participant'])&&$_SESSION['CurrentUser']['userRole'] == "Tournament Manager" ){
    $db->addParticipant($_POST['participantId'],$_POST['tName'],$_SESSION['CurrentUser']['userId']);
}
if(isset($_POST['submit_Participant'])&&$_SESSION['CurrentUser']['userRole'] == "Admin" ){
    $db->addParticipant($_POST['participantId'],$_POST['tName'],$_POST['tMId']);
}
if(isset($_POST['disableParticipant'])){
    $participantInformation = explode("-",$_POST['participantId']);
    $db->disableParticipant($participantInformation[0],$participantInformation[1],$_SESSION['CurrentUser']['userId'],$participantInformation[2]);
}
/*END OF PARTICIPANTS FUNCTIONS*/
if(isset($_POST['loadChart']) && $_POST['loadChart']=='true'){
    $res=$db->loadChartData(@$_POST['fromDate'],@$_POST['toDate'],$_SESSION['CurrentUser']['userRole'],$_SESSION['CurrentUser']['userId']);
}
if(isset($_POST['tournamentGames'])){
    $db = new dbClass();
    $db->getMyMatches($_POST['tournamentId'],$_SESSION['CurrentUser']['userRole'],$_SESSION['CurrentUser']['userId']);
 }
if(isset($_POST['tournamentBracket'])){
    $db = new dbClass();
    $db->getTournamentBracket($_POST['tournamentId'],$_SESSION['CurrentUser']['userId'],$_SESSION['CurrentUser']['userRole']);
 }
 if(isset($_POST['AdmingetParticipants'])){
    $db = new dbClass();
    $db->getTournamentParticipants($_POST['tournamentName'],$_POST['tournamentManagerId']);
 }
if(isset($_POST['fetchBy'])){
    $db = new dbClass();
    $db->ladderPageFetchBy($_POST['fetchBy'],$_SESSION['CurrentUser']['userRole'],$_SESSION['CurrentUser']['userId']);
 }
 if(isset($_POST["from_gdate"], $_POST["to_gdate"]))
{
    $startingDate = $_POST["from_gdate"];
    $endingDate = $_POST["to_gdate"];
    $db->getgamesByDate($startingDate,$endingDate);
}
 if(isset($_POST['gamesFetchBy'])){
    if($_POST['gamesFetchBy'] == 'Suspended')
        $db->suspendedMatches($_SESSION['CurrentUser']['userRole'],$_SESSION['CurrentUser']['userId']);
    else    
        $db->ladderPageFetchBy($_POST['gamesFetchBy'],$_SESSION['CurrentUser']['userRole'],$_SESSION['CurrentUser']['userId']);
 }

 /*START OF PLAYER INTERFACE */
 if(isset($_POST['joinTournament'])){
    $db = new dbClass();
    $db->joinTournament($_SESSION['CurrentUser']['userId'],$_POST['tournament_id']);
 }
 if(isset($_GET['tId']) && isset($_GET['pId'])){
    $db->getParticipant($_GET['pId'],$_GET['tId']);
}
if(isset($_POST['invBtn'])){
    $db->inviteValidation($_SESSION['CurrentUser']['userId'],$_POST['rivalId'],$_POST['rivalStatus'],$_POST['rivalRank'],$_POST['tId']);
}
if(isset($_POST['sendInvitation'])){
    $db->insertInvitation($_SESSION['CurrentUser']['userId'],$_POST['rivalId'],$_POST['tId'],$_POST['matchLocation'],$_POST['InvitationDate']);
}
if(isset($_POST['fetchInvitationBy'])){
    $db->fetchInvitationBy($_SESSION['CurrentUser']['userId'],$_POST['fetchInvitationBy']);
}
if(isset($_POST['fetchAdminInvitationBy'])){
    $db->fetchAdminInvitationBy($_SESSION['CurrentUser']['userRole'],$_POST['fetchAdminInvitationBy']);
}
//cancel game
if(isset($_POST['cancelBtn'])){
    $db->cancelMatch($_POST['gameIdtoCancel']);
}
//quit tournament
if(isset($_POST['quitTournament'])){
    $db->quitTournament($_POST['playerId'],$_POST['tournamentId'],$_POST['playerRank']);
}
//accept invitation 
if(isset($_POST['acceptInv'])){
    $db->acceptInvitation($_POST['invitationId'],$_POST['accepterId']);
}
//decline invitation
if(isset($_POST['declineInv'])){
    $db->declineInvitation($_POST['invitationId'],$_POST['declinerId']);
}
//filter invitaion
if(isset($_POST["from_idate"], $_POST["to_idate"]) && $_SESSION['CurrentUser']['userRole']=='Player'){
    $db->filterInvitations($_POST['from_idate'],$_POST['to_idate'],$_SESSION['CurrentUser']['userId']);
}
//filter for admin
if(isset($_POST["from_idate"], $_POST["to_idate"]) && $_SESSION['CurrentUser']['userRole']=='Admin'){
    $db->filterAdminInvitationsDate($_POST['from_idate'],$_POST['to_idate']);
}
//winner 
if(isset($_POST['winner'])){
    $db->gameResult($_POST['gameId'],$_SESSION['CurrentUser']['userId'],$_POST['points']);
}
//loser
if(isset($_POST['loser'])){
    $db->gameResult($_POST['gameId'],$_SESSION['CurrentUser']['userId'],$_POST['points']);
}
 /*END OF PLAYER INTERFACE*/
?>