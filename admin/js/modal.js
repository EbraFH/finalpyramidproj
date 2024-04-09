//this file is for the pop up page 
"use strict";

//dashboard functions
$(document).on("click", "#viewTm", function () {
    var formData = new FormData();
    formData.append("viewTm", true);
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            alert(response);
            $('.tbodytmModal').html(response);
        }
    })
});
//END OF DASHBOARD FUNCTION
$(document).on("submit", "#saveUser", function (e) {
    //this function is for the modal (popup) to add a new user
    e.preventDefault();//this line prevents the page from reloading
    var formData = new FormData(this);//getting the form data
    formData.append("save_user", true);
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text(res.message);
            }
            else if (res.status == 200) {
                $('#errorMessage').addClass('d-none');
                $('#userAddModal').modal('hide');
                $('#saveUser')[0].reset();
                $("#myTable").load(location.href + " #myTable");
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                alertify.success(res.message);
            }
        }
    });
});
$(document).on('click', '.editUserBtn', function () {
    //This function opens a the edit modal and shows the current user informations
    var user_id = $(this).val();
    // var user_id_color = $('#user_id');
    // user_id_color.css({
    //     'background-color': 'gray'
    // });
    $.ajax({
        type: "GET",
        url: "modal.php?user_id=" + user_id,
        success: function (response) {
            var res = jQuery.parseJSON(response);
            // if (res.status == 200) {
            $('#user_id').val(res.data.userId);
            $('#user_name').val(res.data.userName);
            $('#user_password').val(res.data.userPassword);
            $('#user_phone').val(res.data.userPhone);
            $('#user_birthDay').val(res.data.userBirthDay);
            $('#user_address').val(res.data.userAddress);
            $('#user_email').val(res.data.userEmail);
            $('#user_role').val(res.data.userRole);
            $('#user_status').val(res.data.userStatus);
            $('#userEditModal').modal('show');
            // }
        }
    });
});
$(document).on("submit", "#updateUser", function (e) {
    //this function is for the modal (popup) it updates the users information in the database
    e.preventDefault();//this line prevents the page from reloading
    var formData = new FormData(this);//getting the form data
    formData.append("update_user", true);
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
                $('#errorMessageUpdate').removeClass('d-none');
                $('#errorMessageUpdate').text(res.message);
            }
            else if (res.status == 200) {
                $('#errorMessageUpdate').addClass('d-none');
                $('#userEditModal').modal('hide');
                $('#updateUser')[0].reset();
                $("#myTable").load(location.href + " #myTable");
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                alertify.success(res.message);
            }
        }
    });
});
//update Profile
$(document).on('click', '.profileSettings', function () {
    //This function opens a the edit modal and shows the current user informations
    // var user_id = $(this).val();
    // var user_id_color = $('#user_id');
    // user_id_color.css({
    //     'background-color': 'gray'
    // });
    $.ajax({
        type: "GET",
        url: "modal.php?Currentuser_id=",
        success: function (response) {
            var res = jQuery.parseJSON(response);
            // if (res.status == 200) {
            $('#Currentuser_id').val(res.data.userId);
            $('#Currentuser_name').val(res.data.userName);
            $('#Currentuser_password').val(res.data.userPassword);
            $('#Currentuser_phone').val(res.data.userPhone);
            $('#Currentuser_birthDay').val(res.data.userBirthDay);
            $('#Currentuser_address').val(res.data.userAddress);
            $('#Currentuser_email').val(res.data.userEmail);
            $('#Currentuser_role').val(res.data.userRole);
            $('#CurrentuserEditModal').modal('show');
            // }
        }
    });
});
$(document).on("submit", "#profileSettings", function (e) {
    //this function is for the modal (popup) it updates the users profile in the database
    e.preventDefault();//this line prevents the page from reloading
    var formData = new FormData(this);//getting the form data
    formData.append("update_profile", true);
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
                $('#errorMessageUpdate').removeClass('d-none');
                $('#errorMessageUpdate').text(res.message);
            }
            else if (res.status == 200) {
                $('#errorMessageUpdate').addClass('d-none');
                $('#profileModal').modal('hide');
                $('#profileSettings')[0].reset();
                $().load(location.href);
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                alertify.success(res.message);
            }
        }
    });
});
//logout Function #logoutBtn
$(document).on("click", "#logoutBtn", function () {
    $.ajax({
        url: 'modal.php?argument=logOut',
        success: function (data) {
            alert(data);
            window.location.href = data;
        }
    });
});
//DeActivateUser
$(document).on("click", ".DeActiveUserBtn", function (e) {
    //This function De-activates the users account.
    //need fix
    //the issue is that the user_id is null 
    e.preventDefault();

    Swal.fire({
        title: 'Are you sure?',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var user_id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: {
                    'deActivate_user': true,
                    'user_id': user_id
                },
                success: function (response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 500) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: res.message,
                        })
                        // $('#myTable').load(location.href + " #myTable")
                        window.location.reload();
                    }
                }
            });
        }
    })
});
$(document).on('click', '.activeUserBtn', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var user_id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: {
                    'Activate_user': true,
                    'user_id': user_id
                },
                success: function (response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 500) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: res.message,
                        })
                        // $('#myTable').load(location.href + " #myTable")
                        window.location.reload();
                    }
                }
            });
        }
    })
});
$(document).ready(function () {
    //function that gets all users according to a certain date
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd'
    });
    $(function () {
        $("#from_date").datepicker();
        $("#to_date").datepicker();
    });
    $('#filter').click(function () {
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if (from_date != '' && to_date != '') {
            if (from_date < to_date) {
                $.ajax({
                    url: "modal.php",
                    method: "POST",
                    data: { from_date: from_date, to_date: to_date },
                    success: function (data) {
                        $('.tbody').html(data);
                        inActive();
                    }
                });
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
//this function gets the data for select filtering
$(document).ready(function () {
    $('#fetchval').on('change', function () {
        var value = $(this).val();
        //after changing the value of the select tag option it will be redirected to dbClass.php
        $.ajax({
            url: "modal.php",
            type: "POST",
            data: 'request=' + value,
            success: function (data) {
                $(".tbody").html(data);
                inActive();
            }
        });
    });
});
function inActive() {
    //this function red colors the inactive users background color
    let tr = document.getElementsByClassName("In-Active");
    for (let i = 0; i < tr.length; i++) {
        tr[i].style.backgroundColor = "red";
    }
}
inActive();
/*VIEW-TOURNAMENT FUNCTIONS*/
$(document).on("submit", "#saveTournament", function (e) {
    //this function is for the modal (popup) to create a new tournament
    e.preventDefault();//this line prevents the page from reloading
    var formData = new FormData(this);//getting the form data
    formData.append("save_Tournament", true);
    alert("test");
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text(res.message);
            }
            else if (res.status == 200) {
                $('#errorMessage').addClass('d-none');
                $('#TournamentEditModal').modal('hide');
                $('#saveTournament')[0].reset();
                // $("#myTable").load(location.href + " #myTable");
                window.location.reload();
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                alertify.success(res.message);
            }
        }
    });
});
$(document).on('click', '.editTournamentBtn', function () {
    //This function opens a the edit modal and shows the current user informations
    var tournament_id = $(this).val();
    // var user_id_color = $('#user_id');
    // user_id_color.css({
    //     'background-color': 'gray'
    // });
    $.ajax({
        type: "GET",
        url: "modal.php?tournament_id=" + tournament_id,
        success: function (response) {
            var res = jQuery.parseJSON(response);
            // if (res.status == 200) {
            $('#tournamentId').val(res.data.tournamentId);
            $('#tournamentName').val(res.data.tournamentName);
            $('#tournamentParticipants').val(res.data.tournamentParticipant);
            $('#tournamentCreationDate').val(res.data.tournamentRegistrationDate);
            $('#tournamentStartDate').val(res.data.tournamentStartDate);
            $('#tournamentEndDate').val(res.data.tournamentEndDate);
            $('#tournamentPlace').val(res.data.tournamentPlace);
            $('#tournamentPrize').val(res.data.tournamentPrize);
            $('#tournamentWinner').val(res.data.tournamentWinner);
            $('#tournamentStatus').val(res.data.tournamentStatus);
            $('#TournamentEditModal').modal('show');
            // }
        }
    });
});
$(document).on("submit", "#updateTournament", function (e) {
    //this function is for the modal (popup) it updates the users information in the database
    e.preventDefault();//this line prevents the page from reloading
    var formData = new FormData(this);//getting the form data
    formData.append("update_tournament", true);
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
                $('#errorMessageUpdate').removeClass('d-none');
                $('#errorMessageUpdate').text(res.message);
            }
            else if (res.status == 200) {
                $('#errorMessageUpdate').addClass('d-none');
                $('#tournamentEditModal').modal('hide');
                $('#updateTournament')[0].reset();
                // $("#myTable").load(location.href + " #myTable");
                window.location.reload();
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                alertify.success(res.message);
            }
        }
    });
});
$(document).ready(function () {
    //function that gets all tournaments according to a certain date
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd'
    });
    $(function () {
        $("#from_tdate").datepicker();
        $("#to_tdate").datepicker();
    });
    $('#tournament_filter').click(function () {
        var from_tdate = $('#from_tdate').val();
        var to_tdate = $('#to_tdate').val();
        if (from_tdate != '' && to_tdate != '') {
            if (from_tdate < to_tdate) {
                $.ajax({
                    url: "modal.php",
                    method: "POST",
                    data: { from_tdate: from_tdate, to_tdate: to_tdate },
                    success: function (data) {
                        $('.tbody').html(data);
                        inActive();
                    }
                });
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
/*End OF VIEW-TOURNAMENT FUNCTIONS*/
/* participants functions */
//getting all tournaments that are related to a certain tournament manager
$(document).ready(function () {
    $('#tournaments').on('change', function () {
        var value = $(this).val();
        //after changing the value of the select tag option it will be redirected to dbClass.php
        $.ajax({
            url: "modal.php",
            type: "POST",
            data: 'requestTournament=' + value,
            success: function (data) {
                $(".tbody").html(data);
                inActive();
            }
        });
    });
});
$(document).on("submit", "#saveParticipant", function (e) {
    //this function is for the modal (popup) to add a new participant to the tournament
    e.preventDefault();//this line prevents the page from reloading
    var formData = new FormData(this);//getting the form data
    formData.append("submit_Participant", true);
    var tname = $('#tournaments').val();
    formData.append("tName", tname);
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            alert(response);
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text(res.message);
            }
            else if (res.status == 200) {
                $('#errorMessage').addClass('d-none');
                $('#participantModal').modal('hide');
                $('#saveParticipant')[0].reset();
                window.location.reload();
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                alertify.success(res.message);
            }
        }
    });
});
$(document).on("submit", "#AdminsaveParticipants", function (e) {
    //this function is for the modal (popup) to add a new participant to the tournament
    e.preventDefault();//this line prevents the page from reloading
    var formData = new FormData(this);//getting the form data
    formData.append("submit_Participant", true);
    var tname = $("#AddParticipantbtn").val().split("-");
    formData.append("tName", tname[0]);
    formData.append("tMId", tname[1]);
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            alert(response);
            var res = jQuery.parseJSON(response);
            if (res.status == 422) {
                $('#errorMessage').removeClass('d-none');
                $('#errorMessage').text(res.message);
            }
            else if (res.status == 200) {
                $('#errorMessage').addClass('d-none');
                $('#participantModal').modal('hide');
                $('#saveParticipant')[0].reset();
                window.location.reload();
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                alertify.success(res.message);
            }
        }
    });
});
$(document).on("click", ".removeParticipantBtn", function (e) {
    e.preventDefault();
    var participantId = e.target.value;
    var ParticipantData = new FormData();
    ParticipantData.append("disableParticipant", true);
    ParticipantData.append("participantId", participantId)
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: ParticipantData,
        processData: false,
        contentType: false,
        success: function (response) {
            alert(response);
            var res = jQuery.parseJSON(response);
            if (res.status == 200) {
                $('#errorMessage').addClass('d-none');
                $('#participantModal').modal('hide');
                $('#saveParticipant')[0].reset();
                location.reload();
                alertify.set('notifier', 'position', 'top-right');
                alertify.set('notifier', 'delay', 5);
                alertify.success(res.message);
            }
        }
    });
})
//function that allows the player to quit a tournament quitTournament
$(document).on("click", ".quitTournament", function (e) {
    Swal.fire({
        title: 'Are you sure you want to quit this tournament?',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var quitTournamentInfo = $(this).val().split("-");
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: {
                    'quitTournament': true,
                    'playerId': quitTournamentInfo[0],
                    'tournamentId': quitTournamentInfo[1],
                    'playerRank': quitTournamentInfo[2],
                },
                success: function (response) {
                    alert(response);
                    var res = jQuery.parseJSON(response);
                    Swal.fire({
                        icon: 'success',
                        title: 'success',
                        text: res.message,
                    }).then((result) => {
                        location.reload();
                    })

                }
            });
        }
    })
})
$(document).on("click", ".getAdminParticipants", function (e) {
    var tournament = e.target.value.split("-");
    var tournamentData = new FormData();
    tournamentData.append("tournamentId", tournament[0]);
    tournamentData.append("tournamentManagerId", tournament[1]);
    tournamentData.append("tournamentName", e.target.name);
    tournamentData.append("AdmingetParticipants", true);
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: tournamentData,
        processData: false,
        contentType: false,
        success: function (response) {
            // $("#ParticipantsTable").load(location.href + " #ParticipantsTable");
            $('.tbodyModal').html(response);
        }
    })
});
/*END OF PARTICIPANTS FUNCTIONS*/
$(document).on("click", ".tournamentbtn", function () {
    //function that shows the tournament ladder to be continued.
    $("#tournamentLadder").removeClass('d-none');
})
/*END OF GETTING TOURNAMENT PYRAMID LADDER*/
$(document).on("click", ".tournamentbtn", function (e) {
    var tournamentId = e.target.value;//the id of the tournament that has been clicked on
    var tournamentData = new FormData();
    tournamentData.append("tournamentId", tournamentId);
    if (window.location.href === 'http://localhost/finalpyramidproj/admin/view-ladder.php') {
        tournamentData.append("tournamentBracket", true);
        $.ajax({
            type: "POST",
            url: "modal.php",
            data: tournamentData,
            processData: false,
            contentType: false,
            success: function (response) {
                $("#tournamentLadder").html(response);
            }
        });
    }
    else if (window.location.href === 'http://localhost/finalpyramidproj/admin/view-games.php') {
        tournamentData.append("tournamentGames", true);
        $.ajax({
            type: "POST",
            url: "modal.php",
            data: tournamentData,
            processData: false,
            contentType: false,
            success: function (response) {
                $(".tbody").html(response);
            }
        });
    }
})

