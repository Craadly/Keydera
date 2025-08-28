<<<<<<< HEAD
<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <div class="page-header-content">
        <div class="page-header-text">
          <h1 class="page-title"><?php echo $title; ?></h1>
          <p class="page-subtitle">Manage all versions for this product</p>
        </div>
        <div class="page-header-tools">
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search versions..." id="versions-search">
          </div>
        </div>
      </div>
    </div>

    <?php if($this->session->flashdata('product_status')): ?>
      <?php $flash = $this->session->flashdata('product_status'); ?>
      <div class="notification is-<?php echo $flash['type']; ?> is-light"><button class="delete"></button><?php echo $flash['message']; ?></div>
      <?php if($flash['type']=='success'): ?>
        <script>setTimeout(function(){document.getElementsByClassName('notification')[0].style.display='none';},4000);</script>
      <?php endif; ?>
    <?php endif; ?>

    <div class="data-table-container">
      <div class="table-header">
        <div class="table-title">
          <h3>Versions</h3>
          <p>Publish management, downloads and actions</p>
        </div>
      </div>

      <div class="table-wrapper">
        <table class="data-table ts" id="versions_table" style="width: 100%">
          <thead>
            <tr>
              <th>Version</th>
              <th>Release date</th>
              <th>Summary</th>
              <th class="center-align">Downloads</th>
              <th class="center-align">Status</th>
              <th class="center-align actions-column">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($versions as $version) : ?>
              <tr>
                <td><?php echo $version['version']; ?></td>
                <td><?php 
                  $originalDate = $version['release_date'];
                  $newDate = date($this->config->item('date_format'), strtotime($originalDate));
                  echo $newDate; ?></td>
                <td><?php echo (!empty($version['summary']))?$version['summary']:'-'; ?></td>
                <td class="center-align"><span class="tag is-primary is-rounded"><?php echo $this->downloads_model->get_update_downloads_based_on_version($version['vid']); ?> downloads</span></td>
                <td class="center-align">
                  <?php if($version['status']==1){
                    $is_valid = "Published";
                    $is_valid_typ = "success";
                    $is_valid_tooltip = "Clients can see and upgrade to this version.";
                  } else {
                    $is_valid = "Unpublished";
                    $is_valid_typ = "warning";
                    $is_valid_tooltip = "Clients cannot see or upgrade to this version.";
                  } ?>
                  <span class="tag is-<?= $is_valid_typ; ?> is-small is-rounded tooltip" data-tooltip='<?= $is_valid_tooltip; ?>'><?= $is_valid; ?></span>
                </td>
                <td class="center-align">
                  <div class="buttons is-centered">
                    <?php if($version['status']!=0){
                      $hidden = array('vid' => $version['vid'], 'version' => $version['version'], 'product' => $version['pid']);
                      echo form_open('/products/versions/unpublish', NULL, $hidden)."<button type='submit' class='button is-warning is-small'><i class='fas fa-eye-slash'></i>&nbsp;Unpublish</button></form>";
                    }else{
                      $hidden = array('vid' => $version['vid'], 'version' => $version['version'], 'product' => $version['pid']);
                      echo form_open('/products/versions/publish', NULL, $hidden)."<button type='submit' class='button is-success is-small'><i class='fas fa-eye'></i>&nbsp;Publish</button></form>";
                    } 
                    $hidden = array('vid' => $version['vid'], 'product' => $version['pid']);
                    echo form_open('/products/versions/download_files', NULL, $hidden); ?>
                    <button type="submit" class="button is-link is-small"><i class="fa fa-download"></i>&nbsp;Files</button></form>
                    <?php if(!empty($version['sql_file'])){ 
                      $hidden = array('vid' => $version['vid'], 'product' => $version['pid']);
                      echo form_open('/products/versions/download_sql', NULL, $hidden); ?>
                      <button type="submit" class="button is-info is-small"><i class="fa fa-database"></i>&nbsp;SQL</button></form>
                    <?php } 
                    $hidden = array('vid' => $version['vid'], 'version_old' => $version['version'], 'version' => $version['version'], 'product' => $version['pid']);
                    echo form_open('/products/versions/edit', NULL, $hidden)."<button type='submit' class='button is-success is-small'><i class='fas fa-edit'></i>&nbsp;Edit</button></form>";
                    $js = 'id="delete_form_'.$version['vid'].'"';
                    $hidden = array('vid' => $version['vid'], 'version' => $version['version'], 'product' => $version['pid']);
                    echo form_open('/products/versions/delete', $js, $hidden); ?>
                    <button type="button" data-id="<?php echo $version['vid']; ?>" data-title="version" data-body="Please note that all of the version <b><?php echo $version['version']; ?></b>'s relevant records like (update download logs) will also be permanently deleted." title="Delete Version" class="button with-delete-confirmation is-danger is-small"><i class="fa fa-trash"></i>&nbsp;Delete</button></form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?> 
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
.page-header { margin-bottom: 1.5rem; }
.page-header-content { display:flex; justify-content:space-between; align-items:center; gap:1rem; }
.page-title { font-size:2rem; font-weight:700; color:var(--text-primary); margin-bottom:.25rem; }
.page-subtitle { color:var(--text-secondary); font-size:.9rem; }
.page-header-tools .search-box{ position:relative; }
.page-header-tools .search-box i{ position:absolute; left:.75rem; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.9rem; }
.page-header-tools .search-box input{ padding:.5rem .75rem .5rem 2.25rem; border:2px solid var(--border); border-radius:8px; width:260px; }

