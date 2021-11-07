const $formFeedback = $('.feedback');

// parse and check the invite token
let inviteToken = getCleanURLParams('/users/register/')[0];
$('input[name="invite"]').val(inviteToken);

// initially disable the sign up button, while we check the invite token
$btnSubmit = $('button[type="submit"]');
$btnSubmit.attr('disabled', true);

postData('controllers/check_invite.php', { token: inviteToken })
    .then(data => {
        if (data['class'] === 'error') {
            $formFeedback.removeClass('hide').addClass(data['class'])
                .append($('<li/>', { text: data['text'] }));
        } else {
            $btnSubmit.attr('disabled', false);
        }
    })
    .catch((error) => {
        console.error(error);
    });


// form and form inputs
const $form = $('#register-form');
const $inputUser = $('input[name="username"]');
const $inputEmail = $('input[name="email"]');
const $inputPass = $('input[name="password"]');
const $inputPassRepeat = $('input[name="password-repeat"]');
const validateClass = 'invalid';

$form.submit(function(e) {
    e.preventDefault();
    $formFeedback.empty();

    const myForm = document.getElementById('register-form');
    const formData = new FormData(myForm);
    const mFD = Object.fromEntries(formData);

    // check email validity
    if (!reValidation(mFD['email'], $inputEmail, emailRE, validateClass)) {
        // $inputEmail.addClass(validateClass);
        $formFeedback.removeClass('hide').addClass('error').append($('<li/>', { text: 'Please enter a valid email address' }));
    }

    // check password
    if (!reValidation(mFD['password'], $inputPass, pwdRE, validateClass)) {
        passFeedback(mFD['password'], $formFeedback);
    }

    // check if the passwords are the same
    passRepeatFeedback(mFD['password'], mFD['password-repeat'], 
        $inputPassRepeat, $formFeedback, validateClass);

    // check if the username is not already in use
    if (mFD['username'].replaceAll(' ', '').length) {

        // check username format
        if (!reValidation(mFD['username'], $inputUser, nameRE, validateClass)) {
            usernameFeedback(mFD['username'], $formFeedback);
        } else {

            // check username availability
            postData('controllers/check_username.php', { username: mFD['username'] })
                .then(data => {
                    if (data['class'] !== 'success') {
                        $('input[name="username"]').addClass('invalid');
                        $formFeedback.removeClass('hide').addClass('error').prepend(
                            $('<li/>', { text: data['text'] })
                        );
                        return false;
                    }
                    $('input[name="username"]').removeClass('invalid');
                    return true;
                })
                .then(okay => {
                    if (okay) {

                        // check if there are any elements with the 'invalid' class
                        if (!$('.invalid').length) {
                            postData('controllers/register.php', mFD)
                                .then(data => {
                                    $formFeedback.removeClass('hide').addClass(data['class']).append($('<li/>', { text: data['text'] }));
                                })
                                .catch((error) => {
                                    console.error('Error: ', error);
                                });
                        }

                    }
                })
                .catch((error) => {
                    console.error('Error: ', error);
                });
        }
    } else {
        $formFeedback.append($('<li/>', { text: 'The username cannot zero characters, \
        or only consist out of white space characters.' }))
    }

});