//this function gets the data for select filtering
$(document).ready(function () {
    $('#fetchvalLadder').on('change', function () {
        var value = $(this).val();
        //after changing the value of the select tag option it will be redirected to dbClass.php
        $.ajax({
            url: "modal.php",
            type: "POST",
            data: 'fetchBy=' + value,
            success: function (data) {
                $(".tbody").html(data);
            }
        });
    });
});
/*START OF VIEW-GAMES FUNCTIONS*/
//this function gets the data for select filtering
$(document).ready(function () {
    $('#fetchvalGame').on('change', function () {
        var value = $(this).val();
        alert(value);
        //after changing the value of the select tag option it will be redirected to dbClass.php
        $.ajax({
            url: "modal.php",
            type: "POST",
            data: 'gamesFetchBy=' + value,
            success: function (data) {
                if (value == 'Suspended')
                    $(".tbody").html(data);
                else
                    $(".ladderBody").html(data);
                $("#gamesTable").DataTable();
            }
        });
    });
});
/*END OF VIEW-GAMES FUNCTIONS*/
$(document).ready(function () {
    $("#myTable").DataTable();
})


/*START OF PLAYER INTERFACE*/
$(document).on("click", ".joinTournamentBtn", function (e) {
    //This function allows the player to join a tournament
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure you want to participate?',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var tournament_id = e.target.value;
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: {
                    'joinTournament': true,
                    'tournament_id': tournament_id
                },
                success: function (response) {
                    alert(response);
                    var res = jQuery.parseJSON(response);
                    if (res.status == 500) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    }
                }
            });
        }
    })
});
$(document).on("click", ".ladderBtn", function (e) {
    var tournamentId = e.target.value;//the id of the tournament that has been clicked on
    var tournamentData = new FormData();
    tournamentData.append("tournamentId", tournamentId);
    tournamentData.append("tournamentBracket", true);
    $.ajax({
        type: "POST",
        url: "modal.php",
        data: tournamentData,
        processData: false,
        contentType: false,
        success: function (response) {
            $(".pycontainer").html(response);
        }
    });
})
$(document).on('click', '.participant', function (e) {
    //This function opens a modal to show player information
    var tInfo = $(this).val().split(" ");
    $.ajax({
        type: "GET",
        url: "modal.php",
        data: 'pId=' + tInfo[0] + '&tId=' + tInfo[1],
        success: function (response) {
            var res = jQuery.parseJSON(response);
            $('#Player_id').val(res.data.userId);
            $('#Player_name').val(res.data.userName);
            $('#Player_phone').val(res.data.userPhone);
            $('#Player_address').val(res.data.userAddress);
            $('#Player_email').val(res.data.userEmail);
            $('#Player_rank').val(res.data.rankingNum);
            $('#Player_status').val(res.data.status);
            $('#Tournament_id').val(res.data.tournamentId);
            $('#ladderParticipantModal').modal('show');
        }
    });
});
$(document).on('click', '.inviteBtn', function (e) {
    //this function is to check if the player can be invited.
    Swal.fire({
        title: 'Are you sure you want to invite this player?',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var invitationData = new FormData();
            invitationData.append("invBtn", true);
            invitationData.append("rivalId", document.getElementById("Player_id").value);
            invitationData.append("tId", document.getElementById("Tournament_id").value);
            invitationData.append("rivalStatus", document.getElementById("Player_status").value);
            invitationData.append("rivalRank", document.getElementById("Player_rank").value);
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: invitationData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var res = jQuery.parseJSON(response);
                    if (res.status == 422) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message,
                        }).then((result) => {
                            $('#InviteParticipantModal').css('display', 'none');
                            $('#InviteParticipantModal').modal('hide');
                        })
                    } else {
                    }
                }
            })
        }
    });
});
$(document).on('submit', '#InviteParticipant', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Confirm Invitation',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var invitationInfo = new FormData();
            invitationInfo.append("sendInvitation", true);
            invitationInfo.append("InvitationDate", document.getElementById("meeting-time").value.slice(0, 19).replace('T', ' '));
            invitationInfo.append("matchLocation", document.getElementById("matchLocation").value);
            invitationInfo.append("rivalId", document.getElementById("Player_id").value);
            invitationInfo.append("tId", document.getElementById("Tournament_id").value);
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: invitationInfo,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response);
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    }
                }
            });
        }
    })
});
$(document).ready(function () {
    $('#fetchvalPlayerInvitation').on('change', function () {
        var value = $(this).val();
        //after changing the value of the select tag option it will be redirected to dbClass.php
        $.ajax({
            url: "modal.php",
            type: "POST",
            data: 'fetchInvitationBy=' + value,
            success: function (data) {
                $(".tbody").html(data);
                $("#gamesTable").DataTable();
                // if (value === 'Sent') {
                //     var sents = document.getElementsByClassName('receiver');
                //     for (var i = 0; i < sents.length; i++)
                //         sents[i].style.display = 'none';

                // }
                // else {
                //     var receivers = document.getElementsByClassName('sender').style.display = 'none';
                //     receivers.style.display = 'none';
                //     var sents = document.getElementsByClassName('receiver');
                //     for (var i = 0; i < sents.length; i++)
                //         sents[i].style.display = '';
                // }
            }
        });
    });
});
//fetch invitation for games for Admin
$(document).ready(function () {
    $('#fetchvalAdminInvitation').on('change', function () {
        var value = $(this).val();
        //after changing the value of the select tag option it will be redirected to dbClass.php
        $.ajax({
            url: "modal.php",
            type: "POST",
            data: 'fetchAdminInvitationBy=' + value,
            success: function (data) {
                $(".tbody").html(data);
                $("#gamesTable").DataTable();
            }
        });
    });
});
//cancel a game in a tournament by player 
$(document).on('click', '.CancelGame', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Confirm cancellation',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var gameIdtoCancel = $(this).val();
            var CancellationData = new FormData();
            CancellationData.append("cancelBtn", true);
            CancellationData.append("gameIdtoCancel", gameIdtoCancel);
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: CancellationData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response);
                    var res = jQuery.parseJSON(response);
                    alert(res.status);
                    if (res.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    }
                    else {
                        location.reload();
                    }
                }
            });
        }
    })
});
//accept or declining invitation
$(document).on('click', '.AcceptInvBtn', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Confirm accepting invitation',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var invitationInfo = $(this).val().split("-");
            var acceptData = new FormData();
            acceptData.append("acceptInv", true);
            acceptData.append("invitationId", invitationInfo[0]);
            acceptData.append("accepterId", invitationInfo[1]);
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: acceptData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response);
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    }
                    else {
                        location.reload();
                    }
                }
            });
        }
    })
});
//decline invitation
$(document).on('click', '.DeclineInvBtn', function (e) {
    Swal.fire({
        title: 'Confirm declining the invitation',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var invitationInfo = $(this).val().split("-");
            var declineData = new FormData();
            declineData.append("declineInv", true);
            declineData.append("invitationId", invitationInfo[0]);
            declineData.append("declinerId", invitationInfo[1]);
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: declineData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response);
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    }
                    else {
                        location.reload();
                    }
                }
            });
        }
    })
});
$(document).ready(function () {
    //function that gets all invitations according to a certain date
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd'
    });
    $(function () {
        $("#from_idate").datepicker();
        $("#to_idate").datepicker();
    });
    $('#invitation_filter').click(function () {
        var from_date = $('#from_idate').val();
        var to_date = $('#to_idate').val();
        if (from_date != '' && to_date != '') {
            if (from_date < to_date) {
                $.ajax({
                    url: "modal.php",
                    method: "POST",
                    data: { from_idate: from_date, to_idate: to_date },
                    success: function (data) {
                        $('.tbody').html(data);
                        $("#gamesTable").DataTable();
                    }
                });
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
//function for date pick in game invitations.

//function that decides who won the match.
$(document).on('submit', '#WinnerForm', function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Confirm points',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var winnerInfo = new FormData();
            winnerInfo.append("winner", true);
            winnerInfo.append("points", document.getElementById("points").value);
            winnerInfo.append("gameId", document.getElementById("won").value);
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: winnerInfo,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response);
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: res.message,
                        }).then((result) => {
                            // location.reload();
                        })
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    }
                }
            });
        }
    })
});
//if the player presses on lost button
$(document).on('click', '#lost', function (e) {
    Swal.fire({
        title: 'Confirm result',
        // showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        // denyButtonText: `Don't delete`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            var loserInfo = new FormData();
            loserInfo.append("loser", true);
            loserInfo.append("points", 0);
            loserInfo.append("gameId", document.getElementById("lost").value);
            $.ajax({
                type: "POST",
                url: "modal.php",
                data: loserInfo,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response);
                    var res = jQuery.parseJSON(response);
                    if (res.status == 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'success',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message,
                        }).then((result) => {
                            location.reload();
                        })
                    }
                }
            });
        }
    })
});
/*END OF PLAYER INTERFACE*/

