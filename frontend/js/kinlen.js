(function( $ ) {
"use strict";
	var calendarMng;

  $( document ).ready( function(){
    $('#bookingButton').on( 'click', function() {
      openTab( 'detail-tab', 'Book Now' );
    });
		calendarMng = KinlenBooking.datePickerManager( 1 );
		var calendar = $('#form-field-bookingDateInput');
		calendar.ready( /*'click', */function(){
			console.log(calendar.flatpickr().currentMonth);
			calendar.flatpickr({
//				disable: ["2018-11-30", "2018-11-21", "2018-11-08", new Date(2018, calendar.flatpickr().currentMonth, 9)],
		    onMonthChange: setDisabledDates,
				onOpen: setDisabledDates
			});
		});
		calendar.change( function(){
			console.log('fgf');
		});
  });

	function setDisabledDates( selectedDates, dateStr, instance ) {
		calendarMng.updateDates( instance );
		// instance.config.disable = [ new Date(2018, instance.currentMonth, 7)];
		// instance.redraw();
		// console.log('monthChange ', instance.currentMonth);
	}

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
