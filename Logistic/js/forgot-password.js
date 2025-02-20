
  document.addEventListener("DOMContentLoaded", function () {
    var togglePassword = document.getElementById("togglePassword");
    var toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    var passwordInput = document.getElementById("password_reg");
    var confirmPasswordInput = document.getElementById("confirm_password");
    var passwordMismatch = document.getElementById("passwordMismatch");
    var forgotForm = document.getElementById("forgotform");

    togglePassword.addEventListener("click", function () {
      togglePasswordVisibility(passwordInput, togglePassword);
    });

    toggleConfirmPassword.addEventListener("click", function () {
      togglePasswordVisibility(confirmPasswordInput, toggleConfirmPassword);
    });

    forgotForm.addEventListener("submit", function (e) {
      var password = passwordInput.value;
      var confirmPassword = confirmPasswordInput.value;

      if (password !== confirmPassword) {
        e.preventDefault();
        passwordMismatch.style.display = "block";
      }
    });

    function togglePasswordVisibility(inputField, toggleButton) {
      if (inputField.type === "password") {
        inputField.type = "text";
        toggleButton.querySelector("i").classList.remove("fa-eye-slash");
        toggleButton.querySelector("i").classList.add("fa-eye");
      } else {
        inputField.type = "password";
        toggleButton.querySelector("i").classList.remove("fa-eye");
        toggleButton.querySelector("i").classList.add("fa-eye-slash");
      }
    }
  });

  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("forgotform").addEventListener("submit", function (e) {
      e.preventDefault();

      let email = document.getElementById("email").value;
      let password = document.getElementById("password_reg").value;
      let confirmPassword = document.getElementById("confirm_password").value;

      // Check if passwords match
      if (password !== confirmPassword) {
        document.getElementById("passwordMismatch").style.display = "block";
        return;
      } else {
        document.getElementById("passwordMismatch").style.display = "none";
      }

      let data = new FormData();
      data.append('email', email);
      data.append('password', password);

      fetch('controller/update-rider-password.php', {
        method: 'POST',
        body: data,
      })
      .then(response => response.text())
      .then(response => {
        if (response === 'success') {
          alert('Password updated successfully');
          window.location.href = 'https://project-qdexpress.netlify.app';
        } else {
          document.getElementById("errorMessage").innerHTML = response;
          document.getElementById("errorMessage").style.display = "block";
        }
      })
      .catch(error => {
        // Handle error
        console.error(error);
      });
    });
  });
