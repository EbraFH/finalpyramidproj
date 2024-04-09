 <?php
include('../php/dbClass.php');
include('authentication.php');
include('includes/header.php');
?>

<link href="./css/pyramid.css" rel="stylesheet" />
<!-- Create Tournament Modal -->
<div class="modal fade" id="tournamentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Tournament</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="saveTournament">
            <div class="modal-body">
                <div id="errorMessage"class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">Tournament Name</label>
                    <input Id="tName" type="text" name="tournamentName" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Total Participants</label>
                    <input readonly Id="tParticipants" type="text" name="tournamentParticipant" value = "15"class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Starting Date:</label>
                    <input type="date" name="tournamentStartDate" class="form-control datepick"/>
                </div>
                <div class="mb-3">
                    <label for="">Ending Date:</label>
                    <input type="date" name="tournamentEndDate" class="form-control datepick"/>
                </div>
                <div class="mb-3">
                    <label for="">Place</label>
                    <input type="text" name="tournamentPlace" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Prize</label>
                    <input type="text" name="tournamentPrize" class="form-control"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id ="submitTournament"name ="saveTournament"class="btn btn-primary">Create Tournament</button>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- Edit Tournament Modal -->
<div class="modal fade" id="TournamentEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Tournament</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateTournament">
            <div class="modal-body">
                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">ID:</label>
                    <input readonly Id="tournamentId" type="text" name="tournamentId" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Tournament Name:</label>
                    <input Id="tournamentName" type="text" name="tournamentName" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Total Participants:</label>
                    <input readonly Id="tournamentParticipants" type="text" name="tournamentParticipant" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Creation Date:</label>
                    <input readonly Id="tournamentCreationDate" type="text" name="tournamenCreationDate" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Starting Date:</label>
                    <input type="date" id ="tournamentStartDate"name="tournamentStartDate" class="form-control datepick"/>
                </div>
                <div class="mb-3">
                    <label for="">Ending Date:</label>
                    <input type="date" id ="tournamentEndDate"name="tournamentEndDate" class="form-control datepick"/>
                </div>
                <div class="mb-3">
                    <label for="">Place</label>
                    <input type="text" id="tournamentPlace"name="tournamentPlace" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Prize</label>
                    <input type="text" id="tournamentPrize"name="tournamentPrize" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Winner</label>
                    <input readonly Id="tournamentWinner" type="text" name="tournamentWinner" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Status (Active/In-Active)</label>
                    <select class = "form-select" name="Status" id="tournamentStatus">
                        <option value="Active">Active</option>
                        <option value="In-Active">In-Active</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id ="submitTournament" name ="saveTournament"class="btn btn-primary">Edit Tournament</button>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- VIEW-LADDER MODAL -->
 <div class="modal fade" id="ViewTournamentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
          
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
            <div class="pycontainer">
         </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
    </div>
  </div>
</div>
<!-- END OF VIEW-LADDER MODAL -->
<div class="container-fluid px-4">
     <?php 
    if(!($_SESSION['CurrentUser']['userRole'] == "Player")):?>
    <h4 class="mt-4">Tournaments</h4>
      <?php  endif; ?>
    <?php 
    if($_SESSION['CurrentUser']['userRole'] == "Player"):?>
    <h4 class="mt-4">Active Tournaments</h4>
      <?php  endif; ?>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Tournaments</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <?php 
                         if(!($_SESSION['CurrentUser']['userRole'] == "Player")):?>
                     <div class="card-header">    
                     <h4>Create Tournaments
                     <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#tournamentModal">
                            Create Tournament
                    </button>
                    </h4>
                     </div>
                <?php  endif; ?>
                <div class="text-box">
                    <div class="col-md-3">
                         <input type="text" name="from_date" id="from_tdate" class="form-control" placeholder="From Date" />
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="to_date" id="to_tdate" class="form-control" placeholder="To Date" />
                    </div>
                    <div class="col-md-5">
                        <input type="button" name="filter" id="tournament_filter" value="Filter" class="btn btn-info" />
                    </div>
                </div>
                <?php 
                         if(!($_SESSION['CurrentUser']['userRole'] == "Player")):?>
                <div id="filters">
                    <span>Fetch results by &nbsp;</span>
                    <select class="form-control-sm" name=fetchval" id="fetchval">
                        <option value="" disabled="" selected="">Select Filter</option>
                        <option value="Finished Tournaments">Finished Tournaments</option>
                        <option value="Active Tournaments">Active Tournaments</option>
                        <option value="Upcoming Tournaments">Upcoming Tournaments</option>
                    </select>
                </div>
                <?php  endif; ?>
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <?php 
                                 if(!($_SESSION['CurrentUser']['userRole'] == "Player")):?>
                                <th>ID</th>
                                <?php  endif; ?>
                                <?php 
                                if($_SESSION['CurrentUser']['userRole'] == "Player"):?>
                                <th>Manager</th>
                                <?php  endif; ?>
                                <th>Name</th>
                                <th>Registration Date</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Place</th>
                                <th>Prize</th>
                                <?php 
                                 if(!($_SESSION['CurrentUser']['userRole'] == "Player")):?>
                                <th>Winner</th>
                                 <?php  endif; ?>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <?php
                                $db = new dbClass();
                                if($_SESSION['CurrentUser']['userRole'] == "Admin")
                                    $db = $db->getAllTournaments();
                                elseif($_SESSION['CurrentUser']['userRole'] == "Tournament Manager")
                                    $db = $db->getMyTournaments($_SESSION['CurrentUser']['userId']);
                                else{
                                    $db->activeTournaments();
                                }
                            ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
