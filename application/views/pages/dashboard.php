<<<<<<< HEAD
<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title">Dashboard</h1>
    </div>

    <!-- Top stats -->
    <div class="stats-grid">
      <a class="stat-card" href="<?php echo base_url(); ?>products">
        <div class="stat-icon"><i class="fas fa-database"></i></div>
        <div class="stat-content">
          <div class="stat-value"><span class="counter"><?php echo thousands_currency_format($product_count, true)[0]; ?></span><?php echo thousands_currency_format($product_count, true)[1]; ?></div>
          <div class="stat-label">Product<?php echo return_s($product_count); ?></div>
          <div class="stat-meta">(<span class="counter"><?php echo thousands_currency_format($product_count_active, true)[0]; ?></span><?php echo thousands_currency_format($product_count_active, true)[1]; ?> active)</div>
        </div>
      </a>

      <a class="stat-card" href="<?php echo base_url(); ?>licenses">
        <div class="stat-icon"><i class="fas fa-key"></i></div>
        <div class="stat-content">
          <div class="stat-value"><span class="counter"><?php echo thousands_currency_format($license_count, true)[0]; ?></span><?php echo thousands_currency_format($license_count, true)[1]; ?></div>
          <div class="stat-label">License<?php echo return_s($license_count); ?></div>
          <div class="stat-meta">(<span class="counter"><?php echo thousands_currency_format($license_count_active, true)[0]; ?></span><?php echo thousands_currency_format($license_count_active, true)[1]; ?> active)</div>
        </div>
      </a>

      <a class="stat-card" href="<?php echo base_url(); ?>activations">
        <div class="stat-icon"><i class="fas fa-hdd"></i></div>
        <div class="stat-content">
          <div class="stat-value"><span class="counter"><?php echo thousands_currency_format($installation_count, true)[0]; ?></span><?php echo thousands_currency_format($installation_count, true)[1]; ?></div>
          <div class="stat-label">Activation<?php echo return_s($installation_count); ?></div>
          <div class="stat-meta">(<span class="counter"><?php echo thousands_currency_format($installation_count_active, true)[0]; ?></span><?php echo thousands_currency_format($installation_count_active, true)[1]; ?> active)</div>
        </div>
      </a>

      <a class="stat-card" href="<?php echo base_url(); ?>update_downloads">
        <div class="stat-icon"><i class="fas fa-download"></i></div>
        <div class="stat-content">
          <div class="stat-value"><span class="counter"><?php echo thousands_currency_format($download_count, true)[0]; ?></span><?php echo thousands_currency_format($download_count, true)[1]; ?></div>
          <div class="stat-label">Update Download<?php echo return_s($download_count); ?></div>
          <div class="stat-meta">(<span class="counter"><?php echo thousands_currency_format($download_count_today, true)[0]; ?></span><?php echo thousands_currency_format($download_count_today, true)[1]; ?> today)</div>
        </div>
      </a>

      <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-server"></i></div>
        <div class="stat-content">
          <div class="stat-value"><span class="counter"><?php echo thousands_currency_format($api_call_count, true)[0]; ?></span><?php echo thousands_currency_format($api_call_count, true)[1]; ?></div>
          <div class="stat-label">API Call<?php echo return_s($api_call_count); ?></div>
          <div class="stat-meta">(<span class="counter"><?php echo thousands_currency_format($api_call_count_today, true)[0]; ?></span><?php echo thousands_currency_format($api_call_count_today, true)[1]; ?> today)</div>
        </div>
      </div>
    </div>

    <?php if($show_message):?>
      <div class="notice-card">
        <div class="notice-body">
          <?php if(rand(1, 2) == 1):?>
            👋 Howdy! We are thrilled you chose <b>Keydera</b>. We hope you are finding it useful, please consider giving us a <a href="https://codecanyon.net/downloads">5-star rating on Envato</a>. It will help us a lot, Thank you!
          <?php elseif(rand(1, 2) == 2):?>
            👋 Hi there! Looking for custom integration in your applications, themes or plugins? Get in touch with us at <a href="mailto:support@keydera.app?subject=Custom Integration"><b>support@keydera.app</b></a> and let us do all the hard work.
          <?php else:?>
            👋 Hey! You can also follow us on Twitter at <a href="https://www.twitter.com/keyderaapp"><b>@KeyderaApp</b></a> to stay updated with important product announcements and future updates, Thank you!
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- Charts -->
    <div class="charts-grid">
      <div class="chart-card">
        <div class="chart-header">
          <h3 class="section-title"><i class="fas fa-chart-pie"></i> Licenses Share</h3>
          <p class="section-subtitle">Distribution of active vs inactive licenses</p>
        </div>
        <div class="chart-content">
          <div id="chartContainer" class="chart-body">
            <div id="chart-loading" class="chart-loading">
              <i class="fas fa-spinner fa-spin"></i>
              <span>Loading chart data...</span>
            </div>
          </div>
        </div>
      </div>
      <div class="chart-card">
        <div class="chart-header">
          <h3 class="section-title"><i class="fas fa-chart-line"></i> Monthly Trends</h3>
          <p class="section-subtitle">New Licenses/Activations/Downloads (<?php echo date('F Y'); ?>)</p>
        </div>
        <div class="chart-content">
          <div id="chartContainer2" class="chart-body">
            <div id="chart-loading2" class="chart-loading">
              <i class="fas fa-spinner fa-spin"></i>
              <span>Loading chart data...</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Activities -->
    <div class="activities-card">
      <div class="activities-header">
        <div class="header-left">
          <h3 class="section-title"><i class="fas fa-list"></i> Activities (Past 24 hours)
            <i class="fas fa-question-circle help-icon" data-tooltip="Activites done by you will be marked with a 'done by <?php echo $this->session->userdata('fullname'); ?>' text, all other activities are either done by the System, Cron Job or the API."></i>
          </h3>
        </div>
        <div class="header-actions">
          <a href="<?php echo base_url(); ?>activities" class="btn btn-secondary">View All</a>
        </div>
      </div>
      <div id="inbox-messages" class="activities-body">
        <?php 
        if(!empty($activity_logs)):
          foreach($activity_logs as $log): ?>
          <div class="activity-item">
            <div class="activity-meta">
              <?php  $originalDate = $log['al_date'];
              $newDate = date($this->config->item('datetime_format'), strtotime($originalDate));
              echo $newDate; ?>
            </div>
            <div class="activity-content"><?php echo $log['al_log']; ?></div>
          </div>
        <?php endforeach; 
        else: ?>
          <div class="activity-empty">No new activity to show.</div>
        <?php
        endif;
        ?>
      </div>
    </div>
  </div>
