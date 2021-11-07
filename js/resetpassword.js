// deconstruct the URL and get the verification token
let pathname = window.location.pathname;
let token = pathname.replace('/users/reset/', '');
token = (token.endsWith('/')) ? token.slice(0, -1) : token; // remove trailing slash

// get the form elements
const $form = $('#reset-form');
const $inputPass = $('input[name="pass-new"]');
const $inputPassConfirm = $('input[name="pass-confirm"]');

// validation element and class
const $formFeedback = $('.feedback');
const validateClass = 'invalid';

// check 
$form.submit(function(e) {
    e.preventDefault();
    $formFeedback.empty();

    const myForm = document.getElementById('reset-form');
    const formData = new FormData(myForm);
    const mFD = Object.fromEntries(formData);
    mFD['token'] = token;

    // check password
    if (!reValidation(mFD['pass-new'], $inputPass, pwdRE, validateClass)) {
        passFeedback(mFD['pass-new'], $formFeedback);
    }

    // check if the passwords are the same
    else if (!passRepeatFeedback(mFD['pass-new'], mFD['pass-confirm'], 
        $inputPassConfirm, $formFeedback, validateClass)) {
            // do nothing actually, all the work is already being done
    } else {

        // check username and email and do everything if they match a user,
        // otherwise do nothing
        postData('controllers/resetpassword.php', mFD)
            .then(data => {
                $formFeedback.removeClass('hide').addClass(data['class'])
                    .append($('<li/>', { text: data['text'] }));

                // disable another form submit
                if (data['class'] === 'success') {
                    $form.find('.btn-submit').attr('disabled', true);
                }
            })
            .catch((error) => {
                console.error(error);
            });


    }


});