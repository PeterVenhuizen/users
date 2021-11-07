// deconstruct the URL and get the verification token
let pathname = window.location.pathname;
let token = pathname.replace('/users/verify/', '');
token = (token.endsWith('/')) ? token.slice(0, -1) : token; // remove trailing slash

$formFeedback = $('.feedback');
if (token === '') {
    $formFeedback.removeClass('error').addClass('error')
        .append($('<li/>', { text: 'Activation error! Missing a verification token.' }));
} else {
    postData('controllers/user_activation.php', { token: token })
        .then(data => {
            $formFeedback.removeClass('hide').addClass(data['class'])
                .append($('<li/>', { text: data['text'] }));

            if (data['class'] !== 'error') {
                $('.underline').removeClass('hide');
            }

        })
        .catch((error) => {
            console.error(error);
        })
}