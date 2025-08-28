<<<<<<< HEAD
<div class="main-content main-content-expanded">
<div class="section">
  <div class="page-header">
    <div class="page-header-content">
      <div class="page-header-text">
        <h1 class="page-title"><?php echo $title; ?></h1>
        <p class="page-subtitle">Monitor update download requests and statuses</p>
      </div>
    </div>
  </div>

  <?php if($this->session->flashdata('update_downloads_status')): ?>
    <?php $flash = $this->session->flashdata('update_downloads_status'); ?>
    <div class="notification is-<?php echo $flash['type']; ?> is-light">
      <button class="delete"></button>
      <?php echo $flash['message']; ?>
    </div>
    <?php if($flash['type']=='success'): ?>
      <script>setTimeout(function(){document.getElementsByClassName("notification")[0].style.display="none";},4000);</script>
    <?php endif; ?>
  <?php endif; ?>

  <div id="delete_notification"></div>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon"><i class="fas fa-download"></i></div>
      <div class="stat-content">
        <div class="stat-number" id="total-downloads">--</div>
        <div class="stat-label">Total Downloads</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon active"><i class="fas fa-check-circle"></i></div>
      <div class="stat-content">
        <div class="stat-number" id="valid-downloads">--</div>
        <div class="stat-label">Valid</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon inactive"><i class="fas fa-times-circle"></i></div>
      <div class="stat-content">
        <div class="stat-number" id="invalid-downloads">--</div>
        <div class="stat-label">Invalid</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon blocked"><i class="fas fa-ban"></i></div>
      <div class="stat-content">
        <div class="stat-number" id="blocked-downloads">--</div>
        <div class="stat-label">Blocked</div>
      </div>
    </div>
  </div>

  <!-- Data Table Container -->
  <div class="data-table-container">
    <div class="table-header">
      <div class="table-title">
        <h3>Update Downloads</h3>
        <p>Review version requests and IPs</p>
      </div>
      <div class="table-actions">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search downloads..." id="download-search">
        </div>
        <div class="filter-dropdown">
          <select id="download-status-filter">
            <option value="">All Status</option>
            <option value="valid">Valid</option>
            <option value="invalid">Invalid</option>
            <option value="blocked">Blocked</option>
          </select>
        </div>
      </div>
    </div>

    <div class="table-wrapper">
      <table id="downloads_table" class="data-table" style="width: 100%">
        <thead>
          <tr>
            <th class="checkbox-column">
              <input class="checkbox-input" type="checkbox" name="delete_download_select_all" id="delete_download_select_all">
              <label for="delete_download_select_all" class="checkbox-label"></label>
            </th>
            <th>Product</th>
            <th>Version</th>
            <th>Downloaded from URL</th>
            <th>IP</th>
            <th>Download Date</th>
            <th class="center-align">Status</th>
            <th class="center-align actions-column">Action</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

    <!-- Empty State CTA (hidden by default; toggled from JS) -->
    <div id="downloads-empty-state-cta" style="display:none;">
      <div class="empty-state">
        <div class="empty-illustration"><i class="fas fa-cloud-download-alt"></i></div>
        <h3>No download logs yet</h3>
        <p>When your apps check for updates, logs will appear here. Start by adding a product and version.</p>
        <div class="empty-actions">
          <a href="<?php echo base_url('products/add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Create a product
          </a>
        </div>
      </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" id="downloads-bulk-actions" style="display: none;">
      <div class="bulk-actions-content">
        <span class="selected-count"><span id="downloads-selected-count">0</span> logs selected</span>
        <div class="bulk-buttons">
          <button class="btn btn-danger btn-sm" id="download-bulk-delete">
            <i class="fas fa-trash"></i>
            Delete Selected
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<style>
.page-header { margin-bottom: 2rem; }
.page-header-content { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem; }
.page-title { font-size:2rem; font-weight:700; color:var(--text-primary); margin-bottom:0.25rem; }
.page-subtitle { color:var(--text-secondary); font-size:0.875rem; margin:0; }

