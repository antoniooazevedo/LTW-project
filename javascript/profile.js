emailForm = document.getElementById('emailForm');
userEmail = document.getElementById('userEmail');
nameForm = document.getElementById('nameForm');
nameUser = document.getElementById('user_name');

emailForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const response = await fetch('../actions/action_change_email.php?email=' + document.getElementById('email').value);
    const res = await response.json();
    if (res === '') {
        console.log(res);
        userEmail.textContent = document.getElementById('email').value;
        document.getElementById('email').value = '';
        closeForm();
    }
    else {
        alert(res);
    }
});

nameForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const response = await fetch('../actions/action_change_name.php?name=' + document.getElementById('name').value);
    const res = await response.json();
    if (res === '') {
        nameUser.textContent = document.getElementById('name').value;
        document.getElementById('name').value = '';
        closeForm();
    } else {
        alert(res);
    }
});

function opacityPointerA(){
    const elements = document.querySelectorAll('footer, header, .profileContainer');
    for (let i = 0; i < elements.length; i++) {
        elements[i].style.pointerEvents = 'none';
        elements[i].style.opacity = '0.5';
    }
}
function opacityPointerD(){
    const elements = document.querySelectorAll('footer, header, .profileContainer');
    for (let i = 0; i < elements.length; i++) {
        elements[i].style.pointerEvents = '';
        elements[i].style.opacity = '';
    }
}

function openEmailForm() {
    opacityPointerA();
    document.getElementById('popupEmail').style.display = 'block';
}

function openPswForm() {
    opacityPointerA();
    document.getElementById('popupPsw').style.display = 'block';
}

function openInfoForm(){
    opacityPointerA();
    document.getElementById('popupName').style.display = 'block';
}
function openUserNameForm(){
    opacityPointerA();
    document.getElementById('popupUserName').style.display = 'block';
}

function closeForm() {
    opacityPointerD();
    document.getElementById('popupEmail').style.display = 'none';
    document.getElementById('popupPsw').style.display = 'none';
    document.getElementById('popupName').style.display = 'none';
    document.getElementById('popupUserName').style.display = 'none';
}

