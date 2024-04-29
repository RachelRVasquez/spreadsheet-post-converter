(function ($) {
	'use strict';

	ready( spcHandleSpreadsheetUpload );

	function ready(fn) {
		if (document.readyState !== 'loading') {
		  fn();
		} else {
		  document.addEventListener('DOMContentLoaded', fn );
		}
	  }

	  function spcHandleSpreadsheetUpload(){
		const spcForm = document.getElementById('spc-upload-spreadsheet-form'),
		spcLoading    = document.getElementById('spc-loading');
	
		if ( 'undefined' === typeof spcForm || null === spcForm ) {
			return;
		}
		
		spcForm.addEventListener('submit', function (event) {
			event.preventDefault();
			spcLoading.classList.remove('hidden');
	
			$.ajax({
				url: wpApiSettings.root + 'spreadsheet-converter/v1/upload-spreadsheet-data',
				method: "POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success: function (data) {
					console.log('success', data);

					spcLoading.classList.add('hidden');
					$('#spc-excel-area').html(data['message']);
				},
				error: function (data) {
					console.log('error', data);
					spcLoading.classList.add('hidden');
				}
			});
		});
	  }

	
	

})(jQuery);
