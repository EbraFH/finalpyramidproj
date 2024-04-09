 <?php
include('../php/dbClass.php');
include('authentication.php');
include('includes/header.php');
?>
<!-- Add user Modal -->
<div class="modal fade" id="userAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="saveUser">
            <div class="modal-body">
                <div id="errorMessage"class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">ID</label>
                    <input Id="id" type="number" name="Id" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Name</label>
                    <input Id="name"type="text" name="name" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Password</label>
                    <input type="password" name="Password" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Phone</label>
                    <input type="text" name="Phone" class="form-control" minlength="10" maxlength="10"/>
                </div>
                <div class="mb-3">
                    <label for="">BirthDay</label>
                    <input type="Date" name="BirthDay" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Address</label>
                    <input type="text" name="Address" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Email</label>
                    <input type="email" name="Email" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Role (Admin/Tournament Manager/Player)</label>
                    <select class = "form-select" name="Role">
                        <option value="Admin">Admin</option>
                        <option value="Player">Player</option>
                        <option value="Tournament Manager">Tournament Manager</option>
                    </select>
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
                <button type="submit" id ="submitUser"name ="saveUser"class="btn btn-primary">Save User</button>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- Edit user Modal -->
<div class="modal fade" id="userEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateUser">
            <div class="modal-body">
                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">ID</label>
                    <input readonly Id="user_id" type="text" name="Id" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Name</label>
                    <input Id="user_name" type="text" name="name" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Password</label>
                    <input type="password" name="Password" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Phone</label>
                    <input type="text" name="Phone" id="user_phone"class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">BirthDay</label>
                    <input type="Date" name="BirthDay" class="form-control" id="user_birthDay"/>
                </div>
                <div class="mb-3">
                    <label for="">Address</label>
                    <input type="text" name="Address" class="form-control" id="user_address"/>
                </div>
                <div class="mb-3">
                    <label for="">Email</label>
                    <input type="email" name="Email" class="form-control" id="user_email"/>
                </div>
                <div class="mb-3">
                    <label for="">Role (Admin/Tournament Manager/Player)</label>
                    <select class = "form-select"name="Role" id="user_role">
                        <option value="Admin">Admin</option>
                        <option value="Player">Player</option>
                        <option value="Tournament Manager">Tournament Manager</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Status (Active/In-Active)</label>
                    <select class = "form-select" name="Status" id="user_status">
                        <option value="Active">Active</option>
                        <option value="In-Active">In-Active</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id ="submitUser"name ="saveUser"class="btn btn-primary">Edit User</button>
            </div>
        </form>
    </div>
  </div>
</div>
 <div class="container-fluid px-4">
    <h4 class="mt-4">Users</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item active">Users</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                     <h4>Registered User
                     <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#userAddModal">
                            Add User
                    </button>
                    </h4>
                </div>
                <div class="text-box">
                    <div class="col-md-3">
                         <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" />
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" />
                    </div>
                    <div class="col-md-5">
                        <input type="button" name="filter" id="filter" value="Filter" class="btn btn-info" />
                    </div>
                </div>
                <div id="filters">
                    <span>Fetch results by &nbsp;</span>
                    <select class="form-control-sm"name=fetchval" id="fetchval">
                        <option value="" disabled="" selected="">Select Filter</option>
                        <option value="Active">Active</option>
                        <option value="Admin">Admin</option>
                        <option value="Player">Player</option>
                        <option value="Tournament Manager">Tournament Manager</option>
                        <option value="In-Active">In-Active</option>
                    </select>
                </div>
                    <table id="myTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>BirthDay</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Disable</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <?php
                                $db = new dbClass();
                                $db = $db->getAllUsersInfo();
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
