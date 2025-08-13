<div class="container is-fluid main_body">  
<div class="section">
  <div class="columns is-multiline">
    <div class="column">
      <div class="widget-stat widget-card has-shadow2 grow">
        <a href="<?php echo base_url(); ?>products">
          <div class="widget-card-body p-md">
            <div class="widget-media">
              <span class="m-r-md widget-background has-text-link">
                <i class="fas fa-database"></i>
              </span>
              <div class="media-body">
                <h3 class="m-b-none has-text-black"><span class="m-l-none counter"><?php echo thousands_currency_format($product_count, true)[0]; ?></span><?php echo thousands_currency_format($product_count, true)[1]; ?></h3>
                <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">Product<?php echo return_s($product_count); ?></p>
                <span class="has-text-grey-dark is-size-6">(<span class="counter"><?php echo thousands_currency_format($product_count_active, true)[0]; ?></span><?php echo thousands_currency_format($product_count_active, true)[1]; ?> active)</span>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="column">
      <div class="widget-stat widget-card has-shadow2 grow">
        <a href="<?php echo base_url(); ?>licenses">
          <div class="widget-card-body p-md">
            <div class="widget-media">
              <span class="m-r-md widget-background <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"has-text-warning":"has-text-link"; ?>">
                <i class="fas fa-key"></i>
              </span>
              <div class="media-body">
                <h3 class="m-b-none has-text-black"><span class="m-l-none counter"><?php echo thousands_currency_format($license_count, true)[0]; ?></span><?php echo thousands_currency_format($license_count, true)[1]; ?></span></h3>
                <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">License<?php echo return_s($license_count); ?></p>
                <span class="has-text-grey-dark is-size-6">(<span class="counter"><?php echo thousands_currency_format($license_count_active, true)[0]; ?></span><?php echo thousands_currency_format($license_count_active, true)[1]; ?> active)</span>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="column">
      <div class="widget-stat widget-card has-shadow2 grow">
        <a href="<?php echo base_url(); ?>activations">
          <div class="widget-card-body p-md">
            <div class="widget-media">
              <span class="m-r-md widget-background <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"has-text-success":"has-text-link"; ?>">
                <i class="fas fa-hdd"></i>
              </span>
              <div class="media-body">
                <h3 class="m-b-none has-text-black"><span class="m-l-none counter"><?php echo thousands_currency_format($installation_count, true)[0]; ?></span><?php echo thousands_currency_format($installation_count, true)[1]; ?></span></h3>
                <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">Activation<?php echo return_s($installation_count); ?></p>
                <span class="has-text-grey-dark is-size-6">(<span class="counter"><?php echo thousands_currency_format($installation_count_active, true)[0]; ?></span><?php echo thousands_currency_format($installation_count_active, true)[1]; ?> active)</span>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="column">
      <div class="widget-stat widget-card has-shadow2 grow">
        <a href="<?php echo base_url(); ?>update_downloads">
          <div class="widget-card-body p-md">
            <div class="widget-media">
              <span class="m-r-md widget-background <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"has-text-dark":"has-text-link"; ?>">
                <i class="fas fa-download"></i>
              </span>
              <div class="media-body">
                <h3 class="m-b-none has-text-black"><span class="m-l-none counter"><?php echo thousands_currency_format($download_count, true)[0]; ?></span><?php echo thousands_currency_format($download_count, true)[1]; ?></span></h3>
                <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">Update Download<?php echo return_s($download_count); ?></p>
                <span class="has-text-grey-dark is-size-6">(<span class="counter"><?php echo thousands_currency_format($download_count_today, true)[0]; ?></span><?php echo thousands_currency_format($download_count_today, true)[1]; ?> today)</span>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
    <div class="column">
      <div class="widget-stat widget-card has-shadow2 grow">
        <div class="widget-card-body p-md">
          <div class="widget-media">
            <span class="m-r-md widget-background <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"has-text-info":"has-text-link"; ?>">
              <i class="fas fa-server"></i>
            </span>
            <div class="media-body">
              <h3 class="m-b-none has-text-black"><span class="m-l-none counter"><?php echo thousands_currency_format($api_call_count, true)[0]; ?></span><?php echo thousands_currency_format($api_call_count, true)[1]; ?></span></h3>
              <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">API Call<?php echo return_s($api_call_count); ?></p>
              <span class="has-text-grey-dark is-size-6">(<span class="counter"><?php echo thousands_currency_format($api_call_count_today, true)[0]; ?></span><?php echo thousands_currency_format($api_call_count_today, true)[1]; ?> today)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<?php if($show_message):?>