//functions for pdf downloads
// $(document).on("click", ".tmpdfBtn", function (e) {
//     var formData = new FormData();
//     formData.append("testpdf", true);
//     $.ajax({
//         type: "POST",
//         url: "modal.php",
//         data: formData,
//         processData: false,
//         contentType: false,
//         success: function (response) {

//         }
//     })
// });

//datepick
var today = new Date();
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0');
var yyyy = today.getFullYear();
today = yyyy + '-' + mm + '-' + dd;
$('.datepick').attr('min', today);

var today1 = new Date().toISOString().slice(0, 16);
document.getElementsByClassName("InviteDate")[0].min = today1;


//fetch games by date
$(document).ready(function () {
    //function that gets all tournaments according to a certain date
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd'
    });
    $(function () {
        $("#from_gdate").datepicker();
        $("#to_gdate").datepicker();
    });
    $('#game_filter').click(function () {
        var from_gdate = $('#from_gdate').val();
        var to_gdate = $('#to_gdate').val();
        if (from_gdate != '' && to_gdate != '') {
            if (from_gdate < to_gdate) {
                $.ajax({
                    url: "modal.php",
                    method: "POST",
                    data: { from_gdate: from_gdate, to_gdate: to_gdate },
                    success: function (data) {
                        $('.tbody').html(data);
                    }
                });
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