<?php
include('../php/dbClass.php');
include('authentication.php');
include('includes/header.php');
?>
<div class="container-fluid px-4">
    <h4 class="mt-4">Game Invitations</h4>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="container">
                    <div class="row">
                         <div class="text-box">
                            <div class="col-md-3">
                                <input type="text" name="from_date" id="from_idate" class="form-control" placeholder="From Date" />
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="to_date" id="to_idate" class="form-control" placeholder="To Date" />
                            </div>
                            <div class="col-md-5">
                                <input type="button" name="filter" id="invitation_filter" value="Filter" class="btn btn-info" />
                            </div>
                         </div>
                        <div id="Invitations"class="gamescontainer col-lg">
                            <div class="col-lg-3">
                            <div id="filters">
                                 <?php if($_SESSION['CurrentUser']['userRole'] == "Player"):?>
                                <select class="form-control-sm" name=fetchval" id="fetchvalPlayerInvitation">    
                                    <option value="" disabled="" selected="">Select Filter</option>
                                    <option value="Sent">Sent</option>
                                    <option value="Received">received</option>
                                <?php  endif; ?>
                                <?php if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                                <select class="form-control-sm" name=fetchval" id="fetchvalAdminInvitation">       
                                    <option value="" disabled="" selected="">Select Filter</option>
                                    <option value="Waiting">Waiting</option>
                                    <option value="Accepted">Accepted</option>
                                    <option value="Expired">Expired</option>
                                <?php  endif; ?>
                                 </select>
                            </div>
                        </div>
                            <table id="gamesTable" class="table table-bordered table-striped">
                                <thead>
                                 <tr>
                                    <th>Tournament Name</th>
                                    <th>InvitationDate</th>
                                    <th>Sender</th>
                                    <th>Receiver</th>
                                    <th>Expiration Date</th>
                                    <th class="sender">Status</th>
                                    <?php if($_SESSION['CurrentUser']['userRole'] == "Player"):?>
                                    <th class="receiver">Accept</th>
                                    <th class="receiver">Decline</th>
                                    <?php  endif; ?>
                                    <?php if($_SESSION['CurrentUser']['userRole'] == "Admin"):?>
                                        <!-- <th></th>
                                        <th></th>
                                        <th></th> -->
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
