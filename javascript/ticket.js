import {encodeForAjax} from "../utils/ajax.js";

const statusForm = document.getElementById('statusChangeForm');
const statusName = document.getElementById('ticketStatus');
const agentForm = document.getElementById('agentChangeForm');
const agentName = document.getElementById('ticketAgent');
const departmentForm = document.getElementById('departmentChangeForm');
const departmentName = document.getElementById('ticketDepartment');
const priorityForm = document.getElementById('priorityChangeForm');
const priorityName = document.getElementById('ticketPriority');
let isResponseVisible = false;
const form = document.getElementById('responseForm');
const ticketResponses = document.getElementById('responseDiv');

document.querySelectorAll('.editForm').forEach(form => {
    form.style.display = 'none';
});

form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const comment = document.getElementById('comment').value;
    const ticket_id = document.getElementsByName('ticket_id')[0].value;
    const response = await fetch('../actions/action_add_response.php',
        {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: encodeForAjax({
                comment: comment,
                ticket_id: ticket_id
            }),
        });

    const res = await response.json();
    if (res === '') {
        const responseDiv = document.createElement('div');

        const infoHeadingDiv = document.createElement('div');
        infoHeadingDiv.classList.add('infoHeading');

        const authorInfoDiv = document.createElement('div');
        authorInfoDiv.classList.add('authorInfo');

        const authorImage = document.createElement('img');
        authorImage.src = document.getElementsByName('imgPath')[0].value;
        authorImage.alt = 'User';
        authorImage.width = 50;
        authorImage.height = 50;
        authorInfoDiv.appendChild(authorImage);

        const authorHeading = document.createElement('h3');
        authorHeading.textContent = document.getElementsByName('author_username')[0].value;
        authorInfoDiv.appendChild(authorHeading);

        infoHeadingDiv.appendChild(authorInfoDiv);

        const dateParagraph = document.createElement('p');
        dateParagraph.textContent = new Date().toISOString().slice(0, 19).replace('T', ' ');
        infoHeadingDiv.appendChild(dateParagraph);

        responseDiv.appendChild(infoHeadingDiv);

        const contentBoxDiv = document.createElement('div');
        contentBoxDiv.classList.add('contentBox');

        const fieldset = document.createElement('fieldset');
        const legend = document.createElement('legend');
        legend.textContent = 'Answer';
        fieldset.appendChild(legend);


        const responseParagraph = document.createElement('p');
        responseParagraph.textContent = document.getElementById('comment').value;

        contentBoxDiv.appendChild(fieldset);
        responseDiv.appendChild(contentBoxDiv);
        if (document.getElementById('comment').value.includes("#")) {
            const faq_link = document.createElement("a");
            faq_link.href = "faq.php";
            faq_link.textContent = document.getElementById('comment').value;
            responseParagraph.textContent = "Answer: ";
            responseParagraph.appendChild(faq_link);
        }

        fieldset.appendChild(responseParagraph);

        ticketResponses.appendChild(responseDiv);
        document.getElementById('comment').value = '';
        if (isResponseVisible) {
            document.getElementById('responseDiv').style.display = 'block'
        }
    } else {
        alert(res);
    }
});

if(statusForm !== null) {
    statusForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const response = await fetch('../actions/action_change_status.php?ticket_id=' + document.getElementsByName('ticket_id')[0].value + '&ticket_status=' + document.getElementById('status').value);
        const res = await response.json();
        if (res === '') {
            const agentButton = document.getElementById('agentEdit');
            const agentForm = document.getElementById('agentChangeForm');
            statusName.textContent = document.getElementById('status').value;
            if (document.getElementById('status').value === 'Open') {
                document.getElementById('responseBox').style.display = 'block';
                document.querySelectorAll('.edit:not(#agentEdit)').forEach(button => {
                    button.style.display = 'block';
                });
                statusName.style.color = 'green';
                agentButton.style.display = 'none';
                if (agentForm.style.display === 'block') {
                    agentForm.style.display = 'none';
                }
                const response2 = await fetch('../actions/action_change_agent.php?ticket_id=' + document.getElementsByName('ticket_id')[0].value + '&agent=None');
                const res2 = await response2.json();
                if (res2 === '') {
                    agentName.textContent = 'Agent: ';
                }
            } else {
                document.querySelectorAll('.edit').forEach(button => {
                    button.style.display = 'block';
                });
                if (document.getElementById('status').value === 'Closed') {
                    document.getElementById('responseForm').style.display = 'none';
                    statusName.style.color = 'red';
                    document.querySelectorAll('.editForm').forEach(form => {
                        form.style.display = 'none';
                    });
                    document.querySelectorAll('.edit:not(#statusEdit,#deleteTicket)').forEach(button => {
                        button.style.display = 'none';
                    });

                } else {
                    document.getElementById('responseForm').style.display = 'block';
                    statusName.style.color = '#be9801';
                }
            }
            statusForm.style.display = 'none';
            updateLogs();
        }
    });
}

