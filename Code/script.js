document.addEventListener('DOMContentLoaded', function() {
    var addButton = document.getElementById('addButton');
    var addModal = document.getElementById('addModal');
    var closeButton = document.getElementById('closeButton');

    addButton.addEventListener('click', function() {
        addModal.style.display = 'block';
    });

    closeButton.addEventListener('click', function() {
        addModal.style.display = 'none';
    });

    // Hide/show password
var passwordEntries = document.getElementsByClassName('password-entry');
for(var i = 0; i < passwordEntries.length; i++) {
    var passwordHidden = passwordEntries[i].getElementsByClassName('password-hidden')[0];
    passwordHidden.addEventListener('click', function() {
        this.style.display = 'none';
        var passwordVisible = this.parentElement.getElementsByClassName('password-visible')[0];
        passwordVisible.style.display = 'inline';
    });
}

});
