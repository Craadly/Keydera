<!-- Inspired Footer Design -->
<footer class="app-footer-inspired">
  <div class="footer-container">
    <div class="footer-left">
      <a href="<?php echo base_url(); ?>" class="footer-logo">K</a>
      <p class="footer-copyright">© <?php echo date('Y'); ?> Keydera. All rights reserved.</p>
    </div>
    <div class="footer-right">
      <nav class="footer-nav-links">
        <a href="https://www.keydera.app/privacy" target="_blank" rel="noopener">Privacy</a>
        <a href="https://www.keydera.app/terms" target="_blank" rel="noopener">Terms</a>
        <a href="https://www.keydera.app/support" target="_blank" rel="noopener">Support</a>
      </nav>
      <div class="footer-version">
        Version <?php echo defined('KEYDERA_VERSION') ? KEYDERA_VERSION : '2.0.1'; ?>
      </div>
    </div>
  </div>
</footer>
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/BulmaTagsInput/js/bulma-tagsinput.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/DataTables/js/datatables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/DataTables/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/dataTables.bulma.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/JQueryDateTimePicker/jquery.datetimepicker.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/JQueryValidation/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/Select2/js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/Dropify/js/dropify.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/BulmaJS/js/bulma.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/ClipboardJS/clipboard.min.js"></script>
<?php if(($this->router->fetch_class()=='pages')&&($this->router->fetch_method()=='dashboard'||$this->router->fetch_method()=='view')): ?>
<script src="<?php echo base_url(); ?>assets/vendor/CanvasJS/canvasjs.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/Waypoints/jquery.waypoints.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/CounterUp/jquery.counterup.min.js"></script>
<script>
jQuery(document).ready(function(){
	$(".counter").counterUp({
		delay: 30,
		time: 1000
	});
});
window.onload = function (){
  CanvasJS.addColorSet("newColors",
    [
      "#8064A1",
      "#3CB371",  
      "#C0504E"         
    ]);
var chart = new CanvasJS.Chart("chartContainer", {
  colorSet: "newColors",
  animationEnabled: true,
  toolTip: {
    cornerRadius: 5,
    borderColor: "#209cee",
    fontColor: "#4a4a4a",
    borderThickness: 2
  },
  data: [{
    type: "pie",
    startAngle: -90,
    radius: 100,
    showInLegend: true,
    toolTipContent: "{name}: <strong>{y}</strong>",
    indexLabel: "{name}: {y}",
    dataPoints: [
      <?php $lic_res = $this->licenses_model->get_licenses_count_for_chart();
      if(!empty($lic_res['valid'])||!empty($lic_res['invalid'])||!empty($lic_res['blocked'])){ ?>
        { y: <?php echo $lic_res['valid']; ?>, name: "Valid" },
        { y: <?php echo $lic_res['invalid']; ?>, name: "Invalid" },
        { y: <?php echo $lic_res['blocked']; ?>, name: "Blocked" }
      <?php }
      ?>
    ]
  }]
});
showDefaultText(chart, "Not enough data for creating chart!");
setTimeout(function() {
       $("#chart-loading").fadeOut()
}, 0);
chart.render();
var chart = new CanvasJS.Chart("chartContainer2", {
  theme: "light2",
  animationEnabled: true,
  zoomEnabled: true,
  axisX: {
    valueFormatString: "DD MMM YYYY",
    lineThickness:0.7,
    tickThickness: 0.7,
  },
    axisY: {
    lineThickness:0.7,
    gridThickness: 0.7,
    tickThickness: 0.7,
    includeZero: false
  },
  toolTip: {
    shared: true,
    cornerRadius: 5,
    borderColor: "#209cee",
    fontColor: "#4a4a4a",
    borderThickness: 2
  },
  legend: {
    cursor: "pointer",
    verticalAlign: "bottom",
    horizontalAlign: "center",
    itemclick: toogleDataSeries
  },
      <?php 
      $chartdata='';
      $chartdata1='';
      $chartdata2='';
      $day='';
      $month='';
      $year='';
      $tday = ($day == "" ) ? "01" : $day;
      $tmonth = ($month == "" ) ? date("m") : $month;
      $tyear = ($year == "" ) ? date("Y") : $year;
     
      $month_sd = date("Y-m-d", strtotime($tmonth.'/'.$tday.'/'.$tyear));
      $month_ed = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime($tmonth.'/'.$tday.'/'.$tyear))));
      $countf=0;
      while (strtotime($month_sd) <= strtotime($month_ed)) {
        $licenses_res = $this->licenses_model->get_licenses_based_on_date($month_sd,$month_sd." 23:59:59"); 
        $activations_res = $this->activations_model->get_activations_based_on_date($month_sd,$month_sd." 23:59:59"); 
        $updates_res = $this->downloads_model->get_update_downloads_based_on_date($month_sd,$month_sd." 23:59:59");

        $countf+=$licenses_res+$activations_res+$updates_res;

        $time = strtotime($month_sd);
        $dday=date('d', $time);
        $dmonth=(date('m', $time)-1);
        $dyear=date('Y', $time);

        $chartdata.="
        { x: new Date(".$dyear.",".$dmonth.",".$dday."), y: ".$licenses_res." },";
        $chartdata1.="
        { x: new Date(".$dyear.",".$dmonth.",".$dday."), y: ".$activations_res." },";
        $chartdata2.="
        { x: new Date(".$dyear.",".$dmonth.",".$dday."), y: ".$updates_res." },";
        $month_sd = date ("Y-m-d", strtotime("+1 day", strtotime($month_sd)));
      }
    ?>
  data: [{
    type:"line",
    axisYType: "primary",
    name: "License Added/Modified",
    showInLegend: true,
    dataPoints: [  
      <?php if(!empty($countf)){echo $chartdata;}  ?>
    ]
  },
  {
    type: "line",
    lineDashType: "shortDot",
    axisYType: "primary",
    name: "Valid Activations",
    showInLegend: true,
    color: "#A1BF63",
    dataPoints: [
     <?php if(!empty($countf)){echo $chartdata1;} ?>
    ]
  },
  {
    type: "line",
    lineDashType: "shortDashDot",
    axisYType: "primary",
    name: "Updates Downloaded",
    showInLegend: true,
    color: "#51CDA0",
    dataPoints: [
      <?php if(!empty($countf)){echo $chartdata2;} ?>
    ]
  }]
});
showDefaultText(chart, "Not enough data for creating chart!");
setTimeout(function() {
       $("#chart-loading2").fadeOut()
}, 0);
chart.render();
if(chart.axisY[0].get("interval") < 1){
      chart.axisY[0].set("interval", 1);  
}
function showDefaultText(chart, text){   
   var isEmpty = !(chart.options.data[0].dataPoints && chart.options.data[0].dataPoints.length > 0);
   if(!chart.options.subtitles)
    (chart.options.subtitles = []);
   
   if(isEmpty)
    chart.options.subtitles.push({
     text : text,
     fontFamily: "Calibri",
     fontWeight: "bold",
     fontSize: 14,
     verticalAlign : 'center',
   });
   else
    (chart.options.subtitles = []);
}
function toogleDataSeries(e){
  if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
    e.dataSeries.visible = false;
  } else{
    e.dataSeries.visible = true;
  }
  chart.render();
}
}
</script>
<?php endif; ?>
<?php if(($this->router->fetch_class()=='licenses')&&($this->router->fetch_method()=='index')): ?>
<script src="<?php echo base_url(); ?>assets/vendor/Ckeditor/ckeditor.js"></script>
<script>
$(document).on('click', '.with-email-confirmation', function(){
    var CkToolbar = ['bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'];
    var id = $(this).data("id");
    Bulma.create('alert', {
    type: 'link',
    title: 'Email license details to the client?',
    body: '<form id="send_email_form" method="post" accept-charset="utf-8" action="<?=base_url();?>licenses/email_license"><input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" /><input type="hidden" name="product" value="' + $(this).data("product") + '" /><div class="field"><label class="label">Client Email Address</label><div class="control"><input class="input" type="email" name="email" id="email" value="' + $(this).data("email") + '" placeholder="Enter client email address" required><p id="email_error" style="display:none!important" class="is-danger">This field is required.</p></div></div><div class="field"><label class="label">Email Subject</label><div class="control"><input class="input" type="text" name="subject" id="subject" value="' + $(this).data("product") + ' - License Information" placeholder="Enter email subject" required><p id="subject_error" style="display:none!important" class="is-danger">This field is required.</p></div></div><div class="field"><label class="label">Message</label><div class="control"><textarea class="textarea" name="email_license_details" id="email_license_details" placeholder="Enter email message" rows="6">Hello, <br><br> Your license information is: <br><br> ' + $(this).data("client") + ' License Code: <b>' + $(this).data("license") + '</b> <br> License Uses: <b>' + $(this).data("uses") + '</b> ' + $(this).data("expiration") + ' <br><br> Thank you for your purchase!</textarea><p id="message_error" style="display:none!important" class="is-danger">This field is required.</p></div></div></form>',
    confirm: {
        close: false,
        label: 'Send',
        classes: ['modal-button-small', 'modal-button-confirm'],
        onClick: function() {
          if(document.getElementById("email").value.length == 0){
            $('#email').addClass('is-danger');
            $('#email_error').show();
          }
          if(document.getElementById("subject").value.length == 0){
            $('#subject').addClass('is-danger');
            $('#subject_error').show();
          }
          if(document.getElementById("email_license_details").value.length == 0){
            $('#email_license_details').addClass('is-danger');
            $('#message_error').show();
          }
          if((document.getElementById("email").value.length != 0)&&(document.getElementById("subject").value.length != 0)&&(document.getElementById("email_license_details").value.length != 0)){
            $("#send_email_form").submit();
          }else{
            return false;
          }
        },
    },
    cancel: {
        label: 'Cancel',
        classes: ['modal-button-small', 'modal-button-cancel'],
        onClick: function() {
            return false;
        }
    }
  });
  ClassicEditor.create( document.querySelector( '#email_license_details' ), {
    toolbar: CkToolbar,
    link: {
      decorators: {
        addTargetToLinks: {
          mode: 'manual',
          label: 'Open in a new tab',
          attributes: {
            target: '_blank'
          }
        }
      }
    }
  });
});
</script>
<script src="<?php echo base_url(); ?>assets/js/common.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/pages/licenses.js?v=<?php echo time(); ?>"></script>
<?php endif; ?>
<?php if(($this->router->fetch_class()=='activations')&&($this->router->fetch_method()=='index')): ?>
<script src="<?php echo base_url(); ?>assets/js/common.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/pages/activations.js?v=<?php echo time(); ?>"></script>
<?php endif; ?>
<?php if(($this->router->fetch_class()=='users')&&($this->router->fetch_method()=='activities')): ?>
<script>
$(document).ready(function(){
  var activities_table = $('#activities_table').DataTable({
      "processing": true,
      "responsive": true,
      "serverSide": true,
      "aLengthMenu": [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
      "iDisplayLength": 25,
      "order": [[ 2, "desc" ]],
      "dom": 'l<"table_custom_buttons">ftipr',
            initComplete: function(){
            $("div.table_custom_buttons").html('<button type="button" class="button is-small is-danger is-rounded" name="delete_selected_activity" id="delete_selected_activity">Delete Selected</button>');           
            },
      "ajax":{
        "url": "<?php echo base_url('users/get_activities') ?>",
        "dataType": "json",
        "type": "POST",
        "data":{  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }
      },
      "columnDefs": [
        { "orderable": true, "width": 190, "targets": [2] },
        { "orderable": false, "width": 50, "targets": [3] },
        { "orderable": false, "width": 20, "targets": [0] }
      ],
      error: function(){ 
        $("#activities_table").html("");
        $("#activities_table_processing").hide();
      },
      language: {
        "processing": "<i class='fas fa-sync-alt  fa-spin'></i> Loading. Please wait..."
      },
      "deferRender": true   
  });
  $('#activities_table').wrap('<div class="dataTables_scroll" />');
  $.fn.dataTable.ext.errMode = 'throw';
  $('body').on('click', '#delete_activity_select_all', function(){
    if(this.checked){
      $('.delete_activity_checkbox').each(function(){
        this.checked = true;
        $(this).closest('tr').addClass('removeRow');
      });
    }else{
      $('.delete_activity_checkbox').each(function(){
        this.checked = false;
        $(this).closest('tr').removeClass('removeRow');
      });
    }
  });
  $('body').on('click', '.delete_activity_checkbox', function(){
    if($('.delete_activity_checkbox:checked').length == $('.delete_activity_checkbox').length){
        $('#delete_activity_select_all').prop('checked', true);
    }else{
        $('#delete_activity_select_all').prop('checked', false);
    }
    if($(this).is(':checked')){
     $(this).closest('tr').addClass('removeRow');
    }
    else{
     $(this).closest('tr').removeClass('removeRow');
    }
  });
  $('body').on('click', '#delete_selected_activity', function(){
    var checkbox = $('.delete_activity_checkbox:checked');
    if(checkbox.length > 0)
    {
      Bulma.create('alert', {
        type: 'danger',
        title: 'Are you sure to delete selected activity logs?',
        body: 'Please note that this action cannot be undone.',
        confirm: {
          label: 'Confirm',
          classes: ['modal-button-small', 'modal-button-confirm'],
          onClick: function() {
          var checkbox_value = [];
          $(checkbox).each(function(){
            checkbox_value.push($(this).val());
          });
          $.ajax({
            url:"<?php echo base_url(); ?>users/delete_selected_activity",
            method:"POST",
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>', 'delete_activities_checkbox':checkbox_value},
            success:function(){
              $('.removeRow').fadeOut(1500);
              setTimeout( function(){ 
                activities_table.ajax.reload();
                Bulma.create('notification', {
                  body: 'Selected activity logs were successfully deleted.',
                  dismissInterval: 4000,
                  isDismissable: true,
                  color: 'success',
                  parent: document.getElementById('delete_notification')
                }).show();
              }, 1500);
            }
           });
          }
        },
        cancel: {
          label: 'Cancel',
          classes: ['modal-button-small', 'modal-button-cancel'],
          onClick: function() {
              return false;
          }
        }
      });
    }else{
      Bulma.create('notification', {
        body: 'Please select at least one activity log for bulk deleting.',
        dismissInterval: 4000,
        isDismissable: true,
        color: 'danger',
        parent: document.getElementById('delete_notification')
      }).show();
    }
  });

  // Hide footer (info + pagination) when there are no records OR only a single page
  function updateActivitiesFooter(){
    var info = activities_table.page ? activities_table.page.info() : null;
    var hasAny = (info && info.recordsDisplay && info.recordsDisplay > 0);
    var hasMultiplePages = (info && info.pages && info.pages > 1);
    var wrapper = $('#activities_table').closest('.dataTables_wrapper');
    if (hasAny && hasMultiplePages) {
      wrapper.find('.dataTables_info, .dataTables_paginate').css('display', '');
    } else {
      wrapper.find('.dataTables_info, .dataTables_paginate').css('display', 'none');
    }
  }
  activities_table.on('draw', updateActivitiesFooter);
  updateActivitiesFooter();
});
</script>
<?php endif; ?>
<?php if(($this->router->fetch_class()=='downloads')&&($this->router->fetch_method()=='index')): ?>
<script src="<?php echo base_url(); ?>assets/js/common.js?v=<?php echo time(); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/pages/downloads.js?v=<?php echo time(); ?>"></script>
<?php endif; ?>
<script>
const bulmaTagsInputInstances = BulmaTagsInput.attach();
bulmaTagsInputInstances.forEach(bulmaTagsInputInstance => {
  bulmaTagsInputInstance.input.onblur = function () {
    bulmaTagsInputInstance.add(bulmaTagsInputInstance.input.value);
    bulmaTagsInputInstance.input.value = "";
  };
});
var clip = new ClipboardJS('.copy_to_clipboard');
document.addEventListener('DOMContentLoaded', function () {
var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('#navbar-burger'), 0);
if ($navbarBurgers.length > 0) {
  $navbarBurgers.forEach(function ($el) {
    $el.addEventListener('click', function () {
      var target = $el.dataset.target;
      var $target = document.getElementById(target);
      $el.classList.toggle('is-active');
      $target.classList.toggle('is-active');
    });
  });
}
});
$(document).on('click', '.notification > button.delete', function(){
  $(this).parent().addClass('is-hidden');
  return false;
});
$(document).ready( function(){
  $('.date-time-picker').datetimepicker({
    format:'Y-m-d H:i:s'
  });
  $('.ts').DataTable({
    responsive: true,
    "order": []
  });
  // Hide info/pagination on single-page simple tables
  $('.ts').each(function(){
    var table = $(this).DataTable();
    function updateSimpleTableFooter(){
      var info = table.page ? table.page.info() : null;
      var hasMultiple = (info && info.pages && info.pages > 1);
      var wrapper = $(table.table().container()).closest('.dataTables_wrapper');
      if (hasMultiple) {
        wrapper.find('.dataTables_info, .dataTables_paginate').css('display','');
      } else {
        wrapper.find('.dataTables_info, .dataTables_paginate').css('display','none');
      }
    }
    table.on('draw', updateSimpleTableFooter);
    updateSimpleTableFooter();
  });
  $('.ts').wrap('<div class="dataTables_scroll" />');
  $('.nots').wrap('<div class="dataTables_scroll" />');
  $('.dropify').dropify();
});
</script>
<script>
$(document).on('click', '.with-delete-confirmation', function(){
    var id = $(this).data("id");
    Bulma.create('alert', {
    type: 'danger',
    title: 'Are you sure to delete this ' + $(this).data("title") + '?',
    body: $(this).data("body"),
    confirm: {
        label: 'Confirm',
        classes: ['modal-button-small', 'modal-button-confirm'],
        onClick: function() {
          $("#delete_form_"+id).submit();
        },
    },
    cancel: {
        label: 'Cancel',
        classes: ['modal-button-small', 'modal-button-cancel'],
        onClick: function() {
            return false;
        }
    }
  });
});
</script>
<script>
$(function(){                
  function mobile_expandable_menu(){
    if( $(window).width()<768 ){
      $('.navbar-link').next('.navbar-dropdown').hide();
      $('.navbar-link').on('click', function(){
          $(this).next('.navbar-dropdown').slideToggle();
      });
    }else{
      $('.navbar-link').next('.navbar-dropdown').css('display','');
      $('.navbar-link').unbind('click');
    }
  }
  mobile_expandable_menu();
});
</script>
<script>
  $(document).ready(function() {
    $('.is-select2').select2({
      placeholder: "Select an option"
    });
    $(".show_loading").click(function (e) {
      $(".show_loading").addClass("is-loading");
    });
  });
