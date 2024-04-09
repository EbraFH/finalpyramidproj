 <?php
include('../php/dbClass.php');
include('authentication.php');
include('includes/header.php');
?>
<!-- Add Participant Modal -->
<div class="modal fade" id="participantModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <?php 
                    if($_SESSION['CurrentUser']['userRole'] == "Tournament Manager"):?>
        <h5 class="modal-title" id="exampleModalLabel">Add Participant</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         <?php  endif; ?> 
      </div>
      <?php 
        if($_SESSION['CurrentUser']['userRole'] == "Tournament Manager"):?>
        <form id="saveParticipant">
          <?php  endif; ?>   
          <?php 
        if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
        <form id="AdminsaveParticipants">
            <?php  endif; ?>   
            <div class="modal-body">
                <div id="errorMessage"class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">Player Id</label>
                    <input Id="tName" type="text" pattern="[0-9]+" title="Id from 1 - 9: 012345678" required minlength="9" maxlength="9" name="participantId" class="form-control"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php 
                 if($_SESSION['CurrentUser']['userRole'] == "Tournament Manager"):?>
                    <button type="submit" id ="submitParticipant" name ="saveParticipants"class="btn btn-primary">Add Participant</button>
                 <?php  endif; ?> 
                 <?php 
                 if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                    <button type="submit" id ="AdminsaveParticipants" name ="AdminsaveParticipants"class="btn btn-primary">Add Participant</button>
                 <?php  endif; ?> 
                 
            </div>
        </form>
    </div>
  </div>
</div>
<!-- ADMIN PARTICIPANTS MODAL -->
<div class="modal fade" id="AdminparticipantModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="AdminParticipant">
            <div class="modal-body">
                <div id="errorMessage"class="alert alert-warning d-none"></div>
                <table id="ParticipantsModal" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                    <th>Rank Number</th>
                                    <th>player ID</th>
                                    <th>player Name</th>
                                    <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="tbodyModal">
                        </tbody>
                    </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- =================================== -->
<div class="container-fluid px-4">
    <h4 class="mt-4">Tournament participants</h4>
    <!-- <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Tournament participants</li>
    </ol> -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <?php 
                    if($_SESSION['CurrentUser']['userRole'] == "Tournament Manager"):?>
                <div id="filters">
                    <span>My tournaments &nbsp;</span>
                    <select class="form-control-sm" name=fetchval" id="tournaments">
                    <!-- Add a function to show tournaments for the certain tounament manager -->
                        <option value="" disabled="" selected="">Tournaments</option>
                        <!-- <option value="Finished Tournaments">Finished Tournaments</option> -->
                        <?php
                                $db = new dbClass();
                                // if($_SESSION['CurrentUser']['userRole'] == "Admin")
                                //     $db = $db->getAllTournaments();
                                if($_SESSION['CurrentUser']['userRole'] == "Tournament Manager")
                                    $db = $db->getMyTournamentsList($_SESSION['CurrentUser']['userId']);
                        ?>
                    </select>
                </div>
                <?php  endif; ?>
                    <table id="ParticipantsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                 <?php 
                                if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                                    <th>Manager</th>
                                    <th>Tournament</th>
                                <?php  endif; ?>
                                <?php 
                                if($_SESSION['CurrentUser']['userRole'] == "Tournament Manager"):?>
                                    <th>Rank Number</th>
                                    <th>player ID</th>
                                    <th>player Name</th>
                                    <th>Status</th>
                                <?php  endif; ?> 
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <?php
                                // if($_SESSION['CurrentUser']['userRole'] == "Admin")
                                //     $db = $db->getAllTournaments();
                                // elseif($_SESSION['CurrentUser']['userRole'] == "Tournament Manager")
                                //     $db = $db->getMyTournaments($_SESSION['CurrentUser']['userId']);
                                if($_SESSION['CurrentUser']['userRole'] == "Admin"){
                                    $db = new dbClass();
                                    $db -> getAllTournamentsAndManagers();
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

<?php 
if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
<script>
    $(document).ready(function () {
    $("#ParticipantsTable").DataTable();
})
</script>
<?php  endif; ?>