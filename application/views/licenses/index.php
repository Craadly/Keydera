<div class="main-content main-content-expanded">
<div class="section">
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-header-text">
        <h1 class="page-title"><?php echo $title; ?></h1>
        <p class="page-subtitle">Manage and monitor all your license keys</p>
      </div>
      <div class="page-header-actions">
        <a class="btn btn-primary" href="<?php echo base_url(); ?>licenses/create">
          <i class="fas fa-plus-circle"></i>
          <span class="btn-text">Create License</span>
        </a>
      </div>
    </div>
    
  </div>

  <?php if($this->session->flashdata('license_status')): ?>
    <?php $flash = $this->session->flashdata('license_status'); ?>
    <div class="notification is-<?php echo $flash['type']; ?> is-light">
      <button class="delete"></button>
      <?php echo $flash['message']; ?>
    </div>
    <?php if($flash['type']=='success'): ?>
      <script>setTimeout(function(){document.getElementsByClassName("notification")[0].style.display="none";},4000);</script>
    <?php endif; ?>
  <?php endif; ?>

  <div id="delete_notification"></div>

  <!-- Statistics Cards -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon">
        <i class="fas fa-key"></i>
      </div>
      <div class="stat-content">
        <div class="stat-number" id="total-licenses">--</div>
        <div class="stat-label">Total Licenses</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon active">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="stat-content">
        <div class="stat-number" id="active-licenses">--</div>
        <div class="stat-label">Active Licenses</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon inactive">
        <i class="fas fa-times-circle"></i>
      </div>
      <div class="stat-content">
        <div class="stat-number" id="inactive-licenses">--</div>
        <div class="stat-label">Inactive Licenses</div>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon blocked">
        <i class="fas fa-ban"></i>
      </div>
      <div class="stat-content">
        <div class="stat-number" id="blocked-licenses">--</div>
        <div class="stat-label">Blocked Licenses</div>
      </div>
    </div>
  </div>

  <!-- Main Data Table -->
  <div class="data-table-container">
    <div class="table-header">
      <div class="table-title">
        <h3>All Licenses</h3>
        <p>View and manage your license inventory</p>
      </div>
      <div class="table-actions">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search licenses..." id="license-search">
        </div>
        <div class="filter-dropdown">
          <select id="status-filter">
            <option value="">All Status</option>
            <option value="valid">Valid</option>
            <option value="invalid">Invalid</option>
            <option value="blocked">Blocked</option>
          </select>
        </div>
      </div>
    </div>

    <div class="table-wrapper">
      <table id="licenses_table" class="data-table" style="width: 100%">
        <thead>
          <tr>
            <th class="checkbox-column">
              <input class="checkbox-input" type="checkbox" name="delete_license_select_all" id="delete_license_select_all">
              <label for="delete_license_select_all" class="checkbox-label"></label>
            </th>
            <th>License Code</th>
            <th>Product</th>
            <th>Client</th>
            <th>Date Modified</th>
            <th>Uses Left</th>
            <th class="center-align">Usage</th>
            <th class="center-align">Status</th>
            <th class="center-align actions-column">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Data will be loaded via AJAX -->
        </tbody>
      </table>
    </div>

    <!-- Empty State CTA (shown only when there are no licenses at all) -->
    <div class="empty-state" id="empty-state-cta" style="display: none;">
      <div class="empty-illustration">
        <i class="fas fa-key"></i>
      </div>
      <h3 class="empty-title">No licenses yet</h3>
      <p class="empty-subtitle">Create your first license to get started.</p>
      <a class="btn btn-primary" href="<?php echo base_url(); ?>licenses/create">
        <i class="fas fa-plus-circle"></i>
        <span class="btn-text">Add your first license</span>
      </a>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="bulk-actions" style="display: none;">
      <div class="bulk-actions-content">
        <span class="selected-count"><span id="selected-count">0</span> licenses selected</span>
        <div class="bulk-buttons">
          <button class="btn btn-danger btn-sm" id="bulk-delete">
            <i class="fas fa-trash"></i>
            Delete Selected
          </button>
          <button class="btn btn-warning btn-sm" id="bulk-block">
            <i class="fas fa-ban"></i>
            Block Selected
          </button>
          <button class="btn btn-success btn-sm" id="bulk-unblock">
            <i class="fas fa-unlock"></i>
            Unblock Selected
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<style>
.page-header {
  margin-bottom: 2rem;
}

