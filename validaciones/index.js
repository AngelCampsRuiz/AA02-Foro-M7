function toggleForms() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    
    if (loginForm.classList.contains("visible")) {
        loginForm.classList.remove("visible");
        loginForm.classList.add("hidden");
        registerForm.classList.remove("hidden");
        registerForm.classList.add("visible");
    } else {
        registerForm.classList.remove("visible");
        registerForm.classList.add("hidden");
        loginForm.classList.remove("hidden");
        loginForm.classList.add("visible");
    }
}
