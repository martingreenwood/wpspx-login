(function($){

    const spektrix_api_url = $('div').data('wpspx-spektrix-base');

    console.log(spektrix_api_url);

    if (sessionStorage.getItem('spektrix_auth') === 'success') {
        let spektrixuserid = null;
        let userdetailssettings = {
            "async": true,
            "crossDomain": true,
            "url": spektrix_api_url + "customer",
            "method": "GET",
            "xhrFields": {
                "withCredentials": true
            },
            "headers": {
                "Content-Type": "application/json",
                "cache-control": "no-cache"
            },
            "statusCode": {
                401: function (error) {
                    // simply ignore this error
                }
            },
            "processData": false
        }
        $.ajax(userdetailssettings).done(function (userdetailsresponse) {
            if (typeof userdetailsresponse.id !== 'undefined' && userdetailsresponse.id !== '') {
                spektrixuserid = userdetailsresponse.id;
                $('.menu-item-wpspx-login').html('<div class="greeting">Hello ' + userdetailsresponse.firstName + '</div><a class="is-info" href="/my-account">">My Account</a><a class="logoutlink" onclick="wpspxLogout();">Log Out</a>'
                );

            }
        }).fail(function (xhr, status, error) {
            $('.menu-item-wpspx-login').html('<a class="loginlink" onclick="wpspxLogin()">Log in</a>');
        });

    }

    wpspxLogout = () => {
        const settings = {
            "async": true,
            "crossDomain": true,
            "url": spektrix_api_url + "customer/deauthenticate",
            "method": "POST",
            "xhrFields": {
                "withCredentials": true
            },
            "headers": {
                "Content-Type": "application/json",
                "cache-control": "no-cache"
            },
            "processData": false
        }
        $.ajax(settings).done(function (response) {
            location.reload();
        });
    }


    wpspxLogin = () => {

        $('.accountbox').addClass('is-active');
        $('.delete').on("click", function () {
            $('.accountbox').removeClass('is-active');
        });

        let lostpass = '<p>Please enter your email to reset your password.</p><div class="field"><div class="label"><label class="label">Email Address</label></div><div class="control"><input id="passwordresetemail" class="input" type="text" placeholder="your email" name="forgot-password" required></div></div><div class="message-field"><div class="message" style="display:none;"><div class="message-body"></div></div></div>';
        let logpaaslinks = '<button id="sendpassword" class="button btn button-primary">Reset Password<span class="loading"></span></button>';
        let loginform = '<p>Login to your account. Don\'t have one yet? You can <a class="registerlink" href="#">register here</a></p><div class="field"><div class="label"><label class="label">Email Address</label></div><div class="control"><input class="input" autocomplete="username" type="text" placeholder="Email" name="login-username" id="spektrix-username" required></div></div><div class="field"><div class="label"><label class="label">Password</label></div><div class="control"><input class="input" id="spektrix-password" autocomplete="current-password" type="password" placeholder="Password" name="login-password" required></div></div><div class="message-field"><div class="message" style="display:none;"><div class="message-body"></div></div></div></div>';
        let loginformlinks = '<button id="loginbutton" class="button btn button-primary">Login<span class="loading"></span></button><button id="forgotpassword" class="button btn button-secondary">Forgot password?</button>';
        let registerform = '<p>Please complete the below form to register for an account.</p><div class="field"><div class="label"><label class="label">Name</label></div><div class="control is-horizontal"><div class="field"><input class="input" type="text" placeholder="First Name" id="signup-firstname"></div><div class="field"><input class="input" type="text" placeholder="Last Name" id="signup-lastname"></div></div></div><div class="field"><label class="label">Date of Birth</label><div class="control"><input class="input" type="date" id="signup-birthdate"></div></div><div class="field"><label class="label">Phone Number</label><div class="control"><input class="input" type="text" id="signup-phone"></div></div><div class="field"><label class="label">Email Address</label><div class="control"><input class="input" type="email" id="signup-email"></div></div><div class="message-field"><div class="message" style="display:none;"><div class="message-body"></div></div></div></div>';
        let registerformlinks = '<button id="register" class="button btn button-primary">Register<span class="loading"></span></button>';


        $('.modal-card-title').html('Login to your account');
        $('.formcontent').html(loginform);
        $('.modal-card-foot').html(loginformlinks);

        //login button
        $('#loginbutton').on("click", function () {
            $(this).children('span').addClass('show');
            const settings = {
                "async": true,
                "crossDomain": true,
                "url": spektrix_api_url + "customer/authenticate",
                "method": "POST",
                "xhrFields": {
                    "withCredentials": true
                },
                "headers": {
                    "Content-Type": "application/json",
                    "cache-control": "no-cache"
                },
                "processData": false,
                "data": "{\r\n  \"email\": \"" + $('#spektrix-username').val() + "\",\r\n  \"password\": \"" + $('#spektrix-password').val() + "\"\r\n}"
            }
            $.ajax(settings).done(function (response) {
                if (response.id !== '') {
                    sessionStorage.setItem("spektrix_auth", "success");
                    $('.message').addClass('is-success');
                    $('.message .message-body').text('Sucessfully loggin in...');
                    $('.message').show();
                    setTimeout(function () {
                        $('#loginbutton span').removeClass('show');
                        $('.accountbox').removeClass('is-active');
                    }, 1000);
                    setTimeout(function () {
                        location.reload();
                    }, 3000);
                }
                else {
                    $('.message').addClass('is-danger');
                    $('.message .message-body').text('There was an error.');
                    $('.message').show();
                    $('#loginbutton span').removeClass('show');
                }
            }).fail(function (xhr, status, error) {
                switch (xhr.status) {
                    case 404:
                        $('.message').addClass('is-danger');
                        $('.message .message-body').text('Invalid username or password');
                        setTimeout(function () {
                            $('.message').show();
                            $('#loginbutton span').removeClass('show');
                        }, 1000);
                        break;
                    case 401:
                        $('.message').addClass('is-danger');
                        $('.message .message-body').text('There was an error trying to log you in.');
                        setTimeout(function () {
                            $('.message').show();
                            $('#loginbutton span').removeClass('show');
                        }, 1000);
                        break;
                    default:
                        $('.message').addClass('is-danger');
                        $('.message .message-body').text('There was an error trying to log you in.');
                        setTimeout(function () {
                            $('#loginbutton span').removeClass('show');
                            $('.message').show();
                        }, 1000);
                        break;
                }
            });
        });

        // forget password link
        $('#forgotpassword').on("click", function (e) {
            $('.modal-card-title').html('Forgot your password');
            $('.formcontent').html(lostpass);
            $('.modal-card-foot').html(logpaaslinks)
            $('#sendpassword').on("click", function (e) {
                $(this).children('span').addClass('show');
                var emailaddress = $('#passwordresetemail').val();
                if (emailaddress !== '') {
                    var settings = {
                        "async": true,
                        "crossDomain": true,
                        "url": spektrix_api_url + "customer/forgot-password?emailAddress=" + emailaddress,
                        "method": "POST",
                        "headers": {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "cache-control": "no-cache"
                        }
                    }

                    $.ajax(settings).done(function (response) {
                        $('.message').addClass('is-success');
                        $('.message .message-body').text('Password reset email sent.');
                        setTimeout(function () {
                            $('.message').show();
                            $('#sendpassword span').removeClass('show');
                        }, 1000);
                    }).fail(function (xhr, status, error) {
                        switch (xhr.status) {
                            case 404:
                                $('#sendpassword span').removeClass('show');
                                $('.message').addClass('is-danger');
                                $('.message .message-body').text('No account found with this email address.');
                                $('.message').show();
                                break;
                            default:
                                $('#sendpassword span').removeClass('show');
                                $('.message').addClass('is-danger');
                                $('.message .message-body').text('Sorry there was an error - please call the box office to reset your password.');
                                $('.message').show();
                                break;
                        }
                    });
                }
                else {
                    $('#sendpassword span').removeClass('show');
                    $('.message .message-body').text('You must enter an email address');
                    $('.message').show();
                }
            });
            // back to login
            $('#backtologin').on("click", function (e) {
                $('.modal-card-title').html('Login to your account');
                $('.formcontent').html(loginform);
                $('.modal-card-foot').html(loginformlinks);
            });
        });

        // register button
        $('.registerlink').on("click", function (e) {
            $('.modal-card-title').html('Sign uo for an account');
            $('.formcontent').html(registerform);
            $('.modal-card-foot').html(registerformlinks);
            $('#register').on("click", function (e) {
                $(this).children('span').addClass('show');
                var settings = {
                    "async": true,
                    "crossDomain": true,
                    "url": spektrix_api_url + "customer",
                    "method": "POST",
                    "xhrFields": {
                        "withCredentials": true
                    },
                    "headers": {
                        "Content-Type": "application/json",
                        "cache-control": "no-cache"
                    },
                    "processData": false,
                    "data": "{\r\n  \"birthDate\": \"" + $('#signup-birthdate').val() + "\",\r\n  \"email\": \"" + $('#signup-email').val() + "\",\r\n  \"firstName\": \"" + $('#signup-firstname').val() + "\",\r\n  \"lastName\": \"" + $('#signup-lastname').val() + "\",\r\n  \"mobile\": \"" + $('#signup-phone').val() + "\",\r\n  \"password\": \"" + $('#signup-password').val() + "\"\r\n}"
                }

                $.ajax(settings).done(function (response) {
                    if (response.id !== '') {
                        $('.message').addClass('is-success');
                        $('.message .message-body').text('Account sucessfully created, logging you in....');
                        $('.message').show();
                        $('#register span').removeClass('show');
                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                    }
                    else {
                        $('.message').addClass('is-success');
                        $('.message .message-body').text('There was an error.');
                        setTimeout(function () {
                            $('.message').show();
                            $('#register span').removeClass('show');
                        }, 1000);
                    }
                }).fail(function (xhr, status, error) {
                    switch (xhr.status) {
                        default:
                            $('.message').addClass('is-success');
                            $('.message .message-body').text('There was an error.');
                            setTimeout(function () {
                                $('.message').show();
                                $('#register span').removeClass('show');
                            }, 1000);

                            break;
                    }
                });
            });
        });
    }

})(jQuery);