</div>

<style>
.page-header { margin-bottom: 1.5rem; }
.page-title { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); }

.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.25rem; }
.stat-card { display: flex; gap: 1rem; align-items: center; background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 1rem; text-decoration: none; color: inherit; transition: transform .15s ease, box-shadow .2s; }
.stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.stat-icon { width: 44px; height: 44px; border-radius: 10px; background: var(--bg-main); display: inline-flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.1rem; flex-shrink: 0; border: 1px solid var(--border); }
.stat-content { display: flex; flex-direction: column; }
.stat-value { font-size: 1.35rem; font-weight: 700; color: var(--text-primary); line-height: 1; }
.stat-label { color: var(--text-secondary); font-weight: 500; }
.stat-meta { color: var(--text-muted); font-size: .85rem; }

.notice-card { background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; border-radius: 12px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; }
.notice-body a { color: #92400E; text-decoration: underline; }

.charts-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; margin-bottom: 1.5rem; }
.chart-card { background: var(--bg-card); border-radius: 12px; box-shadow: var(--shadow); border: 1px solid var(--border); overflow: hidden; }
.chart-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); background: var(--bg-main); }
.chart-content { padding: 1rem; }
.chart-body { height: 320px; width: 100%; position: relative; }
.chart-loading { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: flex; flex-direction: column; align-items: center; gap: .75rem; color: var(--text-secondary); }
.chart-loading i { font-size: 1.5rem; }
.chart-loading span { font-size: .9rem; }
.section-title { font-size: 1.1rem; font-weight: 600; color: var(--text-primary); display: inline-flex; align-items: center; gap: .5rem; margin: 0; }
.section-title i { color: var(--primary); }
.section-subtitle { color: var(--text-secondary); font-size: .85rem; margin: .25rem 0 0; }

