const depOrAg = document.getElementById('showDepForm') != null;

const currentForm = depOrAg ? document.getElementById('modifyDeps') : document.getElementById('modifyUsers');
const showBtn = depOrAg ? document.getElementById('showDepForm') : document.getElementById('showAgForm');
const toggleBtn = depOrAg ? document.getElementById('add-rm') : document.getElementById('promote-demote');
const actionInput = document.getElementById('action_input');

const assignForm = depOrAg ? currentForm : document.getElementById('modifyUserDeps');
const showBtnAssign = depOrAg ? showBtn : document.getElementById('showAssignDepForm');
const toggleBtnAssign = depOrAg ? toggleBtn : document.getElementById('assign-unassign');
const actionInputAssign = depOrAg ? actionInput : document.getElementById('action_input_assign');

if(toggleBtn != null){
    toggleBtn.style.backgroundColor = 'green';
    toggleBtnAssign.style.backgroundColor = 'green';
    currentForm.style.display = 'none';
    assignForm.style.display = 'none';

}


if(showBtn != null){
    showBtn.addEventListener('click', function(e){
        e.preventDefault();
        if(currentForm.style.display === 'none'){
            currentForm.style.display = 'flex';
            currentForm.style.justifyContent = 'center';
            currentForm.style.alignItems = 'center';
            currentForm.style.marginBottom = '20px';
            showBtn.textContent = 'Hide form';
        } else {
            currentForm.style.display = 'none';
            showBtn.textContent = depOrAg ? 'Add/Remove Departments' : 'Promote/Demote Users';
        }

        if(!depOrAg){
            assignForm.style.display = 'none';
            showBtnAssign.textContent = 'Assign/Unassign Agent to Department';
        }
    });

}

if(toggleBtn != null){
toggleBtn.addEventListener('click', function(e){
    e.preventDefault();
    if(toggleBtn.textContent === 'Add'){
        toggleBtn.textContent = 'Remove';
        toggleBtn.style.backgroundColor = '#1464B5';
        actionInput.value ='remove';
    }
    else if(toggleBtn.textContent === 'Remove'){
        toggleBtn.textContent = 'Add';
        toggleBtn.style.backgroundColor = 'green';
        actionInput.value = 'add';
    }
    else if(toggleBtn.textContent === 'Promote'){
        toggleBtn.textContent = 'Demote';
        toggleBtn.style.backgroundColor = '#1464B5';
        actionInput.value ='demote';
    }
    else if(toggleBtn.textContent === 'Demote'){
        toggleBtn.textContent = 'Promote';
        toggleBtn.style.backgroundColor = 'green';
        actionInput.value = 'promote';
    }
});
}

if(showBtnAssign != null){
showBtnAssign.addEventListener('click', function(e){
    if(!depOrAg){
        e.preventDefault();
        if(assignForm.style.display === 'none'){
            assignForm.style.display = 'flex';
            assignForm.style.justifyContent = 'center';
            assignForm.style.alignItems = 'center';
            assignForm.style.marginBottom = '20px';
            showBtnAssign.textContent = 'Hide form';
        } else {
            assignForm.style.display = 'none';
            showBtnAssign.textContent = 'Assign/Unassign Agent to Department';
        }

        currentForm.style.display = 'none';
        showBtn.textContent = 'Promote/Demote Users';
    }
});}

if(toggleBtnAssign != null){
toggleBtnAssign.addEventListener('click', function(e){
    if(!depOrAg){
        e.preventDefault();
        if(toggleBtnAssign.textContent === 'Assign'){
            toggleBtnAssign.textContent = 'Unassign';
            toggleBtnAssign.style.backgroundColor = '#1464B5';
            actionInputAssign.value ='unassign';
        }
        else if(toggleBtnAssign.textContent === 'Unassign'){
            toggleBtnAssign.textContent = 'Assign';
            toggleBtnAssign.style.backgroundColor = 'green';
            actionInputAssign.value = 'assign';
        }
    }
});}
