$form = $('.modal form');

// populate setting change form
$('.btn-small').click(function(e) {
    $('.modal form').html($(this).next('.form-elements').children().clone(true, true));
    $('#modal-wrapper').css('visibility', 'visible');
});

$form.submit(function(e) {
    e.preventDefault();
    
    const $formFeedback = $('.feedback');
    $formFeedback.empty();
    const validateClass = 'invalid';

    // get the form data
    const myForm = document.querySelector('.modal form');
    const formData = new FormData(myForm);
    const mFD = Object.fromEntries(formData);

    // add username and determine what is being updated
    mFD['logged_in_user'] = $('#logged_in_user').text();
    const updateWhat = $form.find('button[type="submit"]').attr('data-update');
    mFD['what'] = updateWhat;

    switch (updateWhat) {
        case 'username':

            // get the form elements
            const $inputUser = $('input[name="username"]');

            // validate username
            if (!reValidation(mFD['username'], $inputUser, nameRE, validateClass)) {
                usernameFeedback(mFD['username'], $formFeedback);
            } else {
    
                // check availability
                postData('controllers/check_username.php', { username: mFD['username'] })
                    .then(data => {
                        if (data['class'] === 'error') {
                            $inputUser.addClass('invalid');
                            $formFeedback.removeClass('hide').addClass(data['class'])
                                .prepend($('<li/>', { text: data['text'] })
                            );
                            return false;
                        }
                        $inputUser.removeClass('invalid');
                        return true;
                    })
                    .then(okay => {
                        if (okay) {
    
                            // update
                            postData('controllers/update_user_settings.php', mFD)
                                .then(data => {
                                    $formFeedback.removeClass('hide').addClass(data['class'])
                                        .append($('<li/>', { text: data['text'] }));

                                        // refresh
                                        if (data['class'] === 'success') {
                                            setTimeout(() => {
                                                window.location.href = 'profile/';
                                            }, 3000);
                                        }
                                })
                                .catch((error) => {
                                    console.error('Error: ', error);
                                });
    
                        }
                    })
                    .catch((error) => {
                        console.error('Error: ', error);
                    });

            }
            break;

        case 'email':

            // get the form elements
            const $inputEmail = $('input[name="email"]');
            const $inputPass = $('input[name="password"]');

            // check email format
            if (!reValidation(mFD['email'], $inputEmail, emailRE, validateClass)) {
                $formFeedback.removeClass('hide').addClass('error')
                    .append($('<li/>', { text: 'Please enter a valid email address' }));
            } else {
    
                // check password
                postData('controllers/check_credentials.php', { 
                        username: mFD['logged_in_user'],
                        password: mFD['password']
                    })
                    .then(data => {

                        if (data['class'] !== 'success') {
                            $inputPass.addClass('invalid');
                            $formFeedback.removeClass('hide').addClass(data['class'])
                                .append($('<li/>', { text: data['text'] }));
                            return false;
                        }
                        $inputPass.removeClass('invalid');
                        return true;
                    })
                    .then(okay => {
                        if (okay) {
    
                            // update
                            postData('controllers/update_user_settings.php', mFD)
                                .then(data => {
                                    $formFeedback.addClass(data['class'])
                                        .append($('<li/>', { text: data['text'] }));
                                    
                                    // refresh
                                    if (data['class'] === 'success') {
                                        setTimeout(() => {
                                            window.location.href = 'logout/';
                                        }, 5000);
                                    }
                                })
                                .catch((error) => {
                                    console.error('Error: ', error);
                                });
    
                        }
                    })
                    .catch((error) => {
                        console.error('Error: ', error);
                    });

            }
            break;

        case 'password':

            // get the form elements
            const $inputPassOld = $('input[name="pass-old"]');
            const $inputPassNew = $('input[name="pass-new"]');
            const $inputPassConfirm = $('input[name="pass-confirm"]');

            // check new password format
            if (!reValidation(mFD['pass-new'], $inputPassNew, pwdRE, validateClass)) {
                passFeedback(mFD['pass-new'], $formFeedback);
            } 
            
            // check if confirm password is the same
            else if (mFD['pass-new'] !== mFD['pass-confirm']) {
                passRepeatFeedback(mFD['pass-new'], mFD['pass-confirm'], 
                $inputPassConfirm, $formFeedback, validateClass);
            } 

            // check current password
            else {

                // check password
                postData('controllers/check_credentials.php', { 
                    username: mFD['logged_in_user'],
                    password: mFD['pass-old']
                })
                .then(data => {

                    if (data['class'] !== 'success') {
                        $inputPassOld.addClass('invalid');
                        $formFeedback.removeClass('hide').addClass(data['class'])
                            .append($('<li/>', { text: data['text'] }));
                        return false;
                    }
                    $inputPassOld.removeClass('invalid');
                    return true;
                })
                .then(okay => {
                    if (okay) {

                        // update abc123DEF456
                        postData('controllers/update_user_settings.php', mFD)
                            .then(data => {
                                $formFeedback.removeClass('hide').addClass(data['class'])
                                    .append($('<li/>', { text: data['text'] }));

                                // refresh
                                if (data['class'] === 'success') {
                                    setTimeout(() => {
                                        window.location.href = 'logout/';
                                    }, 5000);
                                }
                            })
                            .catch((error) => {
                                console.error('Error: ', error);
                            });

                    }
                })
                .catch((error) => {
                    console.error('Error: ', error);
                });

            }

            break;
    }
});