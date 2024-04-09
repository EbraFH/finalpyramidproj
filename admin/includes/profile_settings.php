<!-- User profile edit modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h1>Profile Settings</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="profileSettings">
            <div class="modal-body">
                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">ID</label>
                    <input readonly Id="Currentuser_id" type="text" name="Id" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Name</label>
                    <input Id="Currentuser_name" type="text" name="name" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Password</label>
                    <input type="password" id="CurrentPassword"name="Password" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">Phone</label>
                    <input type="text" name="Phone" id="Currentuser_phone"class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="">BirthDay</label>
                    <input type="Date" name="BirthDay" class="form-control" id="Currentuser_birthDay"/>
                </div>
                <div class="mb-3">
                    <label for="">Address</label>
                    <input type="text" name="Address" class="form-control" id="Currentuser_address"/>
                </div>
                <div class="mb-3">
                    <label for="">Email</label>
                    <input type="email" name="Email" class="form-control" id="Currentuser_email"/>
                </div>
                <div class="mb-3">
                    <label for="">Role (Admin/Tournament Manager/Player)</label>
                    <input readonly Id="Currentuser_role" type="text" name="Role" class="form-control"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" name="DeActive" class="btn btn-danger">De-Activate</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id ="submitUser"name ="saveUser" class="btn btn-primary">Edit User</button>
            </div>
        </form>
    </div>
  </div>
</div>