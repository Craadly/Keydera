<<<<<<< HEAD
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
=======
<div class="container is-fluid main_body"> 
<div class="section" >
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
<?php echo generate_breadcrumb('Updates'); ?>
<div class="columns">
  <div class="column">
    <div class="box has-text-centered">
    <h5 class="title is-5 p-t-md" style="margin-bottom: 15px;"><?php echo ($update_data['status'])?$update_data['message']:'Cheers! Keydera is up to date.'; ?></h5>
    <?php if(!$update_data['status']){ ?>
      <p class="p-b-md">It looks like there are no new updates available at the moment.</p>
    <?php } ?>
    <div style="margin-bottom: 15px;">
    <?php if($update_data['status']){ ?>
    <div style="text-align: center;">
      <div style="display: inline-block; text-align: left;">
        <article class="message is-success set_width_550_desktop" style="margin-bottom: 15px;max-width:550px;">
          <div class="message-body">
            Make sure you back-up all the files and the database before updating.
          </div>
        </article>
        <div class="notification set_width_550_desktop" style="max-width:550px;box-shadow: none;">
          <div class="content"><p><b>Changelog:</b></p><?php echo $update_data['changelog']; ?></div>
        </div>
      </div>
    </div>
    <?php 
    if($show_loader){?>
        <div style="text-align: center;">
          <div style="display: inline-block; text-align: left;">
            <div class="set_width_550_desktop" style="max-width:550px;margin-top:15px;">
              <progress id="prog" value="0" max="100.0" class="progress is-success" style="width:100%;margin-bottom:10px;"></progress>
              <?php $lbapi->download_update($update_id, $has_sql, $version); ?>
            </div>
          </div>
        </div>
        <br>
    <?php }else{ ?>
      <?php echo form_open('updates'); ?>
        <input type="hidden" class="form-control" value="<?php echo $update_data['update_id']; ?>" name="update_id">
        <input type="hidden" class="form-control" value="<?php echo $update_data['has_sql']; ?>" name="has_sql">
        <input type="hidden" class="form-control" value="<?php echo $update_data['version']; ?>" name="version">
        <div class="row" style="padding-left: 15px;margin-top: 15px;">
          <button type="submit" class="button is-warning is-rounded"><i class="fas fa-download p-r-xs"></i> Install Update</button>
        </div> 
      <?php echo form_close(); ?>
    <?php } ?>
      <?php }else{ ?>
        <a class="button is-warning is-rounded" href="<?php echo base_url();?>updates" style="margin-top: 10px;"><i class="fas fa-sync-alt p-r-xs"></i> Recheck</a>
      <?php } ?>
    </div>
  </div>
  </div>
</div>
</div>
</div>
>>>>>>> origin/main

