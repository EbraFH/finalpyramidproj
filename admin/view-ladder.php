<?php
include('../php/dbClass.php');
include('authentication.php');
include('includes/header.php');
?>
<link href="./css/pyramid.css" rel="stylesheet" />
<!-- MODAL FOR PARTICIPANTS INFO  -->
<div class="modal fade" id="ladderParticipantModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="ladderParticipant">
            <div class="modal-body">
                <div id="errorMessage"class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">ID</label>
                    <input readonly Id="Player_id" type="text" name="Id" class="form-control"/>
                    <label for="">Name</label>
                    <input readonly Id="Player_name" type="text" name="name" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Phone</label>
                    <input readonly type="text" name="Phone" id="Player_phone"class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Address</label>
                    <input readonly type="text" name="Address" class="form-control" id="Player_address"/>
                </div>
                <div class="mb-3">
                    <label for="">Email</label>
                    <input readonly type="email" name="Email" class="form-control" id="Player_email"/>
                </div>
                <div class="mb-3">
                    <label for="">Player Rank</label>
                    <input readonly type="text" name="status" class="form-control" id="Player_rank"/>
                </div>
                <div class="mb-3">
                    <label for="">Player Status</label>
                    <input readonly type="text" name="status" class="form-control" id="Player_status"/>
                </div>
                <div class="mb-3">
                    <input style="display:none;"readonly type="text" name="tId" id="Tournament_id"class="form-control"/>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <?php 
            if($_SESSION['CurrentUser']['userRole'] == "Player"):?>
            <button  class="inviteBtn" type="button" class="btn btn-primary btn-success" data-bs-toggle="modal" data-bs-target="#InviteParticipantModal">
                           Invite for a match
                    </button>
                     <?php endif; ?>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- MODAL FOR INVITING A PLAYER -->
<div   class="modal fade" id="InviteParticipantModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="InviteParticipant">
            <div class="modal-body">
                <div id="errorMessage"class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">Match Date:</label>
                    <input type="datetime-local" id="meeting-time"
                    name="meeting-time" class="InviteDate" value=<?php new DateTime() ?>
                    min="2022-03-07T00:00" max="2030-06-14T00:00">
                    <label for="">Court Location</label>
                    <input  type="text" name="Location" class="form-control" id="matchLocation"/>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="submit" id ="inviteParticipant" name ="inviteP"class="btn btn-primary">Invite</button>
                </div>
            </div>
        </form>
    </div>
  </div>
</div>
<div class="container-fluid px-4">
    <h4 class="mt-4">Tournaments Ladder</h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="container">
                 <div class="row">
                    <div class="col-lg-3">
                    <div id="filters">
                    <select class="form-control-sm" name=fetchval" id="fetchvalLadder">
                        <option value="" disabled="" selected="">Select Filter</option>
                        <option value="Finished Tournaments">Finished Tournaments</option>
                        <option value="Active Tournaments">Active Tournaments</option>
                    </select>
                </div>
                    <table id="LadderTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                 <?php 
                                 if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                                <th>Tournament Manager</th>
                                <?php endif; ?>
                                <th>Tournament Name</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <?php
                                $db = new dbClass();
                                    $db = $db->buildRunningTournamentLadderList($_SESSION['CurrentUser']['userId'],$_SESSION['CurrentUser']['userRole']);
                            ?>
                        </tbody>
                    </table>
                    </div>
                        <div id="tournamentLadder" class="d-none pycontainer col-lg">
                        </div>
                 </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
<script>
    $(document).ready(function () {
    $("#LadderTable").DataTable();
})
</script>