</script>
<script>
// Modern sidebar interactions: collapse, mobile overlay, submenu a11y, and persistence
(function(){
  const appContainer = document.querySelector('.app-container');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const body = document.body;

  function applyCollapsedState(collapsed){
    const shouldCollapse = !!collapsed;
    if(shouldCollapse){
      appContainer.classList.add('collapsed');
      localStorage.setItem('sidebar-collapsed','1');
    }else{
      appContainer.classList.remove('collapsed');
      localStorage.removeItem('sidebar-collapsed');
    }
  }

  function setMobileOpen(open){
    if(open){
      sidebar.classList.add('mobile-open');
      overlay.classList.add('visible');
      body.style.overflow = 'hidden';
    }else{
      sidebar.classList.remove('mobile-open');
      overlay.classList.remove('visible');
      body.style.overflow = '';
    }
  }

  // Public toggle for buttons
  window.toggleSidebar = function(){
    const isDesktop = window.matchMedia('(min-width: 769px)').matches;
    if(isDesktop){
      const isCollapsed = appContainer.classList.contains('collapsed');
      applyCollapsedState(!isCollapsed);
    }else{
      const isOpen = sidebar.classList.contains('mobile-open');
      setMobileOpen(!isOpen);
    }
  }

  // Overlay click closes on mobile
  if(overlay){
    overlay.addEventListener('click', ()=> setMobileOpen(false));
  }

  // Restore persisted collapsed state on desktop
  if(localStorage.getItem('sidebar-collapsed') === '1'){
    applyCollapsedState(true);
  }

  // Close mobile sidebar on resize to desktop
  window.addEventListener('resize', function(){
    if(window.matchMedia('(min-width: 769px)').matches){
      setMobileOpen(false);
    }
  });

  // Keyboard access for submenu toggle buttons
  document.querySelectorAll('.nav-submenu-toggle').forEach(btn=>{
    btn.setAttribute('role','button');
    btn.setAttribute('tabindex','0');
    btn.addEventListener('keydown', (e)=>{
      if(e.key === 'Enter' || e.key === ' '){
        e.preventDefault();
        btn.click();
      }
    });
  });

  // Topbar Help dropdown
  const helpBtn = document.getElementById('helpMenuButton');
  const helpWrap = helpBtn ? helpBtn.closest('.topbar-dropdown') : null;
  if(helpBtn && helpWrap){
    helpBtn.addEventListener('click', (e)=>{
      e.stopPropagation();
      const isOpen = helpWrap.classList.contains('open');
      helpWrap.classList.toggle('open', !isOpen);
      helpBtn.setAttribute('aria-expanded', String(!isOpen));
    });
    document.addEventListener('click', ()=>{
      if(helpWrap.classList.contains('open')){
        helpWrap.classList.remove('open');
        helpBtn.setAttribute('aria-expanded','false');
      }
    });
  }
})();
</script>
<?php 
if(($this->router->fetch_class()=='users')&&($this->router->fetch_method()=='login')){
  echo generate_form_validation_js($this->router->fetch_method());
}
if(($this->router->fetch_class()=='users')&&($this->router->fetch_method()=='forgot_password')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='users')&&($this->router->fetch_method()=='reset_password')){
  echo generate_form_validation_js($this->router->fetch_method());
}
if(($this->router->fetch_class()=='products')&&($this->router->fetch_method()=='add')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='products')&&($this->router->fetch_method()=='edit')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='products')&&($this->router->fetch_method()=='add_version')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='products')&&($this->router->fetch_method()=='edit_version')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='licenses')&&($this->router->fetch_method()=='create')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='licenses')&&($this->router->fetch_method()=='edit')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='generate_helpers')&&($this->router->fetch_method()=='external_helper')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='generate_helpers')&&($this->router->fetch_method()=='internal_helper')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='settings')&&($this->router->fetch_method()=='general_settings')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='settings')&&($this->router->fetch_method()=='email_settings')){
  echo generate_form_validation_js('test_email');
} 
if(($this->router->fetch_class()=='settings')&&($this->router->fetch_method()=='email_settings')){
  echo generate_form_validation_js($this->router->fetch_method()); ?>
<script>
$(document).ready(function(){
  checkEmailMethod();
  $("#email_method").change(checkEmailMethod); 
  checkSmtpAuthentication();
  $("#smtp_requires_authentication").change(checkSmtpAuthentication); 
});
function checkEmailMethod(){
  if ($("#email_method").val() == 'smtp') {
    $('#smtp_settings').show();
  } else {
    $('#smtp_settings').hide();
  }
}
function checkSmtpAuthentication(){
  if ($("#smtp_requires_authentication").val() == '1') {
    $('#smtp_authentication').show();
  } else {
    $('#smtp_authentication').hide();
  }
}
</script>
<?php } 
if(($this->router->fetch_class()=='settings')&&($this->router->fetch_method()=='api_settings')){
  echo generate_form_validation_js($this->router->fetch_method()); 
  ?>
<script>
function load_endpoints(val){
if(val === "internal"){
  document.getElementById('endpoints').style.display = "block";
  document.getElementById('internal_api').style.display = "block";
  document.getElementById('external_api').style.display = "none";
  $('#external_api :input').attr('disabled', true);
  $('#internal_api :input').removeAttr('disabled');;
}
else if(val === "external"){
  document.getElementById('endpoints').style.display = "block";
  document.getElementById('external_api').style.display = "block";
  document.getElementById('internal_api').style.display = "none";
  $('#internal_api :input').attr('disabled', true);
  $('#external_api :input').removeAttr('disabled');
}
}
</script>
  <?php
}
if(($this->router->fetch_class()=='settings')&&($this->router->fetch_method()=='account_settings')){
  echo generate_form_validation_js($this->router->fetch_method());
} 
if(($this->router->fetch_class()=='settings')&&($this->router->fetch_method()=='account_settings')){
  echo generate_form_validation_js('account_settings2');
} ?>
<script>
$(document).ready(function(){
  $(".toggle-password").click(function() {
    $(this).text($(this).text() == 'show' ? 'hide' : 'show');
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });
});

