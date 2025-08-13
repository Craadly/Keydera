<div class="container is-fluid main_body"> 
<div class="section">
  <h1 class="title">
    <?php echo $title; ?>
 </h1>
 <?php echo generate_breadcrumb(); ?>
 <?php if($this->session->flashdata('activity_status')){
   $flash = $this->session->flashdata('activity_status');
   echo '<div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; 
   if($flash['type']=='success'){
     echo '<script>setTimeout(function(){document.getElementsByClassName("notification")[0].style.display="none";},4000);</script>';
   } 
 } ?>
<div id="delete_notification"></div>
<div class="box">
  <table id="activities_table" class="table is-striped is-hoverable" style="width: 100%">
    <thead>
      <tr>
        <th data-sortable="false"><center><input class="is-checkradio is-cr-t is-danger is-small" type="checkbox" name="delete_activity_select_all" id="delete_activity_select_all"><label for="delete_activity_select_all"></label></center></th>
        <th>Log</th>
        <th>Date</th>
        <th data-sortable="false">Actions</th>
      </tr>
    </thead>
</table>
</div>
</div>
</div>

