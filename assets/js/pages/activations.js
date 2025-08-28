(function(){
  if (!(KD.route.cls === 'activations' && KD.route.method === 'index')) return;
  $(function(){
    var activations_table = $('#activations_table').DataTable({
      processing: true,
      responsive: true,
      serverSide: true,
      aLengthMenu: [[10,25,50,100,500],[10,25,50,100,500]],
      iDisplayLength: 25,
      order: [[6,'desc']],
      dom: 't<"dt-footer"ip>',
      language: {
        processing: "<i class='fas fa-sync-alt  fa-spin'></i> Loading. Please wait...",
        emptyTable: "No activations found.",
        zeroRecords: "No matching activations found.",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "",
        infoFiltered: "(filtered from _MAX_ total entries)"
      },
      ajax: { url: KD.baseUrl + 'activations/get_activations', dataType: 'json', type: 'POST', data: function(d){ d[KD.csrf.name] = KD.csrf.hash; return d; } },
      error: function(){ $('#activations_table').html(''); $('#activations_table_processing').hide(); },
      columnDefs: [ { orderable: false, targets: [8] }, { orderable: false, width: 20, targets: [0] } ],
      deferRender: true
    });

    var actDtContainer = activations_table.table().container ? $(activations_table.table().container()) : $('#activations_table').closest('.dataTables_wrapper');
    actDtContainer.find('.dataTables_length, .dataTables_filter').hide();
    $('#activations_table').wrap('<div class="dataTables_scroll" />');
    $.fn.dataTable.ext.errMode = 'throw';

    // Custom search & filter
    $('#activation-search').on('keyup change', function(){ activations_table.search(this.value).draw(); });
    $('#activation-status-filter').on('change', function(){
      var val = this.value;
      if (!val) activations_table.column(7).search('').draw();
      else if (val === 'valid') activations_table.column(7).search('Valid', true, false).draw();
      else if (val === 'invalid') activations_table.column(7).search('Invalid', true, false).draw();
      else if (val === 'blocked') activations_table.column(7).search('Blocked', true, false).draw();
    });

    function updateActivationStats(){
      var info = activations_table.page.info();
      var rows = activations_table.rows({ search: 'applied' }).data();
      var valid = 0, invalid = 0, blocked = 0;
      for (var i=0;i<rows.length;i++){
        var statusCell = KD.normalizeText(rows[i][7]);
        if (statusCell === 'valid') valid++; else if (statusCell === 'invalid') invalid++;
        if (statusCell === 'blocked') blocked++;
      }
      $('#total-activations').text(info.recordsTotal ?? info.recordsDisplay ?? rows.length);
      $('#valid-activations').text(valid);
      $('#invalid-activations').text(invalid);
      $('#blocked-activations').text(blocked);
      KD.hideFooterIfSinglePage(activations_table);
    }
    activations_table.on('draw', updateActivationStats);
    updateActivationStats();

    // Bulk bar wiring
    function updateActivationsBulkBar(){
      var selected = $('.delete_activation_checkbox:checked').length;
      $('#activations-selected-count').text(selected);
      if (selected > 0) { $('#activations-bulk-actions').slideDown(120); } else { $('#activations-bulk-actions').slideUp(120); }
    }
    $(document).on('change click', '.delete_activation_checkbox, #delete_activation_select_all', updateActivationsBulkBar);
    activations_table.on('draw', updateActivationsBulkBar);

      // Select all handling
      $(document).on('click', '#delete_activation_select_all', function(){
        var checked = this.checked;
        $('.delete_activation_checkbox').each(function(){ this.checked = checked; $(this).closest('tr').toggleClass('removeRow', checked); });
      });
      $(document).on('click', '.delete_activation_checkbox', function(){
        $('#delete_activation_select_all').prop('checked', $('.delete_activation_checkbox:checked').length == $('.delete_activation_checkbox').length);
        $(this).closest('tr').toggleClass('removeRow', $(this).is(':checked'));
      });

      // Bulk delete: Support both new and legacy buttons
      $(document).on('click', '#activation-bulk-delete, #delete_selected_activation', function(e){
        e.preventDefault();
        var checkbox = $('.delete_activation_checkbox:checked');
        if(checkbox.length === 0){
          Bulma.create('notification', { body: 'Please select at least one activation for bulk deleting.', dismissInterval: 4000, isDismissable: true, color: 'danger', parent: document.getElementById('delete_notification') }).show();
          return;
        }
        Bulma.create('alert', {
          type: 'danger', title: 'Are you sure to delete selected activations?', body: 'Please note that this action cannot be undone.',
          confirm: { label: 'Confirm', classes: ['modal-button-small','modal-button-confirm'], onClick: function(){
            var values = []; $(checkbox).each(function(){ values.push($(this).val()); });
            var data = {}; data[KD.csrf.name] = KD.csrf.hash; data['delete_activations_checkbox'] = values;
            $.post(KD.baseUrl + 'activations/delete_selected', data, function(){
              $('.removeRow').fadeOut(1500);
              setTimeout(function(){ activations_table.ajax.reload(); Bulma.create('notification', { body: 'Selected activations were successfully deleted.', dismissInterval: 4000, isDismissable: true, color: 'success', parent: document.getElementById('delete_notification') }).show(); }, 1500);
            });
          } },
          cancel: { label: 'Cancel', classes: ['modal-button-small','modal-button-cancel'], onClick: function(){ return false; } }
        });
      });
  });
})();