if (agentForm !== null) {
    agentForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const response = await fetch('../actions/action_change_agent.php?ticket_id=' + document.getElementsByName('ticket_id')[0].value + '&agent=' + encodeURIComponent(document.getElementById('agent').value));
        const res = await response.json();
        if (res === '') {
            agentName.textContent = "Agent: " + document.getElementById('agent').value;
            agentForm.style.display = 'none';
        }

        updateLogs();
    });

}
if(departmentForm !== null) {
    departmentForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const response = await fetch('../actions/action_change_department.php?ticket_id=' + document.getElementsByName('ticket_id')[0].value + '&department=' + encodeURIComponent(document.getElementById('department').value));
        const res = await response.json();
        if (res === '') {
            const selectedDepartment = document.getElementById('department').value;
            departmentName.textContent = (selectedDepartment).length > 15 ? (selectedDepartment).substring(0, 15) + "..." : selectedDepartment;
            fetch('../api/get_agentsByDep.php?department=' + encodeURIComponent(selectedDepartment) + '&ticket_id=' + document.getElementsByName('ticket_id')[0].value)
                .then(response => response.json())
                .then(data => {
                        const agentButton = document.getElementById('agentEdit');
                        const agentForm = document.getElementById('agentChangeForm');

                        statusName.textContent = 'Open';
                        statusName.style.color = 'green';
                        agentButton.style.display = 'none';
                        if (agentForm.style.display === 'block') {
                            agentForm.style.display = 'none';
                        }
                        agentName.textContent = "Agent: ";

                        const agentSelect = document.getElementById('agent');
                        agentSelect.innerHTML = '';
                        for (const agent of data) {
                            const option = document.createElement('option');
                            option.value = agent['agent_username'];
                            option.textContent = agent['agent_username'];
                            agentSelect.appendChild(option);
                        }
                    }
                );
            departmentForm.style.display = 'none';
        }

        updateLogs();
    });

}
if (priorityForm !== null) {
    priorityForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const response = await fetch('../actions/action_change_priority.php?ticket_id=' + document.getElementsByName('ticket_id')[0].value + '&priority=' + document.getElementById('priority').value);
        const res = await response.json();
        if (res === '') {
            priorityName.textContent = 'Priority: ' + document.getElementById('priority').value;
            priorityForm.style.display = 'none';
        }
        updateLogs();
    });
}


async function updateLogs() {
    const logs = document.querySelector(".log-list");

    const ticket_id = document.querySelector("#ticketId").value;

    const response = await fetch("../api/get_logs.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: encodeForAjax({
            id: ticket_id,
        }),
    });

    const data = await response.json();


    logs.innerHTML = "";

    for (const log of data) {
        const logElement = document.createElement("li");
        logElement.classList.add("log-item");

        const logDate = document.createElement("p");
        logDate.classList.add("log-date");

        logDate.innerHTML = log.date;

        const logContent = document.createElement("p");
        logContent.classList.add("log-content");

        logContent.innerHTML = log.content;

        logElement.appendChild(logContent);
        logElement.appendChild(logDate);

        logs.prepend(logElement);
        if (!isResponseVisible) {
            document.getElementById("logsDiv").style.display = "block";
        }
    }
}

const responseForm = document.getElementById('comment');