.page-header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1rem;
}

.page-header-text {
  flex: 1;
}

.page-title {
  font-size: 2rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.25rem;
}

.page-subtitle {
  color: var(--text-secondary);
  font-size: 0.875rem;
  margin: 0;
}

.page-header-actions {
  flex-shrink: 0;
}

.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-weight: 500;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  border: none;
  text-decoration: none;
}

.btn-primary {
  background: var(--primary);
  color: white;
}

.btn-primary:hover {
  background: var(--primary-dark);
  transform: translateY(-1px);
  box-shadow: var(--shadow-lg);
}

.btn-text {
  display: inline;
}

/* Statistics Cards */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: var(--bg-card);
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: var(--shadow);
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: transform 0.2s;
}

.stat-card:hover {
  transform: translateY(-2px);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  color: white;
  background: var(--text-muted);
}

.stat-icon.active {
  background: var(--success);
}

.stat-icon.inactive {
  background: var(--warning);
}

.stat-icon.blocked {
  background: var(--danger);
}

.stat-content {
  flex: 1;
}

.stat-number {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.25rem;
}

.stat-label {
  color: var(--text-secondary);
  font-size: 0.875rem;
  font-weight: 500;
}

/* Data Table Container */
.data-table-container {
  background: var(--bg-card);
  border-radius: 12px;
  box-shadow: var(--shadow);
  overflow: hidden;
}

.table-header {
  padding: 1.5rem 2rem;
  border-bottom: 1px solid var(--border);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.table-title h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 0.25rem;
}

.table-title p {
  color: var(--text-secondary);
  font-size: 0.875rem;
  margin: 0;
}

