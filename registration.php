<?php
session_start();

// DB config
$host = "localhost";
$user = "root";
$pass = "";
$db   = "task_manager";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];

    if ($action === 'register') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $adminCode = $_POST['adminCode'];
        $SECRET_ADMIN_CODE = "ADMIN123";

        if ($adminCode !== $SECRET_ADMIN_CODE) {
            echo json_encode(['success' => false, 'message' => 'Invalid admin code']); exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email']); exit;
        }
        $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute(); $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Email already registered']);
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
            $ins->bind_param("ss", $email, $hashed);
            if ($ins->execute()) echo json_encode(['success' => true, 'message' => 'Registration successful']);
            else echo json_encode(['success' => false, 'message' => 'Registration failed']);
        }
        $stmt->close();
        exit;
    }

    if ($action === 'login') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $stmt = $conn->prepare("SELECT id, password FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute(); $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hash); $stmt->fetch();
            if (password_verify($password, $hash)) {
                $_SESSION['admin_id'] = $id;
                echo json_encode(['success' => true, 'message' => 'Login successful']);
            } else echo json_encode(['success' => false, 'message' => 'Invalid password']);
        } else echo json_encode(['success' => false, 'message' => 'Admin not found']);
        $stmt->close();
        exit;
    }

    if ($action === 'logout') {
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logged out']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Authentication</title>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  body{
     background: linear-gradient(135deg, #ebadbe, #1E1E2F);
  }
  input[type="submit"] {
    width: 100%;
    background-color:#ebadbe;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
  }

  input[type="submit"]:hover {
    background-color: #0083b0;
  }

.hidden{display:none;}
.error-message{color:#ef4444;font-size:0.875rem;margin-top:0.25rem;}
.success-message{color:#10b981;font-size:0.875rem;margin-top:0.25rem;}
</style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md">
<div class="bg-white rounded-lg shadow-lg p-8">
<h1 id="auth-title" class="text-2xl font-bold text-center mb-6 text-gray-800">Welcome</h1>
<div id="auth-message" class="hidden text-center mb-4"></div>

<form id="signup-form" class="space-y-4">
  <div>
    <input type="email" id="signup-email" placeholder="Email" required class="w-full px-3 py-2 border rounded">
    <div id="signup-email-error" class="error-message hidden"></div>
  </div>
  <div>
    <input type="password" id="signup-password" placeholder="Password" required minlength="8" class="w-full px-3 py-2 border rounded">
    <div id="signup-password-error" class="error-message hidden"></div>
  </div>
  <div>
    <input type="password" id="signup-password-confirm" placeholder="Confirm Password" required class="w-full px-3 py-2 border rounded">
    <div id="signup-password-confirm-error" class="error-message hidden"></div>
  </div>
  <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded" onclick="openpage('index.html')">Register</button>
  <p class="text-center text-sm mt-2">Already have account? <a href="#" id="show-login" class="text-blue-600">Login</a></p>
</form>

<form id="login-form" class="space-y-4 hidden">
  <div>
    <input type="email" id="login-email" placeholder="Email" required class="w-full px-3 py-2 border rounded">
    <div id="login-email-error" class="error-message hidden"></div>
  </div>
  <div>
    <input type="password" id="login-password" placeholder="Password" required class="w-full px-3 py-2 border rounded">
    <div id="login-password-error" class="error-message hidden"></div>
  </div>
  <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Login</button>
  <p class="text-center text-sm mt-2">Don't have account? <a href="#" id="show-signup" class="text-blue-600">Sign up</a></p>
</form>

<div id="admin-dashboard" class="hidden text-center">
  <h2 class="text-xl font-bold mb-4">Welcome, Admin!</h2>
  <button id="logout-btn" class="bg-red-600 text-white px-4 py-2 rounded">Logout</button>
</div>
</div>
</div>

<script>
    function openpage(url){
        window.location.href=url;
    }
const signupForm = document.getElementById('signup-form');
const loginForm = document.getElementById('login-form');
const dashboard = document.getElementById('admin-dashboard');
const authTitle = document.getElementById('auth-title');
const authMessage = document.getElementById('auth-message');

document.getElementById('show-login').onclick = e => {
  e.preventDefault();
  signupForm.classList.add('hidden');
  loginForm.classList.remove('hidden');
  authTitle.textContent = 'Login';
  clearMessages();
};

document.getElementById('show-signup').onclick = e => {
  e.preventDefault();
  loginForm.classList.add('hidden');
  signupForm.classList.remove('hidden');
  authTitle.textContent = 'Register';
  clearMessages();
};

document.getElementById('logout-btn').onclick = async () => {
  await send('logout', {});
  dashboard.classList.add('hidden');
  loginForm.classList.remove('hidden');
  authTitle.textContent = 'Login';
};

signupForm.onsubmit = async e => {
  e.preventDefault(); clearMessages();
  const email = document.getElementById('signup-email').value.trim();
  const password = document.getElementById('signup-password').value;
  const confirm = document.getElementById('signup-password-confirm').value;
  const adminCode = document.getElementById('signup-admin-code').value.trim();

  if (password !== confirm) {
    showFieldError('signup-password-confirm-error', 'Passwords do not match');
    return;
  }

  const res = await send('register', { email, password, adminCode });
  showMsg(res);
  if (res.success) {
    signupForm.reset();
    signupForm.classList.add('hidden');
    loginForm.classList.remove('hidden');
    authTitle.textContent = 'Login';
  }
};

loginForm.onsubmit = async e => {
  e.preventDefault(); clearMessages();
  const email = document.getElementById('login-email').value.trim();
  const password = document.getElementById('login-password').value;

  const res = await send('login', { email, password });
  showMsg(res);
  if (res.success) {
    loginForm.reset();
    loginForm.classList.add('hidden');
    dashboard.classList.remove('hidden');
    authTitle.textContent = 'Dashboard';
  }
};

function clearMessages() {
  authMessage.className = 'hidden';
  authMessage.textContent = '';
  document.querySelectorAll('.error-message').forEach(el => el.classList.add('hidden'));
}

function showMsg(data) {
  authMessage.textContent = data.message;
  authMessage.classList.remove('hidden');
  authMessage.className = data.success ? 'success-message' : 'error-message';
}

function showFieldError(elementId, message) {
  const el = document.getElementById(elementId);
  el.textContent = message;
  el.classList.remove('hidden');
}

async function send(action, data) {
  data.action = action;
  const formData = new FormData();
  for (const key in data) formData.append(key, data[key]);
  const res = await fetch(location.href, { method: 'POST', body: formData });
  return await res.json();
}
</script>
</body>
</html>