.stats-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); gap:1.5rem; margin-bottom:2rem; }
.stat-card { background:var(--bg-card); border-radius:12px; padding:1.5rem; box-shadow:var(--shadow); display:flex; align-items:center; gap:1rem; }
.stat-icon { width:50px; height:50px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; color:#fff; background:var(--text-muted); }
.stat-icon.active { background: var(--success); }
.stat-icon.inactive { background: var(--warning); }
.stat-icon.blocked { background: var(--danger); }
.stat-number { font-size:1.75rem; font-weight:700; color:var(--text-primary); }
.stat-label { color:var(--text-secondary); font-size:0.875rem; font-weight:500; }

.data-table-container { background:var(--bg-card); border-radius:12px; box-shadow:var(--shadow); overflow:hidden; }
.table-header { padding:1.5rem 2rem; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
.table-title h3 { font-size:1.25rem; font-weight:600; color:var(--text-primary); margin-bottom:0.25rem; }
.table-title p { color:var(--text-secondary); font-size:0.875rem; margin:0; }
.table-actions { display:flex; gap:1rem; align-items:center; }
.search-box { position:relative; display:flex; align-items:center; }
.search-box i { position:absolute; left:0.75rem; color:var(--text-muted); font-size:0.875rem; }
.search-box input { padding:0.5rem 0.75rem 0.5rem 2.5rem; border:2px solid var(--border); border-radius:8px; font-size:0.875rem; width:250px; }
.filter-dropdown select { padding:0.5rem 2.5rem 0.5rem 0.75rem; border:2px solid var(--border); border-radius:8px; font-size:0.875rem; background:#fff; cursor:pointer; }

.table-wrapper { overflow-x:auto; }
.data-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
.data-table thead th { background:var(--bg-main); padding:1rem 1.5rem; text-align:left; font-weight:600; color:var(--text-primary); border-bottom:2px solid var(--border); white-space:nowrap; }
.data-table thead th.center-align { text-align:center; }
.data-table tbody td { padding:1rem 1.5rem; border-bottom:1px solid var(--border); vertical-align:middle; }
.data-table tbody tr:hover { background:var(--bg-main); }

.checkbox-column { width:50px; text-align:center; }
.checkbox-input { display:none; }
.checkbox-label { display:inline-block; width:18px; height:18px; border:2px solid var(--border); border-radius:4px; cursor:pointer; transition:all .2s; }
.checkbox-input:checked + .checkbox-label { background:var(--primary); border-color:var(--primary); }
.checkbox-input:checked + .checkbox-label::after { content:'✓'; color:#fff; font-size:12px; font-weight:bold; display:flex; align-items:center; justify-content:center; height:100%; }

.center-align { text-align:center; }
.actions-column { width:200px; }

.bulk-actions { padding:1rem 2rem; background:var(--bg-main); border-top:1px solid var(--border); }
.bulk-actions-content { display:flex; justify-content:space-between; align-items:center; }
.selected-count { color:var(--text-secondary); font-weight:500; }
.bulk-buttons { display:flex; gap:.75rem; }
.btn { padding:.5rem 1rem; border:none; border-radius:8px; font-size:.875rem; cursor:pointer; display:inline-flex; align-items:center; gap:.5rem; }
.btn-sm { padding:.5rem 1rem; font-size:.75rem; }
.btn-danger { background:var(--danger); color:#fff; }
.btn-danger:hover { background:#dc2626; }

/* Empty state styles */
.empty-state { padding:3rem 1rem; text-align:center; }
.empty-illustration { width:72px; height:72px; margin:0 auto 1rem; border-radius:50%; background:var(--bg-main); display:flex; align-items:center; justify-content:center; color:var(--primary); font-size:1.5rem; box-shadow:var(--shadow-sm); }
.empty-state h3 { font-size:1.25rem; font-weight:700; color:var(--text-primary); margin-bottom:.5rem; }
.empty-state p { color:var(--text-secondary); margin:0 auto 1.25rem; max-width:520px; }
.empty-actions .btn-primary { background:var(--primary); color:#fff; }
.empty-actions .btn-primary:hover { filter:brightness(0.95); }

@media (max-width:1024px){
  .page-header-content{flex-direction:column; gap:1rem;}
  .table-header{flex-direction:column; gap:1rem; align-items:flex-start;}
  .table-actions{width:100%; justify-content:space-between;}
  .search-box input{width:200px;}
}
@media (max-width:768px){
  .stats-grid{grid-template-columns:1fr;}
  .table-wrapper{overflow-x:scroll;}
  .data-table{min-width:800px;}
  .bulk-actions-content{flex-direction:column; gap:1rem; align-items:flex-start;}
}
</style>
=======
<div class="container is-fluid main_body"> 
<div class="section">
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
 <?php echo generate_breadcrumb('Update Downloads'); ?>
 <?php if($this->session->flashdata('update_downloads_status')){
    $flash = $this->session->flashdata('update_downloads_status');
    echo '<div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; 
    if($flash['type']=='success'){
      echo '<script>setTimeout(function(){document.getElementsByClassName("notification")[0].style.display="none";},4000);</script>';
    } 
  } ?>
<div id="delete_notification"></div>
<div class="box">
  <table id="downloads_table" class="table is-striped is-hoverable" style="width: 100%">
    <thead>
      <tr>
        <th data-sortable="false"><center><input class="is-checkradio is-cr-t is-danger is-small" type="checkbox" name="delete_download_select_all" id="delete_download_select_all"><label for="delete_download_select_all"></label></center></th>
        <th>Product</th>
        <th data-sortable="false">Version</th>
        <th>Downloaded from URL</th>
        <th>IP</th>
        <th>Download Date</th>
        <th>Status</th>
        <th data-sortable="false">Action</th>
      </tr>
    </thead>
</table>
</div>
</div>
</div>
>>>>>>> origin/main