.activities-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
.activities-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.activities-body { max-height: 575px; overflow-y: auto; padding: .75rem 1.25rem; }
.activity-item { padding: .75rem 0; border-bottom: 1px solid var(--border); display: grid; grid-template-columns: 180px 1fr; gap: 1rem; align-items: start; }
.activity-item:last-child { border-bottom: none; }
.activity-meta { color: var(--text-muted); font-size: .85rem; }
.activity-content { color: var(--text-primary); }
.activity-empty { text-align: center; color: var(--text-secondary); padding: 1rem 0; font-weight: 500; }

.btn { padding: .55rem .9rem; border-radius: 8px; font-weight: 500; font-size: .85rem; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: .5rem; border: none; text-decoration: none; }
.btn-secondary { background: #fff; color: var(--text-secondary); border: 2px solid var(--border); }
.btn-secondary:hover { background: var(--bg-main); border-color: var(--text-muted); }

@media (max-width: 1024px) {
  .charts-grid { grid-template-columns: 1fr; }
  .activity-item { grid-template-columns: 1fr; }
}
</style>
=======
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
                <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(KEYDERA_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">Product<?php echo return_s($product_count); ?></p>
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
              <span class="m-r-md widget-background <?php echo (strtolower(KEYDERA_THEME)=="classic")?"has-text-warning":"has-text-link"; ?>">
                <i class="fas fa-key"></i>
              </span>
              <div class="media-body">
                <h3 class="m-b-none has-text-black"><span class="m-l-none counter"><?php echo thousands_currency_format($license_count, true)[0]; ?></span><?php echo thousands_currency_format($license_count, true)[1]; ?></span></h3>
                <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(KEYDERA_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">License<?php echo return_s($license_count); ?></p>
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
              <span class="m-r-md widget-background <?php echo (strtolower(KEYDERA_THEME)=="classic")?"has-text-success":"has-text-link"; ?>">
                <i class="fas fa-hdd"></i>
              </span>
              <div class="media-body">
                <h3 class="m-b-none has-text-black"><span class="m-l-none counter"><?php echo thousands_currency_format($installation_count, true)[0]; ?></span><?php echo thousands_currency_format($installation_count, true)[1]; ?></span></h3>
                <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(KEYDERA_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">Activation<?php echo return_s($installation_count); ?></p>
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
              <span class="m-r-md widget-background <?php echo (strtolower(KEYDERA_THEME)=="classic")?"has-text-dark":"has-text-link"; ?>">
                <i class="fas fa-download"></i>
              </span>
              <div class="media-body">
                <h3 class="m-b-none has-text-black"><span class="m-l-none counter"><?php echo thousands_currency_format($download_count, true)[0]; ?></span><?php echo thousands_currency_format($download_count, true)[1]; ?></span></h3>
                <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(KEYDERA_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">Update Download<?php echo return_s($download_count); ?></p>
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
            <span class="m-r-md widget-background <?php echo (strtolower(KEYDERA_THEME)=="classic")?"has-text-info":"has-text-link"; ?>">
              <i class="fas fa-server"></i>
            </span>
            <div class="media-body">
              <h3 class="m-b-none has-text-black"><span class="m-l-none counter"><?php echo thousands_currency_format($api_call_count, true)[0]; ?></span><?php echo thousands_currency_format($api_call_count, true)[1]; ?></span></h3>
              <p class="m-b-none has-text-grey-dark is-size-5-mobile is-size-6-5-tablet is-size-5-desktop <?php echo (strtolower(KEYDERA_THEME)=="classic")?"has-text-white":" "; ?> shrink-text">API Call<?php echo return_s($api_call_count); ?></p>
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
      👋 Howdy! We are thrilled you chose <b>Keydera</b>. We hope you are finding it useful, please consider giving us a <a href="https://codecanyon.net/downloads">5-star rating on Envato</a>. It will help us a lot, Thank you!
    <?php elseif(rand(1, 2) == 2):?>
      👋 Hi there! Looking for custom integration in your applications, themes or plugins? Get in touch with us at <a href="mailto:support@keydera.app?subject=Custom Integration"><b>support@keydera.app</b></a> and let us do all the hard work.
    <?php else:?>
      👋 Hey! You can also follow us on Twitter at <a href="https://www.twitter.com/keyderaapp"><b>@KeyderaApp</b></a> to stay updated with important product announcements and future updates, Thank you!
    <?php endif; ?>
  </div>
</article>
<?php endif; ?>
<div class="columns">
  <div class="column is-one-third">
    <article class="tile is-child box has-shadow2">
        <p class="subtitle" style="color:<?php echo (strtolower(KEYDERA_THEME)=="flat")?"#363636":"#0a0a0a"; ?>;font-size: 1.1rem;font-weight: <?php echo (strtolower(KEYDERA_THEME)=="material")?"500":"600"; ?>;">Licenses Share</p>
        <div id="chartContainer" style="height: 300px; width: 100%;"><div id="chart-loading"><center><i class="subtitle fa fa-spinner fa-spin"></i></center></div></div>
    </article>
  </div>
  <div class="column">
    <article class="tile is-child box has-shadow2">
        <p class="subtitle" style="color:<?php echo (strtolower(KEYDERA_THEME)=="flat")?"#363636":"#0a0a0a"; ?>;font-size: 1.1rem;font-weight: <?php echo (strtolower(KEYDERA_THEME)=="material")?"500":"600"; ?>;">New Licenses/Activations/Downloads (<?php echo date('F Y'); ?>)</p>
        <div id="chartContainer2" style="height: 300px; width: 100%;"><div id="chart-loading2"><center><i class="subtitle fa fa-spinner fa-spin"></i></center></div></div>
    </article>
  </div>
</div>
<article class="message <?php echo (strtolower(KEYDERA_THEME)=="classic")?"is-primary":((strtolower(KEYDERA_THEME)=="flat")?"is-link":"is-white"); ?> has-shadow2">
  <div class="message-header" style="border-radius: .5rem .5rem 0 0">
    <p>Activities (Past 24 hours) <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="Activites done by you will be marked with a 'done by <?php echo $this->session->userdata('fullname'); ?>' text, all other activities are either done by the System, Cron Job or the API." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></p><a href="<?php echo base_url(); ?>activities" class="button is-small is-rounded <?php echo (strtolower(KEYDERA_THEME)=="material")?"is-link":"is-white"; ?> is-pulled-right">View All</a>
  </div>
  <div id="inbox-messages" class="inbox-messages" style="max-height: 575px;overflow-y: auto;">
    <?php 
    if(!empty($activity_logs)):
      foreach($activity_logs as $log): ?>
    <div class="card <?php echo (strtolower(KEYDERA_THEME)=="material")?"has-default-shadows":""; ?>">
      <div class="card-content">
        <small><?php  $originalDate = $log['al_date'];
         $newDate = date($this->config->item('datetime_format'), strtotime($originalDate));
         echo $newDate; ?></small>
        <div><?php echo $log['al_log']; ?></div>
     </div>
   </div>
   <?php endforeach; 
   else: ?>
    <div class="card <?php echo (strtolower(KEYDERA_THEME)=="material")?"has-default-shadows":""; ?>">
      <div class="card-content has-text-centered">
        <p class="<?php echo (strtolower(KEYDERA_THEME)!="material")?"has-text-weight-semibold":"" ?>">No new activity to show.</p>
      </div></div>
      <?php
    endif;
    ?>
  </div>
</article>
</div>
</div>
>>>>>>> origin/main