// Premium Design System JavaScript
$(document).ready(function() {
  // Legacy sidebar functionality (only if old markup exists)
  if ($('.nav-sidebar').length) {
    window.toggleSidebar = function() {
      const sidebar = $('.nav-sidebar');
      const mainContent = $('.main-content');
      const isCollapsed = sidebar.hasClass('nav-sidebar-collapsed');
      if (isCollapsed) {
        sidebar.removeClass('nav-sidebar-collapsed');
        mainContent.removeClass('main-content-expanded');
      } else {
        sidebar.addClass('nav-sidebar-collapsed');
        mainContent.addClass('main-content-expanded');
      }
      localStorage.setItem('sidebarCollapsed', !isCollapsed);
    };
    // Mobile sidebar toggle
    window.toggleMobileSidebar = function() {
      const sidebar = $('.nav-sidebar');
      const overlay = $('.sidebar-overlay');
      const isVisible = sidebar.hasClass('mobile-visible');
      if (isVisible) {
        sidebar.removeClass('mobile-visible');
        overlay.removeClass('active');
        $('body').removeClass('sidebar-open');
      } else {
        sidebar.addClass('mobile-visible');
        overlay.addClass('active');
        $('body').addClass('sidebar-open');
      }
    };
    // Close sidebar when clicking overlay
    $(document).on('click', '.sidebar-overlay', function() {
      toggleMobileSidebar();
    });
    // Load saved sidebar state
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (sidebarCollapsed && window.innerWidth >= 768) {
      $('.nav-sidebar').addClass('nav-sidebar-collapsed');
      $('.main-content').addClass('main-content-expanded');
    }
    // Handle window resize cleanup
    $(window).on('resize', function() {
      const isMobile = window.innerWidth < 768;
      const sidebar = $('.nav-sidebar');
      if (!isMobile && sidebar.hasClass('mobile-visible')) {
        sidebar.removeClass('mobile-visible');
        $('.sidebar-overlay').removeClass('active');
        $('body').removeClass('sidebar-open');
      }
    });
  }
    
    // User menu toggle
    window.toggleUserMenu = function() {
        const dropdown = $('.user-dropdown-menu');
        dropdown.toggleClass('show');
    };
    
    // Close user menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.user-dropdown').length) {
            $('.user-dropdown-menu').removeClass('show');
        }
    });
    
    // Theme toggle
    window.toggleTheme = function() {
        const body = $('body');
        const isDark = body.hasClass('dark-theme');
        
        if (isDark) {
            body.removeClass('dark-theme');
            localStorage.setItem('theme', 'light');
        } else {
            body.addClass('dark-theme');
            localStorage.setItem('theme', 'dark');
        }
    };
    
    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        $('body').addClass('dark-theme');
    }
    
    // Initialize notification toasts
    function showNotification(message, type = 'info') {
        const toast = $(`
            <div class="notification-toast ${type}">
                ${message}
                <button onclick="$(this).parent().fadeOut(300)" style="background:none; border:none; color:inherit; float:right; cursor:pointer;">&times;</button>
            </div>
        `);
        
        $('body').append(toast);
        setTimeout(() => toast.fadeOut(300), 4000);
    }
    
    // Enhanced hover effects for action buttons
    $('.action-btn').hover(
        function() {
            $(this).css('transform', 'scale(1.05)');
        },
        function() {
            $(this).css('transform', 'scale(1)');
        }
    );
    
    // Smooth scrolling for better UX
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 600);
        }
    });
    
    // Auto-hide notifications
    $('.notification-toast').each(function() {
        const notification = $(this);
        setTimeout(() => {
            notification.fadeOut(300);
        }, 5000);
    });
    
  // Submenu toggle functionality (robust)
  window.toggleSubmenu = function(btn, sectionKey) {
    const toggle = btn;
    const content = document.getElementById('submenu-' + sectionKey);
    const arrow = toggle.querySelector('.submenu-arrow');
    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
    if (isExpanded) {
      content.classList.remove('expanded');
      toggle.setAttribute('aria-expanded', 'false');
      if (arrow) arrow.style.transform = 'rotate(0deg)';
    } else {
      content.classList.add('expanded');
      toggle.setAttribute('aria-expanded', 'true');
      if (arrow) arrow.style.transform = 'rotate(180deg)';
    }
    localStorage.setItem('submenu-' + sectionKey, !isExpanded);
  };

  // Load saved submenu states and auto-open active section
  document.querySelectorAll('.nav-submenu-toggle').forEach(function(toggle){
    const sectionKey = toggle.dataset.section;
    if(!sectionKey) return;
    const content = document.getElementById('submenu-' + sectionKey);
    const arrow = toggle.querySelector('.submenu-arrow');
    const header = toggle.querySelector('.submenu-header');
    const saved = localStorage.getItem('submenu-' + sectionKey) === 'true';
    const hasActiveChild = !!(content && content.querySelector('.submenu-link.active'));
  const container = document.querySelector('.app-container');
  const isCollapsed = container && container.classList.contains('collapsed');
  if(!isCollapsed && (saved || hasActiveChild) && content){
          content.classList.add('expanded');
          toggle.setAttribute('aria-expanded', 'true');
          if (arrow) arrow.style.transform = 'rotate(180deg)';
          if (header && hasActiveChild) header.classList.add('active');
        }
  });
});
</script>
	</main>
</div>
</body>
</html>