<article class="message is-warning has-shadow2" data-bulma-attached="attached">
  <div class="message-body">
    <?php if(rand(1, 2) == 1):?>
      👋 Howdy! We are thrilled you chose <b>LicenseBox</b>. We hope you are finding it useful, please consider giving us a <a href="https://codecanyon.net/downloads">5-star rating on Envato</a>. It will help us a lot, Thank you!
    <?php elseif(rand(1, 2) == 2):?>
      👋 Hi there! Looking for custom integration in your applications, themes or plugins? Get in touch with us at <a href="mailto:support@licensebox.app?subject=Custom Integration"><b>support@licensebox.app</b></a> and let us do all the hard work.
    <?php else:?>
      👋 Hey! You can also follow us on Twitter at <a href="https://www.twitter.com/licenseboxapp"><b>@LicenseBoxApp</b></a> to stay updated with important product announcements and future updates, Thank you!
    <?php endif; ?>
  </div>
</article>
<?php endif; ?>
<div class="columns">
  <div class="column is-one-third">
    <article class="tile is-child box has-shadow2">
        <p class="subtitle" style="color:<?php echo (strtolower(LICENSEBOX_THEME)=="flat")?"#363636":"#0a0a0a"; ?>;font-size: 1.1rem;font-weight: <?php echo (strtolower(LICENSEBOX_THEME)=="material")?"500":"600"; ?>;">Licenses Share</p>
        <div id="chartContainer" style="height: 300px; width: 100%;"><div id="chart-loading"><center><i class="subtitle fa fa-spinner fa-spin"></i></center></div></div>
    </article>
  </div>
  <div class="column">
    <article class="tile is-child box has-shadow2">
        <p class="subtitle" style="color:<?php echo (strtolower(LICENSEBOX_THEME)=="flat")?"#363636":"#0a0a0a"; ?>;font-size: 1.1rem;font-weight: <?php echo (strtolower(LICENSEBOX_THEME)=="material")?"500":"600"; ?>;">New Licenses/Activations/Downloads (<?php echo date('F Y'); ?>)</p>
        <div id="chartContainer2" style="height: 300px; width: 100%;"><div id="chart-loading2"><center><i class="subtitle fa fa-spinner fa-spin"></i></center></div></div>
    </article>
  </div>
</div>
<article class="message <?php echo (strtolower(LICENSEBOX_THEME)=="classic")?"is-primary":((strtolower(LICENSEBOX_THEME)=="flat")?"is-link":"is-white"); ?> has-shadow2">
  <div class="message-header" style="border-radius: .5rem .5rem 0 0">
    <p>Activities (Past 24 hours) <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Activites done by you will be marked with a 'done by <?php echo $this->session->userdata('fullname'); ?>' text, all other activities are either done by the System, Cron Job or the API." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></p><a href="<?php echo base_url(); ?>activities" class="button is-small is-rounded <?php echo (strtolower(LICENSEBOX_THEME)=="material")?"is-link":"is-white"; ?> is-pulled-right">View All</a>
  </div>
  <div id="inbox-messages" class="inbox-messages" style="max-height: 575px;overflow-y: auto;">
    <?php 
    if(!empty($activity_logs)):
      foreach($activity_logs as $log): ?>
    <div class="card <?php echo (strtolower(LICENSEBOX_THEME)=="material")?"has-default-shadows":""; ?>">
      <div class="card-content">
        <small><?php  $originalDate = $log['al_date'];
         $newDate = date($this->config->item('datetime_format'), strtotime($originalDate));
         echo $newDate; ?></small>
        <div><?php echo $log['al_log']; ?></div>
     </div>
   </div>
   <?php endforeach; 
   else: ?>
    <div class="card <?php echo (strtolower(LICENSEBOX_THEME)=="material")?"has-default-shadows":""; ?>">
      <div class="card-content has-text-centered">
        <p class="<?php echo (strtolower(LICENSEBOX_THEME)!="material")?"has-text-weight-semibold":"" ?>">No new activity to show.</p>
      </div></div>
      <?php
    endif;
    ?>
  </div>
</article>
</div>
</div>