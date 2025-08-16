<?php
session_start();
require_once 'security.php';

// If already logged in, redirect to landpage
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    header('Location: landpage.php');
    exit();
}

// Get error message if any
$error_message = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'access_denied':
            $error_message = 'Access denied. Admin privileges required.';
            break;
        case 'session_expired':
            $error_message = 'Session expired. Please login again.';
            break;
        default:
            $error_message = 'An error occurred. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SLATE System</title>
  <style>
    /* Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      color: white;
      line-height: 1.6;
    }

    /* Layout Components */
    .main-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .login-container {
      width: 100%;
      max-width: 75rem;
      display: flex;
      background: rgba(31, 42, 56, 0.8);
      border-radius: 0.75rem;
      overflow: hidden;
      box-shadow: 0 0.625rem 1.875rem rgba(0, 0, 0, 0.3);
    }

    /* Welcome Panel */
    .welcome-panel {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2.5rem;
      background: linear-gradient(135deg, rgba(0, 114, 255, 0.2), rgba(0, 198, 255, 0.2));
    }

    .welcome-panel h1 {
      font-size: 2.25rem;
      font-weight: 700;
      color: #ffffff;
      text-shadow: 0.125rem 0.125rem 0.5rem rgba(0, 0, 0, 0.6);
      text-align: center;
    }

    /* Login Panel */
    .login-panel {
      width: 25rem;
      padding: 3.75rem 2.5rem;
      background: rgba(22, 33, 49, 0.95);
    }

    .login-box {
      width: 100%;
      text-align: center;
    }

    .login-box img {
      width: 6.25rem;
      height: auto;
      margin-bottom: 1.25rem;
    }

    .login-box h2 {
      margin-bottom: 1.5625rem;
      color: #ffffff;
      font-size: 1.75rem;
    }

    /* Error Message */
    .error-message {
      background: rgba(220, 53, 69, 0.2);
      border: 1px solid rgba(220, 53, 69, 0.5);
      border-radius: 0.375rem;
      padding: 0.75rem;
      margin-bottom: 1.25rem;
      color: #ff6b6b;
      font-size: 0.875rem;
      display: none;
    }

    .error-message.show {
      display: block;
    }

    /* Form Elements */
    .login-box form {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }

    .login-box input {
      width: 100%;
      padding: 0.75rem;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 0.375rem;
      color: white;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .login-box input:focus {
      outline: none;
      border-color: #00c6ff;
      box-shadow: 0 0 0 0.125rem rgba(0, 198, 255, 0.2);
    }

    .login-box input::placeholder {
      color: rgba(160, 160, 160, 0.8);
    }

    .login-box button {
      padding: 0.75rem;
      background: linear-gradient(to right, #0072ff, #00c6ff);
      border: none;
      border-radius: 0.375rem;
      font-weight: 600;
      font-size: 1rem;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .login-box button:hover {
      background: linear-gradient(to right, #0052cc, #009ee3);
      transform: translateY(-0.125rem);
      box-shadow: 0 0.3125rem 0.9375rem rgba(0, 0, 0, 0.2);
    }

    .login-box button:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    /* Loading spinner */
    .spinner {
      display: none;
      width: 1rem;
      height: 1rem;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-right: 0.5rem;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 1.25rem;
      background: rgba(0, 0, 0, 0.2);
      color: rgba(255, 255, 255, 0.7);
      font-size: 0.875rem;
    }

    /* Responsive Design */
    @media (max-width: 48rem) {
      .login-container {
        flex-direction: column;
      }

      .welcome-panel, 
      .login-panel {
        width: 100%;
      }

      .welcome-panel {
        padding: 1.875rem 1.25rem;
      }

      .welcome-panel h1 {
        font-size: 1.75rem;
      }

      .login-panel {
        padding: 2.5rem 1.25rem;
      }
    }

    @media (max-width: 30rem) {
      .main-container {
        padding: 1rem;
      }

      .welcome-panel h1 {
        font-size: 1.5rem;
      }

      .login-box h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="main-container">
    <div class="login-container">
      <div class="welcome-panel">
        <h1>FREIGHT MANAGEMENT SYSTEM</h1>
      </div>

      <div class="login-panel">
        <div class="login-box">
          <img src="slatelogo.png" alt="SLATE Logo">
          <h2>SLATE Login</h2>
          
          <!-- Error Message -->
          <div id="errorMessage" class="error-message">
            <span id="errorText"></span>
          </div>
          
          <form id="loginForm" onsubmit="handleLogin(event)">
            <input type="hidden" name="action" value="login">
            <input type="text" name="username" id="username" placeholder="Username" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <button type="submit" id="loginButton">
              <span class="spinner" id="spinner"></span>
              <span id="buttonText">Log In</span>
            </button>
          </form>
          
          <div style="margin-top: 1rem; font-size: 0.75rem; color: rgba(255, 255, 255, 0.6);">
            Default: admin / admin123
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer>
    &copy; <span id="currentYear"></span> SLATE Freight Management System. All rights reserved.
  </footer>

  <script>
    // Add current year to footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();
    
    // Show error message if present
    <?php if (!empty($error_message)): ?>
    document.getElementById('errorText').textContent = '<?php echo addslashes($error_message); ?>';
    document.getElementById('errorMessage').classList.add('show');
    <?php endif; ?>
    
    // Handle login form submission
    async function handleLogin(event) {
      event.preventDefault();
      
      const form = event.target;
      const button = document.getElementById('loginButton');
      const spinner = document.getElementById('spinner');
      const buttonText = document.getElementById('buttonText');
      const errorMessage = document.getElementById('errorMessage');
      const errorText = document.getElementById('errorText');
      
      // Get form data
      const formData = new FormData(form);
      
      // Show loading state
      button.disabled = true;
      spinner.style.display = 'inline-block';
      buttonText.textContent = 'Logging in...';
      errorMessage.classList.remove('show');
      
      try {
        const response = await fetch('auth.php', {
          method: 'POST',
          body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
          // Success - redirect to landpage
          window.location.href = 'landpage.php';
        } else {
          // Show error message
          errorText.textContent = result.message || 'Login failed. Please try again.';
          errorMessage.classList.add('show');
          
          // Clear password field
          document.getElementById('password').value = '';
          document.getElementById('password').focus();
        }
      } catch (error) {
        // Network error
        errorText.textContent = 'Network error. Please check your connection and try again.';
        errorMessage.classList.add('show');
      } finally {
        // Reset button state
        button.disabled = false;
        spinner.style.display = 'none';
        buttonText.textContent = 'Log In';
      }
    }
    
    // Clear error message when user starts typing
    document.getElementById('username').addEventListener('input', function() {
      document.getElementById('errorMessage').classList.remove('show');
    });
    
    document.getElementById('password').addEventListener('input', function() {
      document.getElementById('errorMessage').classList.remove('show');
    });
  </script>
</body>
</html>