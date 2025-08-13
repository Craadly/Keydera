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