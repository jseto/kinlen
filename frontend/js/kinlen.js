(function( $ ) {
"use strict";
  $( document ).ready( function(){
    $('#bookingButton').on( 'click', function() {
      openTab( 'detail-tab', 'Book Now' );
    });
		var calendar = $('#form-field-bookingDateInput');
		calendar.ready( /*'click', */function(){
			calendar.flatpickr({
				disable: ["2018-11-30", "2018-11-21", "2018-11-08", new Date(2018, 10, 9)],
		    onMonthChange: function( selectedDates, dateStr, instance ) {
//		    	instance.config.disable = ["2018-11-7", "2018-10-7", "2018-12-07"];
		    	instance.config.disable = [ new Date(2018, instance.currentMonth, 7)];
		    	instance.redraw();
				console.log('monthChange ', instance.currentMonth);
				}
			});
		});
		calendar.change( function(){
			console.log('fgf');
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
