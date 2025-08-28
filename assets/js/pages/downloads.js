(function(){
  if (!(KD.route.cls === 'downloads' && KD.route.method === 'index')) return;
  $(function(){
    var downloads_table = $('#downloads_table').DataTable({
      processing: true,
      responsive: true,
      serverSide: true,
      aLengthMenu: [[10,25,50,100,500],[10,25,50,100,500]],
      iDisplayLength: 25,
      order: [[5,'desc']],
      dom: 't<"dt-footer"ip>',
      language: { processing: "<i class='fas fa-sync-alt  fa-spin'></i> Loading. Please wait...", emptyTable: "No update downloads found.", zeroRecords: "No matching update downloads found.", info: "Showing _START_ to _END_ of _TOTAL_ entries", infoEmpty: "", infoFiltered: "(filtered from _MAX_ total entries)" },
      ajax: { url: KD.baseUrl + 'downloads/get_update_downloads', dataType: 'json', type: 'POST', data: function(d){ d[KD.csrf.name] = KD.csrf.hash; return d; } },
      error: function(){ $('#downloads_table').html(''); $('#downloads_table_processing').hide(); },
      columnDefs: [ { orderable: false, targets: [7] }, { orderable: false, width: 20, targets: [0] } ],
      deferRender: true
    });

    var dlDtContainer = downloads_table.table().container ? $(downloads_table.table().container()) : $('#downloads_table').closest('.dataTables_wrapper');
    dlDtContainer.find('.dataTables_length, .dataTables_filter').hide();
    $('#downloads_table').wrap('<div class="dataTables_scroll" />');
    $.fn.dataTable.ext.errMode = 'throw';

    // Custom search & filter
    $('#download-search').on('keyup change', function(){ downloads_table.search(this.value).draw(); });
    $('#download-status-filter').on('change', function(){
      var val = this.value;
      if (!val) downloads_table.column(6).search('').draw();
      else if (val === 'valid') downloads_table.column(6).search('Valid', true, false).draw();
      else if (val === 'invalid') downloads_table.column(6).search('Invalid', true, false).draw();
      else if (val === 'blocked') downloads_table.column(6).search('Blocked', true, false).draw();
    });

    function updateDownloadStats(){
      var info = downloads_table.page.info();
      var rows = downloads_table.rows({ search: 'applied' }).data();
      var valid = 0, invalid = 0, blocked = 0;
      for (var i=0;i<rows.length;i++){
        var statusCell = KD.normalizeText(rows[i][6]);
        if (statusCell === 'valid') valid++; else if (statusCell === 'invalid') invalid++;
        if (statusCell === 'blocked') blocked++;
      }
      $('#total-downloads').text(info.recordsTotal ?? info.recordsDisplay ?? rows.length);
      $('#valid-downloads').text(valid);
      $('#invalid-downloads').text(invalid);
      $('#blocked-downloads').text(blocked);

      KD.hideFooterIfSinglePage(downloads_table);
      var totalRecords = info && info.recordsTotal ? info.recordsTotal : 0;
      if (totalRecords === 0) {
        $('#downloads_table').closest('.table-wrapper').hide();
        $('#downloads-empty-state-cta').show();
      } else {
        $('#downloads-empty-state-cta').hide();
        $('#downloads_table').closest('.table-wrapper').show();
      }
    }

    downloads_table.on('draw', updateDownloadStats);
    updateDownloadStats();
      // Select all handling
      $(document).on('click', '#delete_download_select_all', function(){
        var checked = this.checked;
        $('.delete_download_checkbox').each(function(){ this.checked = checked; $(this).closest('tr').toggleClass('removeRow', checked); });
      });
      $(document).on('click', '.delete_download_checkbox', function(){
        $('#delete_download_select_all').prop('checked', $('.delete_download_checkbox:checked').length == $('.delete_download_checkbox').length);
        $(this).closest('tr').toggleClass('removeRow', $(this).is(':checked'));
      });

      // Bulk delete: Support both new and legacy buttons
      $(document).on('click', '#download-bulk-delete, #delete_selected_download', function(e){
        e.preventDefault();
        var checkbox = $('.delete_download_checkbox:checked');
        if(checkbox.length === 0){
          Bulma.create('notification', { body: 'Please select at least one update download log for bulk deleting.', dismissInterval: 4000, isDismissable: true, color: 'danger', parent: document.getElementById('delete_notification') }).show();
          return;
        }
        Bulma.create('alert', {
          type: 'danger', title: 'Are you sure to delete selected download logs?', body: 'Please note that this action cannot be undone.',
          confirm: { label: 'Confirm', classes: ['modal-button-small','modal-button-confirm'], onClick: function(){
            var values = []; $(checkbox).each(function(){ values.push($(this).val()); });
            var data = {}; data[KD.csrf.name] = KD.csrf.hash; data['delete_downloads_checkbox'] = values;
            $.post(KD.baseUrl + 'update_downloads/delete_selected', data, function(){
              $('.removeRow').fadeOut(1500);
              setTimeout(function(){ downloads_table.ajax.reload(); Bulma.create('notification', { body: 'Selected update download logs were successfully deleted.', dismissInterval: 4000, isDismissable: true, color: 'success', parent: document.getElementById('delete_notification') }).show(); }, 1500);
            });
          } },
          cancel: { label: 'Cancel', classes: ['modal-button-small','modal-button-cancel'], onClick: function(){ return false; } }
        });
      });
  });
})();
