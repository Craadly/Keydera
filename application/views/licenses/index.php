<div class="container is-fluid main_body"> 
<div class="section" >
  <h1 class="title">
    <?php echo $title; ?> <a class="button is-warning is-rounded is-pulled-right" href="<?php echo base_url(); ?>licenses/create"><i class="fas fa-plus-circle"></i><span class="p-l-xs is-hidden-smobile">Create License</span></a>
  </h1>
 <?php echo generate_breadcrumb(); ?>
 <?php if($this->session->flashdata('license_status')){
    $flash = $this->session->flashdata('license_status');
    echo '<div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; 
    if($flash['type']=='success'){
      echo '<script>setTimeout(function(){document.getElementsByClassName("notification")[0].style.display="none";},4000);</script>';
    } 
  } ?>
<div id="delete_notification"></div>
<div class="box">
  <table id="licenses_table" class="table is-striped is-hoverable" style="width: 100%">
    <thead>
      <tr>
        <th data-sortable="false"><center><input class="is-checkradio is-cr-t is-danger is-small" type="checkbox" name="delete_license_select_all" id="delete_license_select_all"><label for="delete_license_select_all"></label></center></th>
        <th>License code</th>
        <th>Product</th>
        <th>Client</th>
        <th>Date modified</th>
        <th>Uses left</th>
        <th data-sortable="false"><center>Usage</center></th>
        <th><center>Status</center></th>
        <th data-sortable="false"><center>Action</center></th>
      </tr>
    </thead>
</table>
</div>
</div>
</div>
