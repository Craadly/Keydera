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

