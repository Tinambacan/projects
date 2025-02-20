function submitForm(event) {
    event.preventDefault();
    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;
    let xhr = new XMLHttpRequest();

    let formData = new FormData();
    formData.append('username', username);
    formData.append('password', password);

    xhr.open('POST', 'controller/controller_adminlogin.php', true); //
    
    xhr.onload = function () {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            console.log(response);
            if (response.status === 'success') {
                console.log('Login successful for user: ' + response.username);
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful!',
                    text: 'Welcome, ' + response.username + '!',
                    confirmButtonText: 'Continue'
                }).then(() => {
                    window.location.href = 'assign_delivery.php';
                });
                setTimeout(function () {
                    window.location.href = 'assign_delivery.php';
                }, 3000);
                username = "";
                password = "";
                window.history.pushState(null, '', window.location.href);
                window.addEventListener('popstate', function() {
                    window.history.pushState(null, '', window.location.href);
                });
            } else {
                console.log("Error Login: " + response.message);
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: response.message,
                    confirmButtonText: 'OK'
                });
            }
        } else {
            console.log("Error Login");
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again later.',
                confirmButtonText: 'OK'
            });
        }
    }
    xhr.send(formData);
    
}