const $form = $('#login-form');
$form.submit(function(e) {
    e.preventDefault();

    const myForm = document.getElementById('login-form');
    const formData = new FormData(myForm);
    const myFormData = Object.fromEntries(formData);

    // see if the user was redirected here
    let url = window.location.href;
    url = url.split('?')[1]; // only keep the bits after the question mark
    const urlParams = new URLSearchParams(url);
    const validRedirects = ['sinterklaas/', 'books/', 'bingo/'];
    let redirectedFrom = urlParams.get('redirectedFrom');
    myFormData['redirectTo'] = (validRedirects.includes(redirectedFrom)) 
        ? redirectedFrom : 'users/profile/';
    console.log(myFormData);

    const $formFeedback = $('.feedback');
    $formFeedback.empty();

    // check if username was given
    if (myFormData['username'] === '') {
        $formFeedback.removeClass('hide').addClass('error')
            .append($('<li/>', { text: 'Username not provided' } ));
    }

    // check if password was given
    else if (myFormData['password'] === '') {
        $formFeedback.removeClass('hide').addClass('error')
            .append($('<li/>', { text: 'Password not provided' } ));
    }

    // check with the db
    else {
        postData('controllers/check_credentials.php', myFormData)
            .then(data => {
                $formFeedback.removeClass('hide').addClass(data['class'])
                    .append($('<li/>', { text: data['text'] }));

                // submit a hidden form to the actual login php page
                if (data['class'] === 'success') {

                    let form = $('<form/>', { 
                            'action': 'controllers/login.php',
                            'method': 'post'
                        }).append([
                            $('<input/>', { 
                                'type': 'text', 
                                'name': 'username', 
                                'value': myFormData['username'] 
                            }),
                            $('<input/>', {
                                'type': 'password',
                                'name': 'password',
                                'value': myFormData['password']
                            }),
                            $('<input/>', {
                                'type': 'checkbox',
                                'name': 'remember'
                            }),
                            $('<input/>', {
                                'type': 'text',
                                'name': 'redirectTo',
                                'value': myFormData['redirectTo']
                            })
                        ]);
                    form.find('input[type="checkbox"]')
                        .attr('checked', (myFormData.hasOwnProperty('remember')) ? true : false);
                    $('body').append(form);
                    form.submit();
                }
            })
            .catch((error) => {
                console.error(error);
            })
    }
});