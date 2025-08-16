<?php
require_once 'auth.php';
requireAdmin(); // Only admin users can access this page
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Service Provider | CORE II</title>
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
      --border-radius: 0.75rem;
      --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body { font-family: 'Inter', 'Segoe UI', system-ui, sans-serif; overflow-x: hidden; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); color: var(--text-dark); transition: all 0.3s; min-height: 100vh; }

    body.dark-mode { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: var(--text-light); }

    /* Modern Sidebar */
    .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; left: 0; top: 0; background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%); color: white; padding: 0; transition: all 0.3s ease; z-index: 1000; transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.1); backdrop-filter: blur(10px); }

    .sidebar.collapsed {
      transform: translateX(-100%);
    }

    .sidebar .logo { padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); backdrop-filter: blur(10px); }

    .sidebar .logo img {
      max-width: 100%;
      height: auto;
      filter: brightness(1.1);
    }

    .system-name { padding: 1rem 1.5rem; font-size: 1.1rem; font-weight: 700; color: rgba(255,255,255,0.95); text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 1.5rem; background: rgba(255,255,255,0.03); letter-spacing: 1px; text-transform: uppercase; }

    .sidebar-nav {
      padding: 0 1rem;
    }

    .sidebar-nav .nav-item {
      margin-bottom: 0.5rem;
    }

    .sidebar-nav .nav-link { display: flex; align-items: center; color: rgba(255,255,255,0.8); padding: 1rem 1.25rem; text-decoration: none; border-radius: 0.75rem; transition: all 0.3s ease; font-weight: 500; border: 1px solid transparent; position: relative; overflow: hidden; }
    .sidebar-nav .nav-link::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent); transition: left 0.5s; }
    .sidebar-nav .nav-link:hover::before { left: 100%; }

    .sidebar-nav .nav-link:hover { background: rgba(255,255,255,0.1); color: white; border-color: rgba(255,255,255,0.2); transform: translateX(5px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }

    .sidebar-nav .nav-link.active { background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05)); color: white; border-color: rgba(255,255,255,0.3); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }

    .sidebar-nav .nav-link i {
      margin-right: 0.75rem;
      font-size: 1.1rem;
      width: 20px;
      text-align: center;
    }
    .sidebar-nav .nav-link .peso-icon { display: inline-block; margin-right: 0.75rem; font-size: 1.1rem; width: 20px; text-align: center; font-weight: 700; }

    .admin-feature {
      background: rgba(0,0,0,0.1);
      border-left: 3px solid rgba(255,255,255,0.3);
    }

    .admin-feature:hover {
      background: rgba(0,0,0,0.2);
      border-left-color: rgba(255,255,255,0.6);
    }

    .sidebar-footer { position: absolute; bottom: 0; width: 100%; padding: 1rem; border-top: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.1); backdrop-filter: blur(10px); }

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

    .sidebar-footer .nav-link i {
      margin-right: 0;
    }

    /* Enhanced transitions */
    .sidebar.transitioning {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Hover effects for better UX */
    .sidebar-nav .nav-link:active {
      transform: scale(0.98);
      transition: transform 0.1s ease;
    }

    /* Main Content */
    .content { margin-left: var(--sidebar-width); padding: 2rem; transition: all 0.3s ease; min-height: 100vh; }

    .content.expanded {
      margin-left: 0;
    }

    /* Header */
    .header { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px); padding: 1.5rem 2rem; border-radius: var(--border-radius); box-shadow: 0 8px 32px rgba(0,0,0,0.1); margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; border: 1px solid rgba(255,255,255,0.2); }

    .dark-mode .header { background: rgba(22, 33, 62, 0.9); color: var(--text-light); border: 1px solid rgba(255,255,255,0.1); }

    .hamburger { font-size: 1.5rem; cursor: pointer; padding: 0.75rem; border-radius: 0.5rem; transition: all 0.3s; background: rgba(0,0,0,0.05); }
    .hamburger:hover { background: rgba(0,0,0,0.1); }

    .system-title { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 2.2rem; font-weight: 800; }

    /* Dashboard Cards */
    .dashboard-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }

    .card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2); border-radius: var(--border-radius); box-shadow: 0 8px 32px rgba(0,0,0,0.1); padding: 2rem; transition: all 0.3s; position: relative; overflow: hidden; }
    .card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .card:nth-child(2)::before { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .card:nth-child(3)::before { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .card:nth-child(4)::before { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

    .dark-mode .card { background: rgba(22, 33, 62, 0.9); color: var(--text-light); border: 1px solid rgba(255,255,255,0.1); }

    .card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }

    .stat-value { font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .card:nth-child(2) .stat-value { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .card:nth-child(3) .stat-value { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .card:nth-child(4) .stat-value { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

    /* Form Section */
    .form-section {
      background-color: white;
      padding: 1.5rem;
      border-radius: var(--border-radius);
      box-shadow: var(--shadow);
      margin-bottom: 1.5rem;
      display: none;
    }

    .dark-mode .form-section {
      background-color: var(--dark-card);
      color: var(--text-light);
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 0.5rem;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 1rem;
    }

    .dark-mode .form-group input,
    .dark-mode .form-group select,
    .dark-mode .form-group textarea {
      background-color: #2a3a5a;
      border-color: #3a4b6e;
      color: var(--text-light);
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 0.5rem;
      margin-top: 1rem;
    }

    /* Buttons */
    .btn {
      padding: 0.5rem 1rem;
      border: none;
      border-radius: 4px;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-primary {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-primary:hover {
      background-color: #3a5bc7;
    }

    .btn-secondary {
      background-color: #6c757d;
      color: white;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
    }

    .btn-success {
      background-color: var(--success-color);
      color: white;
    }

    .btn-info {
      background-color: var(--info-color);
      color: white;
    }

    .toggle-form-btn {
      background-color: var(--primary-color);
      color: white;
      margin-bottom: 1.5rem;
    }

    .toggle-form-btn:hover {
      background-color: #3a5bc7;
    }

    /* Table Section */
    .table-section { background: rgba(255,255,255,0.9); border: 1px solid rgba(255,255,255,0.2); border-radius: var(--border-radius); box-shadow: 0 8px 32px rgba(0,0,0,0.1); padding: 1.5rem; }
    .dark-mode .table-section { background: rgba(22, 33, 62, 0.9); color: var(--text-light); border: 1px solid rgba(255,255,255,0.1); }

    .dark-mode .table-section {
      background-color: var(--dark-card);
      color: var(--text-light);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 0.75rem;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    .dark-mode th,
    .dark-mode td {
      border-bottom-color: #3a4b6e;
    }

    thead {
      background-color: var(--primary-color);
      color: white;
    }

    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }

    /* Header Controls */
    .header-controls {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    /* Theme Toggle */
    .theme-toggle-container {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .theme-switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
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
      transition: .4s;
      border-radius: 34px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: var(--primary-color);
    }

    input:checked + .slider:before {
      transform: translateX(26px);
    }

    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        transform: translateX(-100%);
        box-shadow: 2px 0 20px rgba(0,0,0,0.3);
      }
      
      .sidebar.show {
        transform: translateX(0);
      }
      
      .content {
        margin-left: 0;
      }

      .sidebar-nav {
        padding: 0 0.75rem;
      }

      .sidebar-nav .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
      }

      .sidebar-nav .nav-link i {
        font-size: 1rem;
        margin-right: 0.5rem;
      }
    }

    @media (max-width: 576px) {
      .sidebar {
        width: 100%;
        max-width: 320px;
      }

      .sidebar-nav .nav-link {
        padding: 1rem;
        font-size: 1rem;
      }

      .sidebar-nav .nav-link i {
        font-size: 1.1rem;
        margin-right: 0.75rem;
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
        <a href="service-provider.php" class="nav-link active">
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
        <h1>Service Provider Management <span class="system-title">| CORE II </span></h1>
      </div>
      <div class="header-controls">
        <a href="admin.php" class="btn btn-outline-primary btn-sm me-2">
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

    <div class="dashboard-cards">
      <div class="card">
        <h3>Total Providers</h3>
        <div class="stat-value" id="totalProviders">0</div>
        <div class="stat-label">Active service providers</div>
      </div>

      <div class="card">
        <h3>Active Contracts</h3>
        <div class="stat-value" id="activeContracts">0</div>
        <div class="stat-label">Current agreements</div>
      </div>

      <div class="card">
        <h3>Service Categories</h3>
        <div class="stat-value" id="serviceCategories">0</div>
        <div class="stat-label">Available services</div>
      </div>

      <div class="card">
        <h3>Total Revenue</h3>
        <div class="stat-value" id="totalRevenue">₱0</div>
        <div class="stat-label">This month</div>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Service Provider Management</h3>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#providerModal" onclick="openAddModal()">
        <i class="bi bi-plus-circle"></i> Add New Service Provider
      </button>
    </div>

    <div class="table-section">
      <div class="table-responsive">
        <table id="providersTable" class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Type</th>
              <th>Contact Person</th>
              <th>Service Area</th>
              <th>Monthly Rate</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="providersTableBody">
            <!-- Provider data will be loaded here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Provider Modal -->
  <div class="modal fade" id="providerModal" tabindex="-1" aria-labelledby="providerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="providerModalLabel">Add New Service Provider</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="providerForm">
            <input type="hidden" id="providerId">
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="providerName" class="form-label">Provider Name *</label>
                  <input type="text" class="form-control" id="providerName" name="providerName" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="providerType" class="form-label">Provider Type *</label>
                  <select class="form-select" id="providerType" name="providerType" required>
                    <option value="">Select Type</option>
                    <option value="Transport">Transport</option>
                    <option value="Logistics">Logistics</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Security">Security</option>
                    <option value="Technology">Technology</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="contactPerson" class="form-label">Contact Person *</label>
                  <input type="text" class="form-control" id="contactPerson" name="contactPerson" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="contactEmail" class="form-label">Contact Email *</label>
                  <input type="email" class="form-control" id="contactEmail" name="contactEmail" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="contactPhone" class="form-label">Contact Phone *</label>
                  <input type="tel" class="form-control" id="contactPhone" name="contactPhone" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="serviceArea" class="form-label">Service Area *</label>
                  <input type="text" class="form-control" id="serviceArea" name="serviceArea" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="contractStart" class="form-label">Contract Start Date *</label>
                  <input type="date" class="form-control" id="contractStart" name="contractStart" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="contractEnd" class="form-label">Contract End Date *</label>
                  <input type="date" class="form-control" id="contractEnd" name="contractEnd" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="monthlyRate" class="form-label">Monthly Rate (PHP) *</label>
                  <input type="number" class="form-control" id="monthlyRate" name="monthlyRate" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="providerStatus" class="form-label">Status *</label>
                  <select class="form-select" id="providerStatus" name="providerStatus" required>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Pending">Pending</option>
                    <option value="Suspended">Suspended</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label for="providerNotes" class="form-label">Notes</label>
                  <textarea class="form-control" id="providerNotes" name="providerNotes" rows="3"></textarea>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="saveProvider()">Save Provider</button>
        </div>
      </div>
    </div>
  </div>

  <!-- View Provider Modal -->
  <div class="modal fade" id="viewProviderModal" tabindex="-1" aria-labelledby="viewProviderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewProviderModalLabel">Provider Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>ID:</strong> <span id="viewProviderId"></span></p>
              <p><strong>Name:</strong> <span id="viewProviderName"></span></p>
              <p><strong>Type:</strong> <span id="viewProviderType"></span></p>
              <p><strong>Contact Person:</strong> <span id="viewProviderContactPerson"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>Email:</strong> <span id="viewProviderEmail"></span></p>
              <p><strong>Phone:</strong> <span id="viewProviderPhone"></span></p>
              <p><strong>Service Area:</strong> <span id="viewProviderServiceArea"></span></p>
              <p><strong>Status:</strong> <span id="viewProviderStatus"></span></p>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <p><strong>Contract Start:</strong> <span id="viewProviderContractStart"></span></p>
              <p><strong>Contract End:</strong> <span id="viewProviderContractEnd"></span></p>
              <p><strong>Monthly Rate:</strong> <span id="viewProviderMonthlyRate"></span></p>
            </div>
            <div class="col-md-6">
              <p><strong>Notes:</strong></p>
              <p id="viewProviderNotes"></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete provider <strong id="deleteProviderName"></strong>?</p>
          <p class="text-danger">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Provider</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutConfirmLabel">Confirm Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to log out?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" onclick="confirmLogout()">Logout</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    const API_BASE = 'api/providers.php';
    let providers = [];
    let currentProviderId = null;
    let isEditMode = false;

    // Initialize the application
    document.addEventListener('DOMContentLoaded', function() {
      initializeEventListeners();
      applyStoredTheme();
      fetchProviders();
    });

    async function fetchProviders() {
      try {
        const res = await fetch(API_BASE);
        const data = await res.json();
        providers = Array.isArray(data) ? data.map(dbToUi) : [];
        loadProviders();
        updateDashboardStats();
      } catch (e) {
        showNotification('Failed to load providers', 'danger');
      }
    }

    function dbToUi(row) {
      return {
        id: parseInt(row.id),
        name: row.name,
        type: row.type,
        contactPerson: row.contact_person,
        contactEmail: row.contact_email,
        contactPhone: row.contact_phone,
        serviceArea: row.service_area,
        monthlyRate: parseFloat(row.monthly_rate),
        status: row.status,
        contractStart: row.contract_start,
        contractEnd: row.contract_end,
        notes: row.notes || ''
      };
    }

    function uiToDb(p) {
      return {
        name: p.name,
        type: p.type,
        contactPerson: p.contactPerson,
        contactEmail: p.contactEmail,
        contactPhone: p.contactPhone,
        serviceArea: p.serviceArea,
        monthlyRate: p.monthlyRate,
        status: p.status,
        contractStart: p.contractStart,
        contractEnd: p.contractEnd,
        notes: p.notes || ''
      };
    }

    function initializeEventListeners() {
      // Theme toggle
      document.getElementById('themeToggle').addEventListener('change', function() {
        document.body.classList.toggle('dark-mode', this.checked);
        localStorage.setItem('theme', this.checked ? 'dark' : 'light');
      });

      // Enhanced sidebar toggle with smooth animations
      document.getElementById('hamburger').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        
        // Add smooth transition class
        sidebar.classList.add('transitioning');
        setTimeout(() => {
          sidebar.classList.remove('transitioning');
        }, 300);
      });

      // Close sidebar when clicking outside on mobile
      document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger');
        
        if (window.innerWidth <= 992 && 
            !sidebar.contains(e.target) && 
            !hamburger.contains(e.target) &&
            !sidebar.classList.contains('collapsed')) {
          sidebar.classList.add('collapsed');
        }
      });

      // Active link management
      const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
      navLinks.forEach(link => {
        link.addEventListener('click', function() {
          navLinks.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
        });
      });

      // Logout confirmation
      const logoutLinks = document.querySelectorAll('.sidebar-footer a[href="login.php"]');
      logoutLinks.forEach(l => {
        l.addEventListener('click', function(e) {
          e.preventDefault();
          const modal = new bootstrap.Modal(document.getElementById('logoutConfirmModal'));
          modal.show();
        });
      });
    }

    function applyStoredTheme() {
      const stored = localStorage.getItem('theme');
      const isDark = stored === 'dark';
      document.body.classList.toggle('dark-mode', isDark);
      const toggle = document.getElementById('themeToggle');
      if (toggle) toggle.checked = isDark;
    }

    function loadProviders() {
      const tbody = document.getElementById('providersTableBody');
      tbody.innerHTML = '';

      providers.forEach(provider => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${provider.id}</td>
          <td>${provider.name}</td>
          <td>${provider.type}</td>
          <td>${provider.contactPerson}</td>
          <td>${provider.serviceArea}</td>
          <td>₱${provider.monthlyRate.toLocaleString()}</td>
          <td><span class="badge ${getStatusBadgeClass(provider.status)}">${provider.status}</span></td>
          <td>
            <div class="action-buttons">
              <button class="btn btn-sm btn-info" onclick="viewProvider(${provider.id})" title="View Details">
                <i class="bi bi-eye"></i>
              </button>
              <button class="btn btn-sm btn-primary" onclick="editProvider(${provider.id})" title="Edit Provider">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-sm btn-danger" onclick="deleteProvider(${provider.id})" title="Delete Provider">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        `;
        tbody.appendChild(row);
      });
    }

    function getStatusBadgeClass(status) {
      switch(status) {
        case 'Active': return 'bg-success';
        case 'Inactive': return 'bg-secondary';
        case 'Pending': return 'bg-warning text-dark';
        case 'Suspended': return 'bg-danger';
        default: return 'bg-secondary';
      }
    }

    function updateDashboardStats() {
      const totalProviders = providers.length;
      const activeContracts = providers.filter(p => p.status === 'Active').length;
      const serviceCategories = new Set(providers.map(p => p.type)).size;
      const totalRevenue = providers.reduce((sum, p) => sum + p.monthlyRate, 0);

      document.getElementById('totalProviders').textContent = totalProviders;
      document.getElementById('activeContracts').textContent = activeContracts;
      document.getElementById('serviceCategories').textContent = serviceCategories;
      document.getElementById('totalRevenue').textContent = '₱' + totalRevenue.toLocaleString();
    }

    function openAddModal() {
      isEditMode = false;
      currentProviderId = null;
      document.getElementById('providerModalLabel').textContent = 'Add New Service Provider';
      document.getElementById('providerForm').reset();
      document.getElementById('providerId').value = '';
    }

    function viewProvider(id) {
      const provider = providers.find(p => p.id === id);
      if (!provider) return;

      document.getElementById('viewProviderId').textContent = provider.id;
      document.getElementById('viewProviderName').textContent = provider.name;
      document.getElementById('viewProviderType').textContent = provider.type;
      document.getElementById('viewProviderContactPerson').textContent = provider.contactPerson;
      document.getElementById('viewProviderEmail').textContent = provider.contactEmail;
      document.getElementById('viewProviderPhone').textContent = provider.contactPhone;
      document.getElementById('viewProviderServiceArea').textContent = provider.serviceArea;
      document.getElementById('viewProviderStatus').textContent = provider.status;
      document.getElementById('viewProviderContractStart').textContent = provider.contractStart;
      document.getElementById('viewProviderContractEnd').textContent = provider.contractEnd;
      document.getElementById('viewProviderMonthlyRate').textContent = '₱' + provider.monthlyRate.toLocaleString();
      document.getElementById('viewProviderNotes').textContent = provider.notes || 'No notes available';

      const viewModal = new bootstrap.Modal(document.getElementById('viewProviderModal'));
      viewModal.show();
    }

    function editProvider(id) {
      const provider = providers.find(p => p.id === id);
      if (!provider) return;

      isEditMode = true;
      currentProviderId = id;
      document.getElementById('providerModalLabel').textContent = 'Edit Service Provider';
      
      // Populate form fields
      document.getElementById('providerId').value = provider.id;
      document.getElementById('providerName').value = provider.name;
      document.getElementById('providerType').value = provider.type;
      document.getElementById('contactPerson').value = provider.contactPerson;
      document.getElementById('contactEmail').value = provider.contactEmail;
      document.getElementById('contactPhone').value = provider.contactPhone;
      document.getElementById('serviceArea').value = provider.serviceArea;
      document.getElementById('contractStart').value = provider.contractStart;
      document.getElementById('contractEnd').value = provider.contractEnd;
      document.getElementById('monthlyRate').value = provider.monthlyRate;
      document.getElementById('providerStatus').value = provider.status;
      document.getElementById('providerNotes').value = provider.notes || '';

      const modal = new bootstrap.Modal(document.getElementById('providerModal'));
      modal.show();
    }

    function deleteProvider(id) {
      const provider = providers.find(p => p.id === id);
      if (!provider) return;

      document.getElementById('deleteProviderName').textContent = provider.name;
      currentProviderId = id;

      const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
      deleteModal.show();
    }

    async function confirmDelete() {
      if (!currentProviderId) return;
      try {
        const res = await fetch(`${API_BASE}?id=${currentProviderId}`, { method: 'DELETE' });
        if (!res.ok) throw new Error();
        await fetchProviders();
        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
        deleteModal.hide();
        showNotification('Provider deleted successfully!', 'success');
      } catch (e) {
        showNotification('Failed to delete provider', 'danger');
      }
    }

    async function saveProvider() {
      const form = document.getElementById('providerForm');
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const formData = new FormData(form);
      const providerData = {
        name: formData.get('providerName'),
        type: formData.get('providerType'),
        contactPerson: formData.get('contactPerson'),
        contactEmail: formData.get('contactEmail'),
        contactPhone: formData.get('contactPhone'),
        serviceArea: formData.get('serviceArea'),
        monthlyRate: parseFloat(formData.get('monthlyRate')),
        status: formData.get('providerStatus'),
        contractStart: formData.get('contractStart'),
        contractEnd: formData.get('contractEnd'),
        notes: formData.get('providerNotes')
      };

      try {
        if (isEditMode && currentProviderId) {
          const res = await fetch(`${API_BASE}?id=${currentProviderId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(uiToDb(providerData))
          });
          if (!res.ok) throw new Error();
          showNotification('Provider updated successfully!', 'success');
        } else {
          const res = await fetch(API_BASE, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(uiToDb(providerData))
          });
          if (!res.ok) throw new Error();
          showNotification('Provider added successfully!', 'success');
        }
        await fetchProviders();
        const modal = bootstrap.Modal.getInstance(document.getElementById('providerModal'));
        modal.hide();
      } catch (e) {
        showNotification('Failed to save provider', 'danger');
      }
    }

    function showNotification(message, type = 'info') {
      // Create notification element
      const notification = document.createElement('div');
      notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
      notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
      notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      
      document.body.appendChild(notification);
      
      // Auto remove after 3 seconds
      setTimeout(() => {
        if (notification.parentNode) {
          notification.remove();
        }
      }, 3000);
    }

    function confirmLogout() {
      window.location.href = 'login.php';
    }
  </script>
</body>
</html>
