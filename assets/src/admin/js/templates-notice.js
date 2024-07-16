document.addEventListener('DOMContentLoaded', function () {
	var importButton = document.getElementById('mbt-notice-import-templates-patterns');
    console.log(importButton);
	if (importButton) {
		importButton.addEventListener('click', function () {
			var xhr = new XMLHttpRequest();
			xhr.open('POST', maxiblocks.ajaxurl);
			xhr.setRequestHeader(
				'Content-Type',
				'application/x-www-form-urlencoded'
			);
			xhr.onload = function () {
				if (xhr.status === 200) {
					var response = JSON.parse(xhr.responseText);
					if (response.success) {
						console.log(response.data);
						// Display success message or perform any other actions
					} else {
						console.error('Error:', response.data);
						// Display error message or perform any other actions
					}
				} else {
					console.error('AJAX error:', xhr.status);
					// Display error message or perform any other actions
				}
			};
			xhr.onerror = function () {
				console.error('AJAX error:', xhr.status);
				// Display error message or perform any other actions
			};
			var data = 'action=mbt_copy_patterns&nonce=' + maxiblocks.nonce;
			xhr.send(data);
		});
	}
});
