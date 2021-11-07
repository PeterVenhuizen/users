// load invites and users on page loag
window.addEventListener('load', (e) => {

    const tBodyUsers = $('#tbody-users');
    const tBodyInvites = $('#tbody-invites');

    postData('controllers/attach_strings.php')
        .then(data => {

            // populate users
            for (let user of data['users']) {
                let rO = new Date(user['registered_on']);
                tBodyUsers.append($('<tr/>').append([
                    $('<td/>', { text: user['username'] }),
                    $('<td/>', { text: user['privileges'] }),
                    $('<td/>', { text: `${rO.getDate()}-${rO.getMonth()+1}-${rO.getFullYear()}` }),
                    $('<td/>', { text: (user['is_active']) ? 'yes' : 'no' }),
                    $('<td/>', { text: (user['is_blocked']) ? 'yes' : 'no' }),
                    $('<td/>', { 'class': 'controls' }).append([
                        $('<button/>', { 
                            'class': `btn-small btn-error `, 
                            'data-id': user['id'],
                            'data-action': 'block-user',
                            text: (user['is_blocked']) ? 'Unblock' : 'Block'
                        }),
                        $('<button/>', { 
                            'class': 'btn-small btn-warning update-rights', 
                            'data-id': user['id'],
                            'data-action': 'update-rights',
                            'data-username': user['username'],
                            text: 'Change rights'
                        })
                    ])
                ]));
            }

            // populate invites
            for (let invite of data['invites']) {
                let eA = new Date(invite['expire_at']);
                // let expire = `${eA.getDate()}-${eA.getMonth()+1}-${eA.getFullYear()} ${eA.getHours()}:${eA.getMinutes()}`;
                let expire = `${eA.getDate()}-${eA.getMonth()+1}-${eA.getFullYear()}`;

                tBodyInvites.append($('<tr/>').append([
                    $('<td/>', { text: invite['privileges'] }),
                    $('<td/>', { text: expire }),
                    $('<td/>', { text: invite['uses_left'] }),
                    $('<td/>', { text: `${invite['used_by']}` }),
                    $('<td/>', { 'class': 'controls' }).append([
                        $('<button/>', { 
                            'class': 'btn-small btn-warning', 
                            'data-id': invite['id'],
                            'data-action': 'disable-invite',
                            text: 'Disable ',
                            'disabled': (!invite['uses_left']) ? true : false
                        }).append($('<i/>', { 'class': 'fas fa-ban' })),
                        $('<button/>', { 
                            'class': 'btn-small btn-error',
                            'data-action': 'delete-invite', 
                            'data-id': invite['id'],
                            text: 'Delete '
                        }).append($('<i/>', { 'class': 'fas fa-trash' }))
                    ])
                ]));
            }

        })
        .catch((error) => {
            console.error(error);
        });

});

// populate setting change form
$('body').on('click', '.update-rights', function(e) {
    $('.modal form').html($('.form-elements:first').children().clone(true, true));
    $('#modal-wrapper').css('visibility', 'visible');
    
    // set user id and username
    $('input[name="id"]').val($(this).attr('data-id'));
    $('input[name="username"]').val($(this).attr('data-username'));
    $('.modal form span.username').text($(this).attr('data-username'));
});

$('#create-new-invite').click(function(e) {
    $('.modal form').html($(this).next('.form-elements').children().clone(true, true));
    $('#modal-wrapper').css('visibility', 'visible');
});

// change user rights and create invite
$form = $('.modal form');
$form.submit(function(e) {
    e.preventDefault();

    const $formFeedback = $('.modal .feedback');
    $formFeedback.empty();

    // get the form data
    const myForm = document.querySelector('.modal form');
    const formData = new FormData(myForm);
    const mFD = Object.fromEntries(formData);

    // determine what is being updated
    const whatAction = $form.find('button[type="submit"]').attr('data-action');
    mFD['action'] = whatAction;

    postData('controllers/pull_strings.php', mFD)
    .then(data => {
        $formFeedback.removeClass('hide').addClass(data['class'])
            .append($('<li/>', { text: data['text'] }));

        // replace submit button by close button
        $form.find('button[type="submit"]').hide();
        $form.append($btnClose);
    })
    .catch((error) => {
        console.error(error);
    });    

});

// modal buttons
// click 'yes' to do action
const $btnYes = $('<button/>', { 
    'class': 'btn-medium btn-error btn-yes', 
    'type': 'button',
    text: 'Yes' });

$('.modal').on('click', '.btn-yes', function(e) {
    e.preventDefault();
    postData('controllers/pull_strings.php', { 
        action: $btnYes.attr('data-action'),
        id: $btnYes.attr('data-id')})
        .then(data => {
            location.reload();
            return false;
        });
});

// click 'no' to close modal
const $btnNo = $('<button/>', { 
    'class': 'btn-medium btn-primary btn-no', 
    'type': 'button',
    text: 'No' });

$('.modal').on('click', '.btn-no', function(e) {
    e.preventDefault();
    $('#modal-wrapper').css('visibility', 'hidden');
});

// click 'btn-close' to refresh page
const $btnClose = $('<button/>', {
    'type': 'button',
    'class': 'btn-submit btn-primary btn-close',
    text: 'Close' });

$('.modal').on('click', '.btn-close', function(e) {
    location.reload();
    return false;
});

// modal confirmation functions
$('body').on('click', 'button[data-action]', function(e) {
    let action = $(this).attr('data-action');
    let splitAction = action.split('-');

    if (!['update-rights', 'new-invite'].includes(action)) {

        // set yes button actions
        $btnYes.attr({
            'data-action': action,
            'data-id': $(this).attr('data-id')
        });

        // fill the form
        $form.empty();
        $form.append([
            $('<h2/>', { text: `${splitAction[0].capitalize()} ${splitAction[1]}` }),
            $('<p/>', { text: `Are you sure you want to (un)${splitAction[0]} this ${splitAction[1]}?` }),
            $('<div/>', { 'class': 'yes-no' }).append([$btnYes, $btnNo])
        ]);

        $('#modal-wrapper').css('visibility', 'visible');

    }
});