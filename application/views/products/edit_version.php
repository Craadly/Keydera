<div class="container is-fluid main_body"> 
<div class="section">
  <h1 class="title">
    <?php echo $title; ?>
  </h1>
  <?php echo generate_breadcrumb(); ?>
  <?php if($this->session->flashdata('product_status')): ?>
    <?php $flash = $this->session->flashdata('product_status');
    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
  <?php endif; ?>
  <?php if($this->session->flashdata('upload_status_main')): ?>
    <?php $flash = $this->session->flashdata('upload_status_main');
    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
  <?php endif; ?>
  <?php if($this->session->flashdata('upload_status_sql')): ?>
    <?php $flash = $this->session->flashdata('upload_status_sql');
    echo ' <div class="notification is-'.$flash['type'].'"><button class="delete"></button>'.$flash['message'].'</div>'; ?>
  <?php endif; ?>
  <div class="box">
    <?php 
      $hidden = array('product' => $this->input->post('product'), 'version_old' => $version['version'], 'vid' => $version['vid']);
      echo form_open_multipart('products/versions/edit', array('id' => 'edit_version_form'),$hidden); ?>
      <div class="field">
        <label class="label">Version <small class="tooltip is-tooltip-multiline is-tooltip-right" data-tooltip="The system will be comparing the version values so make sure you follow a pattern in your version names." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
        <div class="control">
          <input class="input" type="text" name="version" maxlength="255" value="<?php if(!empty(set_value('version'))) {
          echo set_value('version');
        }
        else{ 
          echo $version['version']; 
        } ?>" placeholder="Product version eg. v1.0.0" required>
        </div>
      </div>
      <div class="field">
        <label class="label">Release Date</label>
        <div class="control">
          <input class="input date-time-picker" type="text" name="released" value="<?php if(!empty(set_value('released'))) {
          echo set_value('released');
        }
        else{ 
          echo $version['release_date']; 
        } ?>" placeholder="Version release date" required>
        </div>
      </div>
      <div class="field">
        <label class="label">Update Notification/Summary</label>
        <div class="control">
          <input class="input" type="text" name="summary" maxlength="255" value="<?php if(!empty(set_value('summary'))) {
          echo set_value('summary');
        }
        else{ 
          echo $version['summary']; 
        } ?>" placeholder="e.g. This update brings major improvements, upgrade now!">
        </div>
      </div>
      <div class="field">
        <label class="label">Changelog</label>
        <div class="content">
          <div class="control">
            <textarea class="textarea" name="changelog" id="changelog" rows="10" placeholder="Enter the update changelog here..." required>
              <?php if(!empty(set_value('changelog'))) {
          echo (!empty(set_value('changelog'))?set_value('changelog'):null);
        }
        else{ 
          echo $version['changelog']; 
        } ?>
            </textarea>
          </div>
      </div>
      </div>
      <div class="columns">
        <div class="column">
           <div class="field">
            <label class="label">Main files (.zip) [Only upload if you want to change files] <small class="tooltip is-tooltip-multiline is-tooltip-right " data-tooltip="These files will be extracted/replaced in the application root so structure it accordingly also make sure you include the updated helper file which has the 'current_version' value changed to the new version." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <input type="file" class="dropify" name="main_file" data-height="150" data-max-file-size="<?php $max_size_possible = get_file_upload_max_size();
            $get_max_upload_size = ($max_size_possible<0)?0:$max_size_possible;
            echo convert_kb($get_max_upload_size); ?>" data-allowed-file-extensions="zip" accept=".zip"/>
          </div>
        </div>
        <div class="column">
          <div class="field">
            <label class="label">Sql file (.sql) [Only upload if you want to change files] <small class="tooltip is-tooltip-multiline is-tooltip-left " data-tooltip="Add your update SQL file here, It will automatically get imported into your client's database during the update process." style="font-weight: 400;"><i class="fas fa-question-circle"></i></small></label>
            <input type="file" class="dropify" name="sql_file" data-height="150" data-max-file-size="<?php $max_size_possible = get_file_upload_max_size();
            $get_max_upload_size = ($max_size_possible<0)?0:$max_size_possible;
            echo convert_kb($get_max_upload_size); ?>" data-allowed-file-extensions="sql" accept=".sql"/>
          </div>
        </div>
      </div>
      <div class="field">
        <?php if($version['status']):?>
          <input class="is-checkradio is-primary" type="checkbox" name="version_status" id="version_status" checked="">
        <?php else: ?>
          <input class="is-checkradio is-primary" type="checkbox" name="version_status" id="version_status">
        <?php endif; ?>
        <label for="version_status" style="margin-left: 0px !important;">Publish this version? <small class="tooltip is-tooltip-multiline " data-tooltip="This version will become available to your clients as soon as you add it. You can also publish it later from the manage versions page."><i class="fas fa-question-circle"></i></small></label>
      </div>

      <div class="field p-t-smis-grouped">
        <div class="control">
          <button type="submit" id="add_version_form_submit" class="button is-link">Submit</button>
        </div>
      </div>
  </form>
</div>
</div> 
</div>
<script src="<?php echo base_url(); ?>assets/vendor/Ckeditor/ckeditor.js"></script>
<script>
  ClassicEditor.create(document.querySelector('#changelog'), {
    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo'],
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
</script>