let selectedAccountType = "";

// Helper function to get users from localStorage
function getUsersFromStorage() {
    const usersJson = localStorage.getItem('farmUsers');
    try {
        return usersJson ? JSON.parse(usersJson) : {};
    } catch (e) {
        console.error("Error parsing users from localStorage:", e);
        return {}; // Return empty object on error
    }
}

// Helper function to save users to localStorage
function saveUsersToStorage(users) {
    try {
        localStorage.setItem('farmUsers', JSON.stringify(users));
    } catch (e) {
        console.error("Error saving users to localStorage:", e);
        // Optionally alert the user if storage fails (e.g., quota exceeded)
        alert("Could not save account data. Storage might be full.");
    }
}

function proceedToLogin() {
    const select = document.getElementById("account-type-select");
    const error = document.getElementById("account-type-error");
    selectedAccountType = select.value;

    if (!selectedAccountType) {
        error.classList.remove("hidden");
        return;
    }

    error.classList.add("hidden");
    document.getElementById("account-type-screen").classList.add("hidden");
    document.getElementById("login-screen").classList.remove("hidden");

    const loginSubtext = document.getElementById("login-subtext");
    if (selectedAccountType === "Farm Owner") {

        loginSubtext.innerText = "Log in as Farm Owner";
    } else {

        loginSubtext.innerText = "Log in as Care Taker";
    }
}

function showSignupScreen() {
    document.getElementById("login-screen").classList.add("hidden");
    document.getElementById("signup-screen").classList.remove("hidden");
    document.getElementById("signup-error").classList.add("hidden");
}

function showLoginScreen() {
    document.getElementById("signup-screen").classList.add("hidden");
    document.getElementById("login-screen").classList.remove("hidden");
    document.getElementById("login-error").classList.add("hidden");
}

function signup() {
    const usernameInput = document.getElementById("signup-username");
    const passwordInput = document.getElementById("signup-password");
    const confirmPasswordInput = document.getElementById("signup-confirm-password");
    const accountTypeSelect = document.getElementById("signup-account-type");
    const error = document.getElementById("signup-error");

    const username = usernameInput.value.trim();
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const accountType = accountTypeSelect.value;

    error.classList.add("hidden");

    if (!username || !password || !confirmPassword || !accountType) {
        error.innerText = "Please fill in all fields, including account type.";
        error.classList.remove("hidden");
        return;
    }
    if (password !== confirmPassword) {
        error.innerText = "Passwords do not match.";
        error.classList.remove("hidden");
        return;
    }

    // Get existing users from storage
    const users = getUsersFromStorage();

    // Check if username exists in localStorage or is a default user
    if (username === 'owner' || username === 'caretaker' || users[username]) {
        error.innerText = "Username already taken.";
        error.classList.remove("hidden");
        return;
    }

    // Add new user to the object
    users[username] = { password: password, type: accountType };

    // Save updated users object back to storage
    saveUsersToStorage(users);

    console.log("User signed up and saved:", username, "Type:", accountType);

    alert("Sign up successful! Please select your account type again to log in.");
    document.getElementById("signup-screen").classList.add("hidden");
    document.getElementById("account-type-screen").classList.remove("hidden");

    // Clear form fields
    usernameInput.value = '';
    passwordInput.value = '';
    confirmPasswordInput.value = '';
    accountTypeSelect.value = '';
}

function login() {
    const usernameInput = document.getElementById("login-username");
    const passwordInput = document.getElementById("login-password");
    const error = document.getElementById("login-error");
    const username = usernameInput.value;
    const password = passwordInput.value;

    if (!selectedAccountType) {
        alert("Error: No account type selected. Please refresh and start over.");
        window.location.reload();
        return;
    }
    error.classList.add("hidden");
    if (!username || !password) {
        error.innerText = "Please enter both username and password.";
        error.classList.remove("hidden");
        return;
    }

    // Get users from storage
    const users = getUsersFromStorage();

    let isValid = false;
    let loggedInRole = "";
    let loggedInUsername = "";

    // Check hardcoded default users first
    if (username === "owner" && password === "pass" && selectedAccountType === "Farm Owner") {
        isValid = true;
        loggedInRole = "Farm Owner";
        loggedInUsername = "owner";
    } else if (username === "caretaker" && password === "pass" && selectedAccountType === "Care Taker") {
        isValid = true;
        loggedInRole = "Care Taker";
        loggedInUsername = "caretaker";
    }
    // Check users from localStorage
    else if (users[username] &&
        users[username].password === password &&
        users[username].type === selectedAccountType) {
        isValid = true;
        loggedInRole = users[username].type;
        loggedInUsername = username;
    }

    if (!isValid) {
        error.innerText = "Invalid username, password, or account type. Check credentials and selection.";
        error.classList.remove("hidden");
        return;
    }

    // Store role in localStorage
    console.log('Attempting to store role:', loggedInRole);
    try {
        localStorage.setItem('currentUserRole', loggedInRole);
    } catch (e) {
        console.error("Error saving role to localStorage:", e);
    }

    document.getElementById("login-screen").classList.add("hidden");
    document.getElementById("app-container").classList.remove("hidden");
    document.body.classList.remove("auth-container");
    document.body.classList.add("bg-gray-100");
    updateUserInfo(loggedInUsername, loggedInRole);
    // Load initial module, passing path only
    loadModule('dashboard.html');
}

function updateUserInfo(username, role) {
    document.getElementById('user-name').textContent = username;
    document.getElementById('user-role').textContent = role;
}

function logout() {
    selectedAccountType = "";
    document.getElementById('login-username').value = '';
    document.getElementById('login-password').value = '';

    document.getElementById("app-container").classList.add("hidden");
    document.getElementById("account-type-screen").classList.remove("hidden");
    document.body.classList.add("auth-container");
    document.body.classList.remove("bg-gray-100");
    document.getElementById('content-frame').src = 'about:blank';
}

function loadModule(modulePath) {
    const iframe = document.getElementById('content-frame');
    if (iframe) {
        iframe.src = modulePath;
        // Set active link based on path
        setActiveLink(modulePath);
    } else {
        console.error("Content frame iframe not found.");
    }
}

function setActiveLink(modulePath) {
    const links = document.querySelectorAll('#sidebar nav a');
    let foundLink = null;
    links.forEach(link => {
        link.classList.remove('active');
        link.classList.remove('font-semibold');
        link.classList.remove('bg-opacity-20');
        // Check if the onclick attribute contains the module path
        const onclickAttr = link.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes(`loadModule('${modulePath}'`)) {
            foundLink = link;
        }
    });

    if (foundLink) {
        foundLink.classList.add('active');
        foundLink.classList.add('font-semibold');
        foundLink.classList.add('bg-opacity-20');
    } else {
        console.warn(`Sidebar link for module '${modulePath}' not found.`);
        // If loading the dashboard initially (the first link), activate it.
        if (modulePath === 'dashboard.html' && links.length > 0) {
            links[0].classList.add('active', 'font-semibold', 'bg-opacity-20');
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const sidebarLinks = document.querySelectorAll('#sidebar nav a');
    sidebarLinks.forEach(link => {
        const originalOnclick = link.getAttribute('onclick');
        if (originalOnclick && originalOnclick.includes('loadModule')) {
            // Extract the module path
            const match = originalOnclick.match(/loadModule\('([^']+)'/);
            if (match && match[1]) {
                const modulePath = match[1];
                // Remove the second argument 'this' from the call
                link.setAttribute('onclick', `loadModule('${modulePath}'); return false;`);
            }
        }
    });
});