document.addEventListener('DOMContentLoaded', function () {
	function checkAndReplaceIframe() {
		const editorPrefix = 'edit-site-patterns-maxiblocks/';

		const { url, directories } = maxiblocks;

		if (!directories) {
			return;
		}

		// Loop through the directories to extract the IDs and get the buttons
		directories.forEach(directory => {
			// Extract the ID part from the URL
			const idPart = directory.split('/').pop(); // Gets the last part of the URL

			// Construct the full ID by prefixing the editorPrefix
			const fullId = `${editorPrefix}${idPart}`;

			// Get the button by ID
			const button = document.getElementById(fullId);

			// If the button exists, add it to the buttons array
			if (
				button &&
				!button.classList.contains('maxiblocks-custom-pattern')
			) {
				const iframe = button.querySelector('iframe');
				const image = button.querySelector(
					'.maxi-blocks-pattern-preview img'
				);
                
				const imgSrc = `${url}${idPart}/preview-${idPart}.webp`;
				const imgAlt = `${idPart} preview image`;

				if (iframe || image) {
                    button.classList.add('maxiblocks-custom-pattern');
					const img = document.createElement('img');
					img.src = imgSrc;
					img.alt = imgAlt;

					// Get the direct parent of the iframe
					if (iframe) {
						const iframeParent = iframe.parentNode;
						
						try {
							// Use the direct parent to replace the iframe with the image
							iframeParent.replaceChild(img, iframe);
						} catch (error) {
							console.error(
								'Error replacing iframe with image:',
								error
							);
							
						}
					} else {
						if (image) {
							image.src = imgSrc;
							image.alt = imgAlt;
						}
					}
					//clearInterval(checkInterval); // Clear the interval once the replacement is done
				}
			} else {
				// WordPress 6.5 fix
				const previewGridsDiv = document.querySelectorAll(
					'div.dataviews-view-grid, div.block-editor-block-patterns-list'
				);

				if (!previewGridsDiv || previewGridsDiv.length === 0) {
					return;
				}

				previewGridsDiv.forEach(previewGridDiv => {
					const gridCards = previewGridDiv.querySelectorAll(
						':scope > .dataviews-view-grid__card, .block-editor-block-patterns-list__list-item'
					);

					if (!gridCards || gridCards.length === 0) {
						return;
					}


					gridCards.forEach(card => {
						if (
							card.classList.contains('maxiblocks-custom-pattern')
						)
							return;
						
						const titleDiv = card.querySelector(
							'div.edit-site-patterns__pattern-title'
						);
						let titleId = card.querySelector(
							'div.block-editor-block-patterns-list__item'
						)?.id;
                        if (titleId && !titleId.includes('maxiblocks/')) {
                            titleId = card.querySelector(
                                'div.block-editor-block-patterns-list__item'
                            )?.getAttribute('aria-label')?.toLowerCase()
                            ?.replace(/\s+/g, '-');
                        }

						
						if (titleDiv || titleId) {
							// Get the text, convert to lowercase, and replace spaces with dashes
							const modifiedText = titleDiv
								? titleDiv?.textContent
										?.toLowerCase()
										?.replace(/\s+/g, '-')
								: titleId?.replace('maxiblocks/', '');

							if (modifiedText === idPart) {
								const src = `${url}${idPart}/preview-${idPart}.webp`;
								const alt = `${modifiedText} preview image`;
								const imageToReplace = card.querySelector(
									'.maxi-blocks-pattern-preview img'
								);

								if (imageToReplace) {
									imageToReplace.src = src;
									imageToReplace.alt = alt;
									card.classList.add('maxiblocks-custom-pattern');
								} else {
									iframeToReplace = card.querySelector(
										'.block-editor-block-preview__container iframe'
									);
									if (iframeToReplace) {
										const img =
											document.createElement('img');
										img.src = src;
										img.alt = alt;
										iframeToReplace.replaceWith(img);
										card.classList.add('maxiblocks-custom-pattern');
									}
								}
							}
						}
					});
				});
			}
		});
	}

	// Set an interval to periodically check for the button and the iframe
	const checkInterval = setInterval(checkAndReplaceIframe, 500); // Check every 500 milliseconds

	// Optionally, you can also set a timeout to stop checking after a certain period
	// const timeout = 30000; // Stop checking after 30 seconds
	// const timeoutId = setTimeout(() => {
	//     clearInterval(checkInterval);
	//     console.error(
	//         'Timeout reached, stopped checking for the button and iframe.'
	//     );
	// }, timeout);
});
