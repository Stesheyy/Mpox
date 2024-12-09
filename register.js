document.getElementById('registerForm').addEventListener('submit', (event) => {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const role = document.getElementById('role').value;

    if (!name || !email || !password || !role) {
        event.preventDefault(); // Stop submission
        alert('Please fill in all fields.');
    }

    // Optional: Add regex checks for email or password strength here
});
