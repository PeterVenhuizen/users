String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

async function postData(url = '', data = {}) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });
    return response.json();
}

function getCleanURLParams(ignoreStr='') {
    
    // split the clean URL in separate bits
    let pathname = window.location.pathname;

    // remove trailing slash
    pathname = (pathname.endsWith('/')) ? pathname.slice(0, -1) : pathname;

    // (optional) get rid of a portion of the string
    pathname = pathname.replace(ignoreStr, '');

    // split into array
    return pathname.split('/');

}

const unique = (value, index, self) => {
    return self.indexOf(value) === index
}

// RegExp validation
function reValidation(v, $el, re, cssClass) {
    let b = re.test(v);
    (b) ? $el.removeClass(cssClass) : $el.addClass(cssClass);
    return b;
}

const emailRE = new RegExp("^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$");

// ^            => The password string will start this way
// ((?|\s).)*   => May not contain whitespace characters
// (?=.*[a-z])  => The string must contain at least 1 lowercase character
// (?=.*[A-Z])  => The string must contain at least 1 uppercase character
// (?=.*[0-9])  => The string must contain at least 1 numeric character
// [a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{8,} => The string must be eight character or longer. Special characters are allowed
// const pwdRE = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{12,}$");
const pwdRE = new RegExp("^((?!\s).)*(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{12,}$");

// alphanumeric characters only
const nameRE = new RegExp("^[a-zA-Z0-9]+$");

// wrong password feedback
function passFeedback(v, $el) {
    // check individual elements
    if (!RegExp('[a-z]').test(v)) {
        $el.removeClass('hide').addClass('error')
            .append($('<li/>', { text: 'The password must contain at least one lowercase letter.' }));
    }
    if (!RegExp('[A-Z]').test(v)) {
        $el.removeClass('hide').addClass('error')
            .append($('<li/>', { text: 'The password must contain at least one uppercase letter.' }));
    }
    if (!RegExp('[0-9]').test(v)) {
        $el.removeClass('hide').addClass('error')
            .append($('<li/>', { text: 'The password must contain at least one number.' }));
    }
    if (RegExp('[ \s]+').test(v)) {
        $el.removeClass('hide').addClass('error')
            .append($('<li/>', { text: 'The password may not contain any whitespace characters.' }));
    }
    if (v.length < 12) {
        $el.removeClass('hide').addClass('error')
            .append($('<li/>', { text: 'The password must be at least 12 characters long.' }));
    }   
}

// password repeat feedback
function passRepeatFeedback(pass, passRepeat, $inputPassRepeat, $feedbackDiv, cssClass) {
    if (pass === passRepeat) {
        $inputPassRepeat.removeClass(cssClass);
        return true;
    } else {
        $inputPassRepeat.addClass(cssClass);
        $feedbackDiv.removeClass('hide').addClass('error')
            .append($('<li/>', { text: 'The repeated password does not match the first password.' }));
    }
    return false;
}

// username feedback
function usernameFeedback(v, $el) {

    // check length
    if (v.length < 3) {
        $el.removeClass('hide').addClass('error')
            .prepend($('<li/>', { text: 'Your username is too short.' }));
    }
    else if (v.length > 20) {
        $el.removeClass('hide').addClass('error')
            .prepend($('<li/>', { text: 'Your username is too long.' }));
    }

    // identify the illegal characters
    // first check at indices they are
    if (v.length) {
        let illegalIdx = [...v].map(char => !nameRE.test(char));
        let illegalChar = [...v].filter((char, idx) => illegalIdx[idx]);

        $el.removeClass('hide').addClass('error').prepend($('<li/>', { 
            text: `Your username contains (an) illegal character(s):
            ${illegalChar.filter(unique).map(c => `'${c}'`).join(', ')}`
        }));
    }
}

/* MODAL */
$('.modal-close').click(function(e) {
    $('#modal-wrapper').css('visibility', 'hidden');
});