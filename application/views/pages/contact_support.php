<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

    <div class="card-wrap">
      <div class="card-section">
        <h3 class="section-title"><i class="fas fa-life-ring"></i> Get Help</h3>
        <p class="section-subtitle">Reach our support team with your issue details and screenshots. We usually reply within 1-2 business days.</p>
        <div class="actions">
          <?php $ver = !empty($lb_version) ? $lb_version : ''; ?>
          <a class="btn btn-primary" href="mailto:support@keydera.app?subject=Need help with Keydera <?php echo urlencode($ver); ?>&body=(Please describe your issue here and attach screenshots.)">
            <i class="fas fa-envelope"></i>
            Email Support
          </a>
          <a class="btn btn-secondary" target="_blank" rel="noopener" href="https://keydera.app/docs">
            <i class="fas fa-book"></i>
            Documentation
          </a>
        </div>
      </div>

      <div class="card-section">
        <h3 class="section-title"><i class="fas fa-info-circle"></i> Helpful Details</h3>
        <p class="section-subtitle">Copy and include these details in your email to speed up troubleshooting.</p>
        <?php 
          $phpv = PHP_VERSION; 
          $server = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Unknown';
          $db = function_exists('mysqli_get_client_info') ? mysqli_get_client_info() : 'Unknown';
          $appv = !empty($lb_version) ? $lb_version : '';
          $info = "Keydera Version: {$appv}\nPHP Version: {$phpv}\nServer: {$server}\nMySQL Client: {$db}";
        ?>
        <textarea id="sysinfo" class="textarea monospace" rows="6" readonly><?php echo $info; ?></textarea>
        <div class="actions">
          <button id="copy_sysinfo" type="button" class="btn btn-secondary"><i class="fas fa-copy"></i> Copy Details</button>
        </div>
      </div>

      <div class="card-section">
        <h3 class="section-title"><i class="fas fa-bug"></i> What to Include</h3>
        <ul class="checklist">
          <li><i class="fas fa-check"></i> Steps to reproduce</li>
          <li><i class="fas fa-check"></i> Expected vs actual behavior</li>
          <li><i class="fas fa-check"></i> Screenshots or error messages</li>
          <li><i class="fas fa-check"></i> Any recent changes or updates</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<style>
.page-header { margin-bottom: 1.5rem; }
.page-title { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); }
.card-wrap { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
.card-section { padding: 1.5rem; border-bottom: 1px solid var(--border); }
.card-section:last-child { border-bottom: none; }
.section-title { font-size: 1.1rem; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: .5rem; }
.section-title i { color: var(--primary); }
.section-subtitle { color: var(--text-secondary); font-size: .95rem; margin-top: .25rem; }
.actions { margin-top: 1rem; display: flex; gap: .75rem; flex-wrap: wrap; }
.btn { padding: .65rem 1rem; border-radius: 8px; font-weight: 500; font-size: .875rem; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: .5rem; border: none; text-decoration: none; }
.btn-primary { background: var(--primary); color: #fff; }
.btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: var(--shadow-lg); }
.btn-secondary { background: #fff; color: var(--text-secondary); border: 2px solid var(--border); }
.btn-secondary:hover { background: var(--bg-main); border-color: var(--text-muted); }
.textarea { width: 100%; padding: .75rem; border: 2px solid var(--border); border-radius: 8px; background: #fff; min-height: 120px; }
.monospace { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
.checklist { list-style: none; padding: 0; margin: .5rem 0 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: .5rem .75rem; }
.checklist li { color: var(--text-primary); display: flex; align-items: center; gap: .5rem; }
.checklist i { color: #10B981; }
</style>

<script>
(function(){
  var btn = document.getElementById('copy_sysinfo');
  if(btn){
    btn.addEventListener('click', function(){
      var ta = document.getElementById('sysinfo');
      if(!ta) return;
      ta.select();
      try {
        var ok = document.execCommand('copy');
        if (ok) {
          btn.innerHTML = '<i class="fas fa-check"></i> Copied';
          setTimeout(function(){ btn.innerHTML = '<i class="fas fa-copy"></i> Copy Details'; }, 1500);
        }
      } catch(e) {}
    });
  }
})();
</script>