responseForm.addEventListener('input', async () => {
    const content = responseForm.value;

    if (content.includes('#')) {
        const response = await fetch('../api/get_faq.php', {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
        });

        const data = await response.json();

        const faq = document.getElementById('faq');

        faq.innerHTML = "";

        for (const qa of data) {
            const question = qa.question;
            const answer = qa.answer;

            const option = document.createElement('option');
            option.value = "#" + question;
            option.innerHTML = answer;

            faq.appendChild(option);
        }
    }
});

const editButton = document.querySelectorAll('.edit:not(#hashtagEdit)');
editButton.forEach(button => {
    button.addEventListener('click', async () => {
        const editForm = button.parentElement.querySelector('.editForm');

        if (editForm !== null) {
            if (editForm.style.display === 'none') {
            editForm.style.display = 'block';
        } else {
            editForm.style.display = 'none';
        }
            }
    });
});


const toggleB = document.querySelector('.toggleB');
const responseBox = document.getElementById('responseDiv');
const logBox = document.getElementById('logsDiv');
const leftP = document.getElementById('responseToggle');
const rightP = document.getElementById('logToggle');
toggleB.addEventListener('change', async () => {
    if (isResponseVisible) {
        logBox.style.display = 'block';
        responseBox.style.display = 'none';
        leftP.style.color = '#252525';
        rightP.style.color = 'white';
        leftP.style.background = 'white';
        rightP.style.background = '#252525';

    } else {
        logBox.style.display = 'none';
        responseBox.style.display = 'block';
        leftP.style.color = 'white';
        rightP.style.color = '#252525';
        leftP.style.background = '#252525';
        rightP.style.background = 'white';
    }

    isResponseVisible = !isResponseVisible;
});

const hashtags = document.querySelectorAll('.tag');
const hashtagEdit = document.getElementById('hashtagEdit');
const editForm = document.getElementById('hashtagChangeForm');

hashtags.forEach(tag => {
    tag.addEventListener('click', removeHashtag);
});

function removeHashtag(event) {
    const hashtagText = event.target.innerText;
    const ticketId = document.getElementsByName('ticket_id')[0].value;
    if (editForm.style.display !== 'none') {
        fetch('../api/get_ticket_hashtags.php?hashtag=' + encodeURIComponent(hashtagText) + '&ticket_id=' + ticketId + '&action=remove')
            .then(response => response.json())
            .then(data => {
                if (data === '') {
                    event.target.parentNode.removeChild(event.target);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

if(hashtagEdit !== null) {
    hashtagEdit.addEventListener('click', async () => {
        if (editForm !== null) {
            if (editForm.style.display === 'none') {
                editForm.style.display = 'block';
                hashtags.forEach(tag => {
                    tag.style.cursor = 'pointer';
                });
            } else {
                editForm.style.display = 'none';
                hashtags.forEach(tag => {
                    tag.style.cursor = 'default';
                });
            }
        }
    });
}


const hashtagDiv = document.getElementById('hashtagDiv');

if(editForm !== null) {
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        document.getElementById("result").style.display = "none";
        const ticketId = document.getElementsByName('ticket_id')[0].value;
        let hashtag = document.getElementById('hashtagInput').value;
        fetch('../api/get_ticket_hashtags.php?hashtag=' + encodeURIComponent(hashtag) + '&ticket_id=' + ticketId + '&action=add')
            .then(response => response.json())
            .then(data => {
                if(data === 'alreadyExists' || data === 'empty') {
                    document.getElementById('hashtagInput').value = '';
                    return;
                }
                const tag = document.createElement('span');
                tag.classList.add('tag');
                tag.innerHTML = '#' + data;
                tag.addEventListener('click', removeHashtag);
                hashtagDiv.appendChild(tag);
                tag.style.cursor = 'pointer';
                document.getElementById('hashtagInput').value = '';
            });
    });
}

const deleteButton = document.getElementById('deleteTicket');


if(deleteButton != null){deleteButton.addEventListener('click', deleteTicket);}

function deleteTicket() {
    const ticketId = document.getElementsByName('ticket_id')[0].value;
    fetch('../actions/action_delete_ticket.php?ticket_id=' + ticketId)
        .then(response => response.json())
        .then(data => {
            if (data === true) {
                window.location.replace('../pages/ticketPage.php');
            } else {
                alert('Something went wrong!');
            }
        });
}

