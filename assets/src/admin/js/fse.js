document.addEventListener('DOMContentLoaded', function () {
    function checkAndReplaceIframe() {
        const editorPrefix = 'edit-site-patterns-maxiblocks/';

        const { url, directories } = maxiblocks;

        // console.log('url');
        // console.log(url);
        // console.log('directories');
        // console.log(directories);

        if (!directories) {
            return;
        }

        // Loop through the directories to extract the IDs and get the buttons
        directories.forEach((directory) => {
            // Extract the ID part from the URL
            const idPart = directory.split('/').pop(); // Gets the last part of the URL

            // Construct the full ID by prefixing the editorPrefix
            const fullId = `${editorPrefix}${idPart}`;

            // console.log('fullId');
            // console.log(fullId);

            // Get the button by ID
            const button = document.getElementById(fullId);

            // If the button exists, add it to the buttons array
            if (button) {
                console.log('Button found.');
                button.classList.add('maxiblocks-custom-pattern');
                const iframe = button.querySelector('iframe');

                if (iframe) {
                    console.log('Iframe found.');
                    const img = document.createElement('img');
                    img.src = `${url}${idPart}/preview-${idPart}.webp`;
                    img.alt = `${idPart} preview image`;
                
                    // Get the direct parent of the iframe
                    const iframeParent = iframe.parentNode;
                    console.log('Iframe Parent:', iframeParent);

                    console.log('Attempting to replace child...');
                    try {
                        // Use the direct parent to replace the iframe with the image
                        iframeParent.replaceChild(img, iframe);
                        console.log('Replacement successful.');
                        // clearInterval(checkInterval); // Clear the interval once the replacement is done
                    } catch (error) {
                        console.error(
                            'Error replacing iframe with image:',
                            error
                        );
                        console.log('Button at the time of error:', button);
                        console.log('Iframe at the time of error:', iframe);
                        console.log(
                            'Iframe Parent at the time of error:',
                            iframeParent
                        );
                    }
                    //clearInterval(checkInterval); // Clear the interval once the replacement is done
                }
                
            } else {
                // WordPress 6.5 fix
                const previewGridDiv = document.querySelector('div.dataviews-view-grid') || document.querySelector('div.block-editor-block-patterns-list');
                if (!previewGridDiv) {console.log('Grid not found yet, checking again...'); return;}

                // Get all direct child divs with the class 'dataviews-view-grid__card'
                const gridCards = previewGridDiv.querySelectorAll(':scope > .dataviews-view-grid__card, .block-editor-block-patterns-list__list-item');
                if (!gridCards || gridCards.length === 0) {console.log('Card not found yet, checking again...'); return;}

                console.log('Grid cards found.', gridCards);

                gridCards.forEach(card => {
                    if(card.classList.contains('maxiblocks-custom-pattern')) return;
                    console.log('Checking card...');
                    console.log(card.querySelector('div.block-editor-block-patterns-list__item')?.id);
                    const titleDiv = card.querySelector('div.edit-site-patterns__pattern-title');
                    const titleId = card.querySelector('div.block-editor-block-patterns-list__item')?.id
                    if (titleDiv || titleId) {
                        // Get the text, convert to lowercase, and replace spaces with dashes
                        const modifiedText = titleDiv ? titleDiv?.textContent?.toLowerCase().replace(/\s+/g, '-') : titleId.replace('maxiblocks/', '');

                        console.log('modifiedText', modifiedText);
                            console.log('idPart      ', idPart);
                            console.log('======================');
                       
                        if(modifiedText === idPart) {
                            card.classList.add('maxiblocks-custom-pattern');
                            const src = `${url}${idPart}/preview-${idPart}.webp`;
                            const alt = `${modifiedText} preview image`;
                            const imageToReplace = card.querySelector('.maxi-blocks-pattern-preview img');

                            if(imageToReplace ) {
                                imageToReplace.src = src;
                                imageToReplace.alt = alt;
                            }
                            else {
                                iframeToReplace = card.querySelector('.block-editor-block-preview__container iframe');
                                if(iframeToReplace) {
                                    const img = document.createElement('img');
                                    img.src = src;
                                    img.alt = alt;
                                    iframeToReplace.replaceWith(img);
                                }
                            }
                        }
                    }
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