.data-table-container { background:var(--bg-card); border-radius:12px; box-shadow:var(--shadow); overflow:hidden; }
.table-header { padding:1rem 1.5rem; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
.table-title h3 { font-size:1.1rem; font-weight:600; margin:0; color:var(--text-primary); }
.table-title p { color:var(--text-secondary); font-size:.9rem; margin:0; }
.table-wrapper { overflow-x:auto; }
.data-table { width:100%; border-collapse:collapse; font-size:.9rem; }
.data-table thead th { background:var(--bg-main); padding:1rem 1.25rem; text-align:left; font-weight:600; color:var(--text-primary); border-bottom:2px solid var(--border); white-space:nowrap; }
.data-table tbody td { padding:1rem 1.25rem; border-bottom:1px solid var(--border); vertical-align:middle; }
.center-align { text-align:center; }
.actions-column { white-space:nowrap; }
</style>

<script>
  // Lightweight client search over rows
  (function(){
    var input = document.getElementById('versions-search');
    if(!input) return;
    input.addEventListener('input', function(){
      var q = this.value.toLowerCase();
      document.querySelectorAll('#versions_table tbody tr').forEach(function(tr){
        var text = tr.innerText.toLowerCase();
        tr.style.display = text.indexOf(q) !== -1 ? '' : 'none';
      });
    });
  })();
</script>

=======
<div class="container is-fluid main_body"> 
<div class="section">
  <h1 class="title">
   <?php echo $title;
   ?>
 </h1>
 <?php echo generate_breadcrumb(); ?>
 <?php if($this->session->flashdata('product_status')){
    $flash = $this->session->flashdata('product_status');
    echo '<div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; 
    if($flash['type']=='success'){
      echo '<script>setTimeout(function(){document.getElementsByClassName("notification")[0].style.display="none";},4000);</script>';
    } 
  } ?>
 <div class="box">
  <table class="table ts is-striped is-hoverable" style="width: 100%">
    <thead>
      <tr>
        <th>Version</th>
        <th>Release date</th>
        <th>Notification/Summary</th>
        <th><center>Downloads</center></th>
        <th><center>Status</center></th>
        <th><center>Action</center></th>
      </tr>
    </thead>
    <tbody>
     <?php foreach($versions as $version) : ?><tr>
      <th><?php echo $version['version']; ?></th>
      <td><?php 
      $originalDate = $version['release_date'];
      $newDate = date($this->config->item('date_format'), strtotime($originalDate));
      echo $newDate; ?></td>
      <td><?php echo (!empty($version['summary']))?$version['summary']:'-'; ?></td>
      <td><center><span class="tag is-primary is-rounded"><?php echo $this->downloads_model->get_update_downloads_based_on_version($version['vid']); ?> downloads</span></center></td>
      <?php if($version['status']==1){
        $is_valid = "Published";
        $is_valid_typ = "success";
        $is_valid_tooltip = "Clients can see and upgrade to this version.";
      }
      else
      {
        $is_valid = "Unpublished";
        $is_valid_typ = "warning";
        $is_valid_tooltip = "Clients cannot see or upgrade to this version.";
      } ?>
      <td><center><span class="tag is-<?= $is_valid_typ; ?> is-small is-rounded tooltip" data-tooltip='<?= $is_valid_tooltip; ?>'><?= $is_valid; ?></span></center></td>
      <td><div class="buttons is-centered">
      <?php
      if($version['status']!=0){
        $hidden = array('vid' => $version['vid'], 'version' => $version['version'], 'product' => $version['pid']);
        echo form_open('/products/versions/unpublish', NULL, $hidden)."<button type='submit' class='button is-warning is-small' style='padding-top: 0px;padding-bottom: 0px;'><i class='fas fa-eye-slash'></i>&nbsp;Unpublish</button></form>&nbsp;&nbsp;";
        }else{
        $hidden = array('vid' => $version['vid'], 'version' => $version['version'], 'product' => $version['pid']);
        echo form_open('/products/versions/publish', NULL, $hidden)."<button type='submit' class='button is-success is-small' style='padding-top: 0px;padding-bottom: 0px;'><i class='fas fa-eye'></i>&nbsp;Publish</button></form>&nbsp;&nbsp;";
        } 
      $hidden = array('vid' => $version['vid'], 'product' => $version['pid']);
      echo form_open('/products/versions/download_files', NULL, $hidden); ?>
      <button type="submit" class="button is-link is-small" style="padding-top: 0px;padding-bottom: 0px;"><i class="fa fa-download"></i>&nbsp;Download Files</button></form>&nbsp;&nbsp;<?php 
      if(!empty($version['sql_file'])){ 
        $hidden = array('vid' => $version['vid'], 'product' => $version['pid']);
        echo form_open('/products/versions/download_sql', NULL, $hidden); ?>
        <button type="submit" class="button is-info is-small" style="padding-top: 0px;padding-bottom: 0px;"><i class="fa fa-database"></i>&nbsp;Download SQL</button></form>&nbsp;&nbsp;<?php 
      }
      $hidden = array('vid' => $version['vid'], 'version_old' => $version['version'], 'version' => $version['version'], 'product' => $version['pid']);
      echo form_open('/products/versions/edit', NULL, $hidden)."<button type='submit' class='button is-success is-small' style='padding-top: 0px;padding-bottom: 0px;'><i class='fas fa-edit'></i>&nbsp;Edit</button></form>&nbsp;&nbsp;";
      $js = 'id="delete_form_'.$version['vid'].'"';
      $hidden = array('vid' => $version['vid'], 'version' => $version['version'], 'product' => $version['pid']);
      echo form_open('/products/versions/delete', $js, $hidden); ?>
      <button type="button" data-id="<?php echo $version['vid']; ?>" data-title="version" data-body="Please note that all of the version <b><?php echo $version['version']; ?></b>'s relevant records like (update download logs) will also be permanently deleted." title="Delete Version" class="button with-delete-confirmation is-danger is-small" style="padding-top: 0px;padding-bottom: 0px;"><i class="fa fa-trash"></i>&nbsp;Delete</button></form></div></td>
      </div>
      </td>
     </tr>
     <?php endforeach; ?> 
    </tbody>
  </table>
</div>
</div>
</div>

>>>>>>> origin/main
