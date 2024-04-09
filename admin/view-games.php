<?php
include('../php/dbClass.php');
include('authentication.php');
include('includes/header.php');
?>
<!-- Modal for winner to enter his points -->
<div class="modal fade" id="WinnerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enter your points:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="WinnerForm">
            <div class="modal-body">
                <div id="errorMessage"class="alert alert-warning d-none"></div>
                    <div class="mb-3">
                        <label for="">Points</label>
                        <input Id="points" type="number" name="Points" class="form-control"/>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id ="submitPoints" class="btn btn-success">Submit</button>
            </div>
        </form>
    </div>
  </div>
</div>
<div class="container-fluid px-4">
    <h4 class="mt-4">Tournaments matches</h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="container">
                    <div class="row">
                         <div class="text-box">
                            <div class="col-md-3">
                                <input type="text" name="from_date" id="from_gdate" class="form-control" placeholder="From Date" />
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="to_date" id="to_gdate" class="form-control" placeholder="To Date" />
                            </div>
                            <div class="col-md-5">
                                <input type="button" name="filter" id="game_filter" value="Filter" class="btn btn-info" />
                            </div>
                         </div>
                        <div class="col-lg-3">
                            <div id="filters">
                                 <select class="form-control-sm" name=fetchval" id="fetchvalGame">
                                    <option value="" disabled="" selected="">Select Filter</option>
                                    <option value="Finished Tournaments">Finished Tournaments</option>
                                    <option value="Active Tournaments">Active Tournaments</option>
                                    <option value="Suspended">Suspended Matches</option>
                                 </select>
                            </div>
                            
                            <table id="LadderTable" class="table table-bordered table-striped">
                                <tbody class="ladderBody">
                                <?php
                                   $db = new dbClass();
                                   $db = $db->buildRunningTournamentLadderList($_SESSION['CurrentUser']['userId'],$_SESSION['CurrentUser']['userRole']);
                                ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div id="tournamentMatches"class="gamescontainer col-lg">
                            <table id="gamesTable" class="table table-bordered table-striped">
                                <thead>
                                 <tr>
                                    <th>Game Id</th>
                                    <th>Game Date</th>
                                    <th>Players</th>
                                     <?php if($_SESSION['CurrentUser']['userRole'] == "Player"):?>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    <?php  endif; ?> 
                                    <?php if(!($_SESSION['CurrentUser']['userRole'] == "Player")):?>  
                                        <th>Winner</th>
                                    <?php  endif; ?>  
                                 </tr>
                                </thead>
                                <tbody class="tbody">
                                </tbody>
                            </table>
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
    $("#gamesTable").DataTable();
})
</script>