<?php
include('../php/dbClass.php');
include('authentication.php');
include('includes/header.php');
?>
<link href="./css/pyramid.css" rel="stylesheet" />
<div class="modal fade" id="tmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="tm">
            <div class="modal-body">
                <div id="errorMessage"class="alert alert-warning d-none"></div>
                <table id="ParticipantsModal" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                    <th>Manager Name</th>
                                    <th>Manager Id</th>
                                    <th>Phone</th>
                                    <th>total tournaments</th>
                            </tr>
                        </thead>
                        <tbody class="tbodytmModal">
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
<!-- users PDF MODAL -->
<!-- Add user Modal -->
<div class="modal fade" id="userspdfModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Users report:</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="userspdf">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="">Amount of users</label>
                    <input type="range" name="weight" id="range_weight" value="5" min="1" max="100" oninput="range_weight_disp.value = range_weight.value">
                    <output  id="range_weight_disp"></output>
                </div>
                <div class="mb-3">
                    <label for="">Status (Active/In-Active)</label>
                    <select class = "form-select" name="Status">
                        <option value="Active">Active</option>
                        <option value="In-Active">In-Active</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="downloadpdf" class="btn btn-primary">Download</button>
            </div>
        </form>
    </div>
  </div>
</div>
  <div class="container-fluid px-4">
                    <?php 
                                   if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                        <h1 class="mt-4">Admin Panel</h1>
                    <?php endif; ?>
                    <?php 
                                   if($_SESSION['CurrentUser']['userRole'] == "Tournament Manager"):?>
                        <h1 class="mt-4">Tournament Manager Panel</h1>
                    <?php endif; ?>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                         <div class="row">
                            <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                     <?php 
                                           if($_SESSION['CurrentUser']['userRole'] != "Player"):?>
                                    <div class="card-body card-style">Upcoming Tournaments:
                                         <?php endif; ?>
                                    <?php 
                                           if($_SESSION['CurrentUser']['userRole'] == "Player"):?>    
                                              <div class="card-body card-style">Upcoming Tournaments not participated in:
                                         <?php endif; ?>
                                        <?php
                                         $db = new dbClass();
                                            if($_SESSION['CurrentUser']['userRole'] == "Admin")
                                                $db->getUpcomingTournaments();
                                            elseif($_SESSION['CurrentUser']['userRole']=='Tournament Manager'){
                                                $db->getMyUpcomingTournaments($_SESSION['CurrentUser']['userId']);
                                            }
                                            else{
                                                 $db->getUpcomingTournamentsNotParticipatedIn($_SESSION['CurrentUser']['userId']);
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                             <?php 
                                if($_SESSION['CurrentUser']['userRole'] == "Player"):?>
                                 <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body card-style">My Upcoming games:
                                        <?php
                                         $db = new dbClass();
                                         $db->getMyupcomingGames($_SESSION['CurrentUser']['userId']);
                                        ?>
                                    </div>
                                </div>
                                </div>
                              <?php endif; ?>
                            <?php 
                                   if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body card-style">Total Users:
                                        <?php
                                            $db = new dbClass();
                                            $db = $db->getTotalUsersCount();
                                        ?>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="view-register.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($_SESSION['CurrentUser']['userRole'] != "Player"):?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body card-style">Total Active TournamentManagers:
                                        <?php
                                            $db = new dbClass();
                                            $db = $db->getTotalTournamentManagerCount();
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                      <?php 
                                           if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" id="viewTm" data-bs-toggle="modal" data-bs-target="#tmModal">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                           </div>
                                           </div>
                                        <?php endif; ?>
                                        <?php 
                                   if($_SESSION['CurrentUser']['userRole'] == "Tournament Manager"):?>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" id="viewTm" data-bs-toggle="modal" data-bs-target="#tmModal">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                     </div>
                            </div>
                                        <?php endif; ?>
                             <?php 
                                           if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body card-style">Total Tournaments:
                                        <?php
                                            $db = new dbClass();
                                            $db = $db->getTotalActiveTournaments();
                                        ?>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="view-tournaments.">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                             
                             <?php 
                                           if($_SESSION['CurrentUser']['userRole'] == "Tournament Manager"):?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body card-style">My Total Tournaments:
                                        <?php
                                            $db = new dbClass();
                                            $db = $db->getMyTotalActiveTournaments($_SESSION['CurrentUser']['userId']);
                                        ?>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="view-tournaments.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        
                        </div>
                    <?php 
                        if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                    <button type="button" class="tpdfBtn btn btn-success">Tournaments Pdf</button>
                    <button type="button" class="tmpdfBtn btn btn-success">Tournaments managers pdf</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#userspdfModal" class="userspdfBtn btn btn-success">users Pdf</button>
                    <?php endif; ?>
</div>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<style>
    /* .chartMenu {
      width: 100vw;
      height: 40px;
      color: rgba(255, 26, 104, 1);
    } */
    .chartMenu p {
        padding: 10px;
        font-size: 20px;
    }
    .chartCard {
        height: 81vh;
        background: rgba(255, 26, 104, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .chartBox {
        padding: 20px;
        border-radius: 20px;
        border: solid 3px rgba(255, 26, 104, 1);
        background: white;
    }
</style>
<div class="chartMenu">
    <p>Tournaments Chart</p>
</div>
<div class="chartCard">
    <div id="myfirstchart" style="height: 250px;"></div>
    <div class="text-box">
        <div class="col-md-3">
            <input type="text" name="from_date" id="from_cdate" class="form-control" placeholder="From Date" />
        </div>
        <div class="col-md-3">
            <input type="text" name="to_date" id="to_cdate" class="form-control" placeholder="To Date" />
        </div>
        <div class="col-md-5">
            <input type="button" name="filter" id="chart_filter" value="Filter" class="btn btn-info" />
        </div>
    </div>
</div>
<?php
include('includes/scripts.php');
?>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="js/modal.js"></script>
<script>

    function loadChart(from_date,to_date){
        $.ajax({
            url:"modal.php",
            type:'post',
            data:{
                'loadChart':'true',
                'fromDate':from_date,
                'toDate':to_date,
            },
            success: function (data) {
                data=JSON.parse(data);
                $("#myfirstchart").empty();
                new Morris.Line({
                    // ID of the element in which to draw the chart.
                    element: 'myfirstchart',
                    // Chart data records -- each entry in this array corresponds to a point on
                    // the chart.
                    data: data,
                    // The name of the data record attribute that contains x-values.
                    xkey: 'tournamentRegistrationDate',
                    // A list of names of data record attributes that contain y-values.
                    ykeys: ['tournaments'],
                    // Labels for the ykeys -- will be displayed when you hover over the
                    // chart.
                    labels: ['tournaments'],
                    xLabelAngle: 60
                });
            }
        });//ajax
    }
    loadChart();
    // $('#datechangechart').on('change', function() {
    //   // alert( this.value );
    //   var range = this.value;
    //   $("#myfirstchart").empty();
    //   loadChart(range);
    // });
    $(document).ready(function () {
    //function that gets all invitations according to a certain date
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd'
    });
    $(function () {
        $("#from_cdate").datepicker();
        $("#to_cdate").datepicker();
    });
    $('#chart_filter').click(function () {
        var from_date = $('#from_cdate').val();
        var to_date = $('#to_cdate').val();
        if (from_date != '' && to_date != '') {
            if (from_date < to_date) {
                loadChart(from_date,to_date);
            }
            else {
                alert("Starting Date cannot be greated than Ending Date");
            }
        }
        else {
            alert("Please Select Date");
        }
    });
});

</script>
