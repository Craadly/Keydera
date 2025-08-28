(function(){
  window.KD = window.KD || {};
  // Utilities
  KD.qs = function(sel, ctx){ return (ctx||document).querySelector(sel); };
  KD.qsa = function(sel, ctx){ return Array.prototype.slice.call((ctx||document).querySelectorAll(sel)); };
  KD.on = function(el, ev, fn){ if(el) el.addEventListener(ev, fn); };

  // DataTables helpers
  KD.hideFooterIfSinglePage = function(dt, wrapperSelector){
    if(!dt) return;
    var info = dt.page ? dt.page.info() : null;
    var hasAny = (info && info.recordsDisplay && info.recordsDisplay > 0);
    var hasMultiple = (info && info.pages && info.pages > 1);
    if (wrapperSelector) {
      var wrapper = $(dt.table().container()).closest(wrapperSelector);
      wrapper.find('.dataTables_info, .dataTables_paginate').css('display', hasAny && hasMultiple ? '' : 'none');
    } else {
      $('.dt-footer').css('display', hasAny && hasMultiple ? '' : 'none');
    }
  };

  KD.normalizeText = function(html){
    return $('<div>').html(html).text().trim().toLowerCase();
  };
})();
