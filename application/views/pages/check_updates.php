<div class="container is-fluid main_body"> 
<div class="section" >
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
<?php echo generate_breadcrumb('Updates'); ?>
<div class="columns">
  <div class="column">
    <div class="box has-text-centered">
    <h5 class="title is-5 p-t-md" style="margin-bottom: 15px;"><?php echo ($update_data['status'])?$update_data['message']:'Cheers! LicenseBox is up to date.'; ?></h5>
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
