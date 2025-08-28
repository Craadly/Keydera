<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

  <div class="settings-card">
      <div class="form-section has-text-centered">
        <?php
          $ud = isset($update_data) && is_array($update_data) ? $update_data : array();
          $status = isset($ud['status']) ? (bool)$ud['status'] : false;
          $msg = $status && !empty($ud['message']) ? $ud['message'] : 'Cheers! Keydera is up to date.';
          $changelog = !empty($ud['changelog']) ? $ud['changelog'] : '';
          // Prefer controller-provided values after POST; fall back to update_data on initial GET
          $u_id = isset($update_id) && $update_id !== '' ? $update_id : (isset($ud['update_id']) ? $ud['update_id'] : '');
          $u_sql = isset($has_sql) && $has_sql !== '' ? $has_sql : (isset($ud['has_sql']) ? $ud['has_sql'] : '');
          $u_ver = isset($version) && $version !== '' ? $version : (isset($ud['version']) ? $ud['version'] : '');
        ?>
        <h3 class="section-title" style="justify-content:center;">
          <i class="fas fa-cloud-download-alt"></i>
          <?php echo $msg; ?>
        </h3>
        <?php if(!$status){ ?>
          <p class="section-subtitle">It looks like there are no new updates available at the moment.</p>
        <?php } ?>

        <?php if($status){ ?>
          <div class="safe-note">
            <i class="fas fa-shield-alt"></i>
            Make sure you back up all files and the database before updating.
          </div>
          <div class="changelog">
            <p class="changelog-title">Changelog</p>
            <div class="changelog-body"><?php echo $changelog; ?></div>
          </div>

          <?php if($show_loader){ ?>
            <div class="progress-wrap">
              <progress id="prog" value="0" max="100.0" class="progress is-success"></progress>
              <?php $lbapi->download_update($update_id, $has_sql, $version); ?>
            </div>
          <?php } else { ?>
            <?php echo form_open('updates'); ?>
              <input type="hidden" value="<?php echo $u_id; ?>" name="update_id">
              <input type="hidden" value="<?php echo $u_sql; ?>" name="has_sql">
              <input type="hidden" value="<?php echo $u_ver; ?>" name="version">
              <div class="actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i> Install Update</button>
              </div>
            </form>
          <?php } ?>
        <?php } else { ?>
          <div class="actions">
            <a class="btn btn-secondary" href="<?php echo base_url(); ?>updates"><i class="fas fa-sync-alt"></i> Recheck</a>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<style>
.page-header { margin-bottom: 1.5rem; }
.page-title { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); }
.form-section { /* uses shared baseline; keep local tweaks if any */ padding: 1.5rem; border-bottom: 1px solid var(--border); }
.form-section:last-child { border-bottom: none; }
.section-title { font-size: 1.1rem; font-weight: 600; color: var(--text-primary); display: inline-flex; align-items: center; gap: .5rem; }
.section-subtitle { color: var(--text-secondary); font-size: .95rem; margin-top: .25rem; }
.safe-note { margin: 1rem auto; display: inline-flex; align-items: center; gap: .5rem; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; padding: .75rem 1rem; border-radius: 8px; }
.changelog { max-width: 700px; margin: 1rem auto; text-align: left; }
.changelog-title { font-weight: 600; margin-bottom: .25rem; }
.changelog-body { background: #fff; border: 2px solid var(--border); border-radius: 8px; padding: 1rem; }
.progress-wrap { max-width: 700px; margin: 1rem auto; }
.actions { margin-top: 1rem; display: flex; gap: .75rem; justify-content: center; }
.btn { padding: .65rem 1rem; border-radius: 8px; font-weight: 500; font-size: .875rem; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: .5rem; border: none; text-decoration: none; }
.btn-primary { background: var(--primary); color: #fff; }
.btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: var(--shadow-lg); }
.btn-secondary { background: #fff; color: var(--text-secondary); border: 2px solid var(--border); }
.btn-secondary:hover { background: var(--bg-main); border-color: var(--text-muted); }
</style>

