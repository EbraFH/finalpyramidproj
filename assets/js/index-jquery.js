//this function hides the desired interfaces and shows the desired interfaces
$(document).ready(function () {
    //this function shows the register interface
    $("#register-btn").click(function () {
        $("#login-box").hide();
        $("#register-box").show();
    });
    //this function shows the login interface
    $("#login-btn").click(function () {
        $("#login-box").show();
        $("#register-box").hide();
    });
    //this function shows the forgot password interface
    $("#forgot-btn").click(function () {
        $("#login-box").hide();
        $("#forgot-box").show();
    })
    //this function is going back from forgot password to login interface
    $("#back-btn").click(function () {
        $("#forgot-box").hide();
        $("#login-box").show();
    });
    $("#login-form").validate();
    $("#register-form").validate();
    $("forgot-form").validate();

    // submit form without page regresh
    $("#register").click(function (e) {
        if (document.getElementById('register-form').checkValidity()) {
            e.preventDefault();//this function will prevent the page refreshing when we click on the submit button
            $.ajax({
                url: 'action.php',
                method: 'post',
                data: $('#register-form').serialize() + '&action=register',//this function will send the data to the url
                success: function (response) {
                    $('#alert').show();
                    $('#result').html(response);
                }
            })
        }
        return true;
    });
    //Login-form
    $("#login").click(function (e) {
        if (document.getElementById('login-form').checkValidity()) {
            e.preventDefault();//this function will prevent the page refreshing when we click on the submit button
            $.ajax({
                url: 'action.php',
                method: 'post',
                data: $('#login-form').serialize() + '&action=login',//this function will send the data to the url
                success: function (response) {
                    if (response === "Admin") {
                        window.location = 'admin/index.php';
                    }
                    else if (response === "Player") {
                        window.location = 'admin/index.php';
                    } else if (response === "Tournament Manager") {
                        window.location = 'admin/index.php';
                    }
                    else {
                        $('#alert').show();
                        $('#result').html(response);
                    }
                }
            })
        }
        return true;
    });

    $("#forgot").click(function (e) {
        if (document.getElementById('forgot-form').checkValidity()) {
            e.preventDefault();//this function will prevent the page refreshing when we click on the submit button
            $.ajax({
                url: 'action.php',
                method: 'post',
                data: $('#forgot-form').serialize() + '&action=forgot',//this function will send the data to the url
                success: function (response) {
                    $('#alert').show();
                    $('#result').html(response);
                }
            })
        }
        return true;
    });
});