.table-actions {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-box i {
  position: absolute;
  left: 0.75rem;
  color: var(--text-muted);
  font-size: 0.875rem;
}

.search-box input {
  padding: 0.5rem 0.75rem 0.5rem 2.5rem;
  border: 2px solid var(--border);
  border-radius: 8px;
  font-size: 0.875rem;
  width: 250px;
  transition: border-color 0.2s;
}

.search-box input:focus {
  outline: none;
  border-color: var(--primary);
}

.filter-dropdown select {
  padding: 0.5rem 2.5rem 0.5rem 0.75rem;
  border: 2px solid var(--border);
  border-radius: 8px;
  font-size: 0.875rem;
  background: white;
  cursor: pointer;
}

/* Table Styles */
.table-wrapper {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.875rem;
}

.data-table thead th {
  background: var(--bg-main);
  padding: 1rem 1.5rem;
  text-align: left;
  font-weight: 600;
  color: var(--text-primary);
  border-bottom: 2px solid var(--border);
  white-space: nowrap;
}

.data-table thead th.center-align {
  text-align: center;
}

.data-table tbody td {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
}

.data-table tbody tr:hover {
  background: var(--bg-main);
}

.checkbox-column {
  width: 50px;
  text-align: center;
}

.checkbox-input {
  display: none;
}

.checkbox-label {
  display: inline-block;
  width: 18px;
  height: 18px;
  border: 2px solid var(--border);
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.2s;
}

.checkbox-input:checked + .checkbox-label {
  background: var(--primary);
  border-color: var(--primary);
}

.checkbox-input:checked + .checkbox-label::after {
  content: '✓';
  color: white;
  font-size: 12px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}

.center-align {
  text-align: center;
}

.actions-column {
  width: 200px;
}

/* Status badges */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-valid {
  background: rgba(16, 185, 129, 0.1);
  color: var(--success);
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-invalid {
  background: rgba(239, 68, 68, 0.1);
  color: var(--danger);
  border: 1px solid rgba(239, 68, 68, 0.2);
}

.status-blocked {
  background: rgba(245, 101, 101, 0.1);
  color: var(--danger);
  border: 1px solid rgba(245, 101, 101, 0.2);
}

.status-active {
  background: rgba(99, 102, 241, 0.1);
  color: var(--primary);
  border: 1px solid rgba(99, 102, 241, 0.2);
}

.status-inactive {
  background: rgba(156, 163, 175, 0.1);
  color: var(--text-muted);
  border: 1px solid rgba(156, 163, 175, 0.2);
}

/* Action buttons */
.action-buttons {
  display: flex;
  gap: 0.5rem;
  justify-content: center;
}

.action-btn {
  padding: 0.5rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.875rem;
}

.action-btn:hover {
  transform: scale(1.1);
}

.action-btn.edit {
  background: var(--primary);
  color: white;
}

.action-btn.block {
  background: var(--warning);
  color: white;
}

.action-btn.unblock {
  background: var(--success);
  color: white;
}

.action-btn.email {
  background: var(--accent);
  color: white;
}

.action-btn.delete {
  background: var(--danger);
  color: white;
}

/* Bulk Actions */
.bulk-actions {
  padding: 1rem 2rem;
  background: var(--bg-main);
  border-top: 1px solid var(--border);
}

.bulk-actions-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.selected-count {
  color: var(--text-secondary);
  font-weight: 500;
}

.bulk-buttons {
  display: flex;
  gap: 0.75rem;
}

.btn-sm {
  padding: 0.5rem 1rem;
  font-size: 0.75rem;
}

.btn-danger {
  background: var(--danger);
  color: white;
}

.btn-danger:hover {
  background: #dc2626;
}

.btn-warning {
  background: var(--warning);
  color: white;
}

.btn-warning:hover {
  background: #d97706;
}

.btn-success {
  background: var(--success);
  color: white;
}

.btn-success:hover {
  background: #059669;
}

/* Notification styles */
.notification {
  padding: 1rem 1.5rem;
  margin-bottom: 1.5rem;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.notification.is-success {
  background: rgba(16, 185, 129, 0.1);
  border: 1px solid rgba(16, 185, 129, 0.2);
  color: var(--success);
}

.notification.is-danger {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.2);
  color: var(--danger);
}

.notification .delete {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.25rem;
  opacity: 0.7;
}

.notification .delete:hover {
  opacity: 1;
}

/* Responsive Design */
@media (max-width: 1024px) {
  .page-header-content {
    flex-direction: column;
    gap: 1rem;
  }

  .page-header-actions {
    align-self: flex-start;
  }

  .table-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .table-actions {
    width: 100%;
    justify-content: space-between;
  }

  .search-box input {
    width: 200px;
  }
}

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }

  .table-wrapper {
    overflow-x: scroll;
  }

  .data-table {
    min-width: 800px;
  }

  .bulk-actions-content {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .bulk-buttons {
    width: 100%;
    justify-content: flex-start;
  }
}

/* Loading states */
.loading {
  opacity: 0.6;
  pointer-events: none;
}

.loading::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 20px;
  height: 20px;
  margin: -10px 0 0 -10px;
  border: 2px solid var(--primary);
  border-radius: 50%;
  border-top-color: transparent;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Empty state CTA */
.empty-state {
  padding: 3rem 2rem;
  text-align: center;
  background: var(--bg-card);
  border-top: 1px solid var(--border);
}
.empty-illustration {
  width: 72px;
  height: 72px;
  margin: 0 auto 1rem auto;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--bg-main);
  color: var(--primary);
  font-size: 1.75rem;
  box-shadow: var(--shadow);
}
.empty-title {
  margin: 0.25rem 0 0.5rem 0;
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text-primary);
}
.empty-subtitle {
  margin: 0 0 1rem 0;
  color: var(--text-secondary);
}
</style>
