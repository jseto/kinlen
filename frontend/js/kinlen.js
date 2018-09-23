(function( $ ) {
"use strict";
  $( document ).ready( function(){
    $('#bookingButton').on( 'click', function() {
      openTab( 'detail-tab', 'Book Now' );
    });
  });

  /**
   * @description Opens a tab from the Elementor Tab widget
   * @param tabWidgetId is the id of the whole Elementor Tab widget. You can
   *                     customize in Elementor's advanced settings
   * @param tabToOpen identifies the tab number or tab title to openTab
   */

  function openTab( tabWidgetId, tabToOpen ) {
    var tabObject = $( '#' + tabWidgetId );
    var activeObject =  tabObject.find( '.elementor-active' );
    if (typeof tabToOpen === 'string' || tabToOpen instanceof String) {
      tabToOpen = tabObject.find( '.elementor-tab-title' ).filter( ':contains("' + tabToOpen + '")' ).attr('data-tab');
    }
    var tabObjectToOpen = tabObject.find( "[data-tab='" + tabToOpen + "']" );
    activeObject.removeClass( 'elementor-active' );
    tabObjectToOpen.addClass('elementor-active');
    activeObject.filter('.elementor-tab-content').hide();
    tabObjectToOpen.filter('.elementor-tab-content').show();
  }


})(jQuery);
