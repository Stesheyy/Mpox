$("#loginForm").submit(function (e) {
    e.preventDefault();

    const email = $("#email").val();
    const password = $("#password").val();
    const role = $("#role").val();

    $.ajax({
        type: "POST",
        url: "php/login.php",
        data: { email, password, role },
        dataType: "json",
        success: function (response) {
            console.log(response); // Log the response to the console
            if (response.success) {
                // Redirect to the appropriate dashboard
                window.location.href = "php/" + response.redirect;
            } else {
                // Show error message
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error: " + status + ": " + error); // Log AJAX error details
            alert("An unexpected error occurred. Please try again.");
        },
    });
});
