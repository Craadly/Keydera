(function(){
  if (!(KD.route.cls === 'licenses' && KD.route.method === 'index')) return;

  $(function(){
    var licenses_table = $('#licenses_table').DataTable({
      processing: true,
      responsive: true,
      serverSide: true,
      aLengthMenu: [[10,25,50,100,500],[10,25,50,100,500]],
      iDisplayLength: 25,
      order: [[4,'desc']],
      dom: 't<"dt-footer"ip>',
      language: {
        processing: "<i class='fas fa-sync-alt fa-spin'></i> Loading. Please wait...",
        emptyTable: "No licenses found.",
        zeroRecords: "No matching licenses found.",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "",
        infoFiltered: "(filtered from _MAX_ total entries)"
      },
      ajax: {
        url: KD.baseUrl + 'licenses/get_licenses',
        dataType: 'json',
        type: 'POST',
        data: function(d){ d[KD.csrf.name] = KD.csrf.hash; return d; }
      },
      error: function(){ $('#licenses_table').html(''); $('#licenses_table_processing').hide(); },
      columnDefs: [
        { orderable: false, width: 150, targets: [8] },
        { orderable: false, width: 20, targets: [0] }
      ],
      deferRender: true
    });

    // Hide default controls defensively
    var dtContainer = licenses_table.table().container ? $(licenses_table.table().container()) : $('#licenses_table').closest('.dataTables_wrapper');
    dtContainer.find('.dataTables_length, .dataTables_filter').hide();
    $('#licenses_table').wrap('<div class="dataTables_scroll" />');
    $.fn.dataTable.ext.errMode = 'throw';

    // Search + Filter
    $('#license-search').on('keyup change', function(){ licenses_table.search(this.value).draw(); });
    $('#status-filter').on('change', function(){
      var val = this.value;
      if (!val) licenses_table.column(7).search('').draw();
      else if (val === 'valid') licenses_table.column(7).search('Valid', true, false).draw();
      else if (val === 'invalid') licenses_table.column(7).search('Invalid', true, false).draw();
      else if (val === 'blocked') licenses_table.column(7).search('Blocked', true, false).draw();
    });

    function updateStats(){
      var info = licenses_table.page.info();
      var rows = licenses_table.rows({ search: 'applied' }).data();
      var active = 0, inactive = 0, blocked = 0;
      for (var i=0;i<rows.length;i++){
        var usageCell = KD.normalizeText(rows[i][6]);
        var statusCell = KD.normalizeText(rows[i][7]);
        if (usageCell === 'active') active++; else if (usageCell === 'inactive') inactive++;
        if (statusCell === 'blocked') blocked++;
      }
      $('#total-licenses').text(info.recordsTotal ?? info.recordsDisplay ?? rows.length);
      $('#active-licenses').text(active);
      $('#inactive-licenses').text(inactive);
      $('#blocked-licenses').text(blocked);

      // Toggle footer + empty state
      KD.hideFooterIfSinglePage(licenses_table);
      var totalRecords = info && info.recordsTotal ? info.recordsTotal : 0;
      if (totalRecords === 0) {
        $('#licenses_table').closest('.table-wrapper').hide();
        $('#empty-state-cta').show();
      } else {
        $('#empty-state-cta').hide();
        $('#licenses_table').closest('.table-wrapper').show();
      }
    }

    licenses_table.on('draw', updateStats);
    updateStats();

    // Bulk delete wiring
    $('body').on('click', '#delete_license_select_all', function(){
      var checked = this.checked;
      $('.delete_license_checkbox').each(function(){ this.checked = checked; $(this).closest('tr').toggleClass('removeRow', checked); });
    });
    $('body').on('click', '.delete_license_checkbox', function(){
      $('#delete_license_select_all').prop('checked', $('.delete_license_checkbox:checked').length == $('.delete_license_checkbox').length);
      $(this).closest('tr').toggleClass('removeRow', $(this).is(':checked'));
    });

  function performBulkDelete(){
      var checkbox = $('.delete_license_checkbox:checked');
      if(checkbox.length === 0){
        Bulma.create('notification', { body: 'Please select at least one license for bulk deleting.', dismissInterval: 4000, isDismissable: true, color: 'danger', parent: document.getElementById('delete_notification') }).show();
        return;
      }
      Bulma.create('alert', {
        type: 'danger',
        title: 'Are you sure to delete selected licenses?',
        body: 'Please note that all of the relevant records like (activation logs) for each license will also be permanently deleted.',
        confirm: {
          label: 'Confirm', classes: ['modal-button-small', 'modal-button-confirm'], onClick: function(){
            var checkbox_value = []; $(checkbox).each(function(){ checkbox_value.push($(this).val()); });
            var data = {}; data[KD.csrf.name] = KD.csrf.hash; data['delete_licenses_checkbox'] = checkbox_value;
            $.post(KD.baseUrl + 'licenses/delete_selected', data, function(){
              $('.removeRow').fadeOut(1500); setTimeout(function(){ licenses_table.ajax.reload(); Bulma.create('notification', { body: 'Selected licenses were successfully deleted.', dismissInterval: 4000, isDismissable: true, color: 'success', parent: document.getElementById('delete_notification') }).show(); }, 1500);
            });
          }
        },
        cancel: { label: 'Cancel', classes: ['modal-button-small', 'modal-button-cancel'], onClick: function(){ return false; } }
      });
    }

  // Bind both new and legacy buttons
  $(document).on('click', '#bulk-delete, #delete_selected_license', performBulkDelete);
  });
})();
