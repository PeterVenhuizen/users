// get the form elements
const $form = $('#forgot-form');
const $inputUser = $('input[name="username"]');
const $inputEmail = $('input[name="email"]');

// validation element and class
const $formFeedback = $('.feedback');
const validateClass = 'invalid';

// check 
$form.submit(function(e) {
    e.preventDefault();
    $formFeedback.empty();

    const myForm = document.getElementById('forgot-form');
    const formData = new FormData(myForm);
    const mFD = Object.fromEntries(formData);

    // check username and email and do everything if they match a user,
    // otherwise do nothing
    postData('controllers/forgotpassword.php', mFD)
        .then(data => {
            $formFeedback.removeClass('hide').addClass(data['class'])
                .append($('<li/>', { text: data['text'] }));
        })
        .catch((error) => {
            console.error(error);
        });

});