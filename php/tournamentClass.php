<?php
class tournament{
    protected $tournamentId;
    protected $tournamentName;
    protected $tournamentParticipant;
    protected $tournamentRegistrationDate;
    protected $tournamentStartDate;
    protected $tournamentEndDate;
    protected $tournamentPlace;
    protected $tournamentPrize;
    protected $tournamentWinner;
    protected $tournamentStatus;
    //constructor
    public function __construct($tournamentName,$tournamentRegistrationDate,$tournamentStartDate,$tournamentEndDate,$tournamentPlace,$tournamentPrize,$tournametWinner="None",$tournamentStatus="Active",$tournamentParticipant=15,$tournamentId=Null){
        $this->tournamentId = $tournamentId;
        $this->tournamentName = $tournamentName;
        $this->tournamentParticipant = $tournamentParticipant;
        $this->tournamentRegistrationDate = $tournamentRegistrationDate;
        $this->tournamentStartDate = $tournamentStartDate;
        $this->tournamentEndDate = $tournamentEndDate;
        $this->tournamentPlace = $tournamentPlace;
        $this->tournamentPrize = $tournamentPrize;
        $this->tournamentWinner = $tournametWinner;
        $this->tournamentStatus = $tournamentStatus;
    }
    //getters and setters
    //tournamentId
    public function getTournamentId(){
        return $this->tournamentId;
    }
    //tournamentName
    public function getTournamentName(){
        return $this->tournamentName;
    }
    public function setTournamentName($TournamentName){
        $this->tournamentName = $TournamentName;
    }
    //tournamentParticipant
    public function getTournamentParticipant(){
        return $this->tournamentParticipant;
    }
    //tournamentRegistrationDate
    public function gettournamentRegistrationDate(){
        return $this->tournamentRegistrationDate;
    }
    public function setTournamentRegistrationDate($TournamentRegistrationDate){
        return $this->tournamentRegaistrationDate = $TournamentRegistrationDate;
    }
    //tournamentStartDate
    public function getTournamentStartDate(){
        return $this->tournamentStartDate;
    }
    public function setTournamentStartDate($TournamentStartDate){
        $this->tournamentStartDate = $TournamentStartDate;
    }
    //tournamentEndDate
    public function getTournamentEndDate(){
        return $this->tournamentEndDate;
    }
    public function setTournamentEndDate($TournamentEndDate){
        $this->tournamentEndDate = $TournamentEndDate;
    }
    //tournamentPlace
    public function getTournamentPlace(){
        return $this->tournamentPlace;
    }
    public function setTournamentPlace($TournamentPlace){
        $this->tournamentPlace = $TournamentPlace;
    }
    //tournamentPrize
    public function getTournamentPrize(){
        return $this->tournamentPrize;
    }
    public function setTournamentPrize($TournamentPrize){
        $this->tournamentPrize = $TournamentPrize;
    }
    //tournamentWinner
    public function getTournamentWinner(){
        return $this->tournamentWinner ;
    }
    public function setTournamentWinner($tournamentWinner){
        $this->tournamentWinner = $tournamentWinner;
    }
    //tournamentStatus
    public function getTournamentStatus(){
        return $this->tournamentStatus ;
    }
    public function setTournamentStatus($TournamentStatus){
        $this->tournamentStatus = $TournamentStatus;
    }
    //Ending tournament
    // public function endTournament(){
    //     if($this->getTournamentEndDate() >= date("Y/m/d"))
    //         return "Over";
    // }
}