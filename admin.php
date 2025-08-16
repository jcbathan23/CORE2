<?php
require_once 'auth.php';
requireAdmin(); // Only admin users can access this page
require_once 'security.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin | CORE II</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --sidebar-width: 280px;
      --primary-color: #4e73df;
      --secondary-color: #f8f9fc;
      --dark-bg: #1a1a2e;
      --dark-card: #16213e;
      --text-light: #f8f9fa;
      --text-dark: #212529;
      --success-color: #1cc88a;
      --info-color: #36b9cc;
      --warning-color: #f6c23e;
      --danger-color: #e74a3b;
      --border-radius: 0.75rem;
      --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
      overflow-x: hidden;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      color: var(--text-dark);
      transition: all 0.3s;
      min-height: 100vh;
    }

    body.dark-mode {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
      color: var(--text-light);
    }

    /* Modern Sidebar */
    .sidebar {
      width: var(--sidebar-width);
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
      color: white;
      padding: 0;
      transition: all 0.3s ease;
      z-index: 1000;
      transform: translateX(0);
      box-shadow: 4px 0 20px rgba(0,0,0,0.1);
      backdrop-filter: blur(10px);
    }

    .sidebar.collapsed {
      transform: translateX(-100%);
    }

    .sidebar .logo {
      padding: 2rem 1.5rem;
      text-align: center;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(10px);
    }

    .sidebar .logo img {
      max-width: 100%;
      height: auto;
      filter: brightness(1.1);
    }

    .system-name {
      padding: 1rem 1.5rem;
      font-size: 1.1rem;
      font-weight: 700;
      color: rgba(255,255,255,0.95);
      text-align: center;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      margin-bottom: 1.5rem;
      background: rgba(255,255,255,0.03);
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .sidebar-nav {
      padding: 0 1rem;
    }

    .sidebar-nav .nav-item {
      margin-bottom: 0.5rem;
    }

    .sidebar-nav .nav-link {
      display: flex;
      align-items: center;
      color: rgba(255,255,255,0.8);
      padding: 1rem 1.25rem;
      text-decoration: none;
      border-radius: 0.75rem;
      transition: all 0.3s ease;
      font-weight: 500;
      border: 1px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .sidebar-nav .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
      transition: left 0.5s;
    }

    .sidebar-nav .nav-link:hover::before {
      left: 100%;
    }

    .sidebar-nav .nav-link:hover {
      background: rgba(255,255,255,0.1);
      color: white;
      border-color: rgba(255,255,255,0.2);
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .sidebar-nav .nav-link.active {
      background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
      color: white;
      border-color: rgba(255,255,255,0.3);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .sidebar-nav .nav-link i {
      margin-right: 0.75rem;
      font-size: 1.1rem;
      width: 20px;
      text-align: center;
    }
    
    .sidebar-nav .nav-link .peso-icon {
      display: inline-block;
      margin-right: 0.75rem;
      font-size: 1.1rem;
      width: 20px;
      text-align: center;
      font-weight: 700;
    }

    .admin-feature {
      background: rgba(0,0,0,0.1);
      border-left: 3px solid rgba(255,255,255,0.3);
    }

    .admin-feature:hover {
      background: rgba(0,0,0,0.2);
      border-left-color: rgba(255,255,255,0.6);
    }

    .sidebar-footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      padding: 1rem;
      border-top: 1px solid rgba(255,255,255,0.1);
      background: rgba(0,0,0,0.1);
      backdrop-filter: blur(10px);
    }

    .sidebar-footer .nav-link {
      justify-content: center;
      padding: 0.75rem;
      border-radius: 0.5rem;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-footer .nav-link:hover {
      background: rgba(255,255,255,0.1);
      border-color: rgba(255,255,255,0.2);
    }

    /* Content Area */
    .content {
      margin-left: var(--sidebar-width);
      padding: 2rem;
      transition: all 0.3s ease;
    }

    .content.expanded {
      margin-left: 0;
    }

    /* Header */
    .header {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(20px);
      padding: 1.5rem 2rem;
      border-radius: var(--border-radius);
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      margin-bottom: 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .dark-mode .header {
      background: rgba(22, 33, 62, 0.9);
      color: var(--text-light);
      border: 1px solid rgba(255,255,255,0.1);
    }

    .hamburger {
      display: none;
      font-size: 1.5rem;
      cursor: pointer;
      background: none;
      border: none;
      color: inherit;
    }

    .system-title {
      color: var(--primary-color);
      font-weight: 600;
    }

    .header-controls {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .theme-toggle-container {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .theme-label {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--text-dark);
    }

    .dark-mode .theme-label {
      color: var(--text-light);
    }

    .theme-switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 24px;
    }

    .theme-switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: 0.3s;
      border-radius: 24px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: 0.3s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: var(--primary-color);
    }

    input:checked + .slider:before {
      transform: translateX(26px);
    }

    /* Cards */
    .card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(20px);
      border-radius: var(--border-radius);
      padding: 1.5rem;
      box-shadow: var(--shadow);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
    }

    .dark-mode .card {
      background: rgba(22, 33, 62, 0.9);
      color: var(--text-light);
      border: 1px solid rgba(255,255,255,0.1);
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 2rem rgba(0,0,0,0.15);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar.show {
        transform: translateX(0);
      }
      
      .content {
        margin-left: 0;
      }
      
      .hamburger {
        display: block;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="logo">
      <img src="slatelogo.png" alt="SLATE Logo">
    </div>
    <div class="system-name">CORE II</div>
    <div class="sidebar-nav">
      <div class="nav-item">
        <a href="landpage.php" class="nav-link">
          <i class="bi bi-speedometer2"></i>
          Dashboard
        </a>
      </div>
      <div class="nav-item">
        <a href="service-provider.php" class="nav-link">
          <i class="bi bi-people"></i>
          Service Provider
        </a>
      </div>
      <div class="nav-item">
        <a href="service-network.php" class="nav-link">
          <i class="bi bi-diagram-3"></i>
          Service Network & Route Planner
        </a>
      </div>
      <div class="nav-item">
        <a href="rate-tariff.php" class="nav-link admin-feature">
          <span class="peso-icon">₱</span>
          Rate & Tariff
        </a>
      </div>
      <div class="nav-item">
        <a href="sop-manager.php" class="nav-link admin-feature">
          <i class="bi bi-journal-text"></i>
          SOP Manager
        </a>
      </div>
      <div class="nav-item">
        <a href="schedules.php" class="nav-link">
          <i class="bi bi-calendar-week"></i>
          Schedules & Transit Timetable
        </a>
      </div>
    </div>
    <div class="sidebar-footer">
      <a href="auth.php?logout=1" class="nav-link">
        <i class="bi bi-box-arrow-right"></i>
        Logout
      </a>
    </div>
  </div>

  <div class="content" id="mainContent">
    <div class="header">
      <div class="hamburger" id="hamburger">☰</div>
      <div>
        <h1>Admin<span class="system-title">| CORE II</span></h1>
      </div>
      <div class="header-controls">
        <a href="admin-users.php" class="btn btn-outline-primary btn-sm me-2">
          <i class="bi bi-shield-lock"></i>
          Admin
        </a>
        <div class="theme-toggle-container">
          <span class="theme-label">Dark Mode</span>
          <label class="theme-switch">
            <input type="checkbox" id="themeToggle">
            <span class="slider"></span>
          </label>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <h3>Admin Accounts</h3>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Last Login</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="usersTableBody">
                <!-- User data will be loaded here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card">
          <h3>Change Password</h3>
          <form id="changePasswordForm">
            <div class="mb-3">
              <label for="currentPassword" class="form-label">Current Password</label>
              <input type="password" class="form-control" id="currentPassword" required>
            </div>
            <div class="mb-3">
              <label for="newPassword" class="form-label">New Password</label>
              <input type="password" class="form-control" id="newPassword" required>
            </div>
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm New Password</label>
              <input type="password" class="form-control" id="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
          </form>
        </div>
        
        <div class="card mt-3">
          <h3>Security Information</h3>
          <ul class="list-unstyled">
            <li><strong>Session Timeout:</strong> 8 hours</li>
            <li><strong>Login Attempts:</strong> 5 before lockout</li>
            <li><strong>Lockout Duration:</strong> 15 minutes</li>
            <li><strong>Current Admin:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></li>
            <li><strong>Role:</strong> <?php echo htmlspecialchars($_SESSION['role']); ?></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Sidebar toggle
    document.getElementById('hamburger').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('show');
    });

    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;
    
    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    if (currentTheme === 'dark') {
      body.classList.add('dark-mode');
      themeToggle.checked = true;
    }
    
    // Theme toggle event listener
    themeToggle.addEventListener('change', function() {
      if (this.checked) {
        body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
      } else {
        body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
      }
    });

    // Load users
    async function loadUsers() {
      try {
        const response = await fetch('api/users.php');
        const users = await response.json();
        
        const tbody = document.getElementById('usersTableBody');
        tbody.innerHTML = '';
        
        users.forEach(user => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${user.id}</td>
            <td>${user.username}</td>
            <td>${user.email}</td>
            <td><span class="badge bg-${user.role === 'admin' ? 'danger' : 'secondary'}">${user.role}</span></td>
            <td><span class="badge bg-${user.is_active ? 'success' : 'warning'}">${user.is_active ? 'Active' : 'Inactive'}</span></td>
            <td>${user.last_login || 'Never'}</td>
            <td>
              <button class="btn btn-sm btn-outline-primary" onclick="editUser(${user.id})">Edit</button>
            </td>
          `;
          tbody.appendChild(row);
        });
      } catch (error) {
        console.error('Error loading users:', error);
      }
    }

    // Change password
    document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      const currentPassword = document.getElementById('currentPassword').value;
      const newPassword = document.getElementById('newPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      
      if (newPassword !== confirmPassword) {
        alert('New passwords do not match!');
        return;
      }
      
      if (newPassword.length < 6) {
        alert('Password must be at least 6 characters long!');
        return;
      }
      
      try {
        const response = await fetch('api/change-password.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            currentPassword: currentPassword,
            newPassword: newPassword
          })
        });
        
        const result = await response.json();
        
        if (result.success) {
          alert('Password changed successfully!');
          document.getElementById('changePasswordForm').reset();
        } else {
          alert(result.message || 'Failed to change password');
        }
      } catch (error) {
        alert('Error changing password');
      }
    });

    // Load users on page load
    loadUsers();
  </script>
</body>
</html>
