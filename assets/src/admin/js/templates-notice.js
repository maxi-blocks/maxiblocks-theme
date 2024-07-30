document.addEventListener('DOMContentLoaded', function () {
	const {
		nonce,
		ajaxUrl,
		adminUrl,
		pluginStatus,
		importing,
		done,
		error,
		view,
	} = maxiblocks;
	var importButton = document.getElementById(
		'mbt-notice-import-templates-patterns'
	);
	if (importButton) {
		var importStatusText = importButton.querySelector('.mbt-button__text');
		var importStatusIcon = importButton.querySelector('.mbt-button__icon');
		var originalButtonText = importStatusText.textContent;

		importButton.addEventListener('click', function () {
			if (importButton.classList.contains('view-templates')) {
				event.preventDefault();
				window.location.href = `${adminUrl}site-editor.php?postType=wp_template&activeView=MaxiBlocks%20Go`;
				return;
			}
			importButton.classList.add('updating-message', 'disabled');
			importStatusText.textContent = importing || 'Importing...';
			importStatusIcon.classList.add('hidden');

			var xhr = new XMLHttpRequest();
			xhr.open('POST', ajaxUrl);
			xhr.setRequestHeader(
				'Content-Type',
				'application/x-www-form-urlencoded'
			);
			xhr.onload = function () {
				if (xhr.status === 200) {
					var response = JSON.parse(xhr.responseText);
					if (response.success) {
						console.log(response.data);
						importButton.classList.replace(
							'updating-message',
							'updated-message'
						);
						importStatusText.textContent = done || 'Done';
						setTimeout(function () {
							importButton.classList.remove(
								'updated-message',
								'disabled'
							);
							importStatusText.textContent =
								view || 'View theme templates';
							importStatusIcon.classList.remove('hidden');
							importButton.classList.add('view-templates');
						}, 1000);
					} else {
						console.error('Error:', response.data);
						importButton.classList.remove(
							'updating-message',
							'disabled'
						);
						importStatusText.textContent =
							error || originalButtonText;
						// Display error message or perform any other actions
					}
				} else {
					console.error('AJAX error:', xhr.status);
					importButton.classList.remove(
						'updating-message',
						'disabled'
					);
					importStatusText.textContent = error || originalButtonText;
					// Display error message or perform any other actions
				}
			};
			xhr.onerror = function () {
				console.error('AJAX error:', xhr.status);
				importButton.classList.remove('updating-message', 'disabled');
				importStatusText.textContent = error || originalButtonText;
				// Display error message or perform any other actions
			};
			var data = 'action=mbt_copy_patterns&nonce=' + nonce;
			xhr.send(data);
		});

		const closeButton = document.querySelector(
			'.mbt-notice .mbt-notice__dismiss'
		);

		/**
		 * Event handler for close button click.
		 * Sends a request to dismiss the notice.
		 */
		if (closeButton) {
			/** @var {HTMLElement} noticeContainer - Container element for the notice. */
			const noticeContainer = document.querySelector('.mbt-notice');

			/**
			 * Hides and removes the notice element from the DOM.
			 */
			const hideAndRemoveNotice = () => noticeContainer?.remove();
			closeButton?.addEventListener('click', async () => {
				const response = await fetch(ajaxUrl, {
					method: 'POST',
					headers: {
						'Content-Type':
							'application/x-www-form-urlencoded; charset=UTF-8',
					},
					body: `action=maxiblocks-go-theme-dismiss-templates-notice&nonce=${nonce}`,
				});

				if (response.status === 200) {
					hideAndRemoveNotice();
				}
			});
		}
	}
});
