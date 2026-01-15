function setError(id, msg) {
  const el = document.getElementById(id);
  if (el) el.textContent = msg || "";
}

function validateEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      const email = loginForm.elements["email"].value.trim();
      const password = loginForm.elements["password"].value;

      if (!validateEmail(email)) {
        e.preventDefault();
        setError("loginError", "Please enter a valid email.");
        return;
      }
      if (!password) {
        e.preventDefault();
        setError("loginError", "Please enter your password.");
        return;
      }

      setError("loginError", "");
    });
  }

  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
      const email = registerForm.elements["email"].value.trim();
      const password = registerForm.elements["password"].value;
      const confirm = registerForm.elements["confirm_password"].value;

      if (!validateEmail(email)) {
        e.preventDefault();
        setError("registerError", "Please enter a valid email.");
        return;
      }
      if (!password || password.length < 8) {
        e.preventDefault();
        setError("registerError", "Password must be at least 8 characters.");
        return;
      }
      if (password !== confirm) {
        e.preventDefault();
        setError("registerError", "Passwords do not match.");
        return;
      }

      setError("registerError", "");
    });
  }
});
