<div class="main-content main-content-expanded">
  <div class="section">
    <div class="page-header">
      <h1 class="page-title"><?php echo $title; ?></h1>
    </div>

    <div class="shadcn-card">
      <div class="card-header">
        <h3 class="card-title">Manual Cron Execution</h3>
        <p class="card-description">This page shows the output of the most recent manual cron job execution.</p>
      </div>
      <div class="card-content">
        <label class="form-label">Execution Output</label>
        <pre class="shadcn-textarea" style="height: auto; min-height: 200px; white-space: pre-wrap; line-height: 1.6;"><?php echo trim($cr_response); ?></pre>
        <p class="form-description" style="margin-top: 1rem;">Note: To run the cron job automatically, you must add it to your server's scheduler (e.g., a crontab). See the documentation for details.</p>
      </div>
      <div class="card-footer" style="justify-content: space-between; align-items: center;">
        <p class="form-description">Last run: <?php echo date('Y-m-d H:i:s'); ?></p>
        <div style="display: flex; gap: 0.75rem;">
          <button type="button" class="shadcn-button shadcn-button-secondary" onclick="location.reload()">
            <i class="fas fa-redo"></i>
            Run Again
          </button>
          <button type="button" id="copy-cron-output" class="shadcn-button">
            <i class="fas fa-clipboard"></i>
            Copy Output
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var btn = document.getElementById('copy-cron-output');
  if (btn) {
    btn.addEventListener('click', function() {
      var outputText = document.querySelector('pre.shadcn-textarea').innerText;
      navigator.clipboard.writeText(outputText).then(function() {
        // Optional: Show a success message
        var originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(function() {
          btn.innerHTML = originalText;
        }, 2000);
      }, function(err) {
        // Optional: Show an error message
        console.error('Could not copy text: ', err);
      });
    });
  }
});
</script>
