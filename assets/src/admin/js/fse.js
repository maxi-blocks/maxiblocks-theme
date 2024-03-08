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

            // Get the button by ID
            const button = document.getElementById(fullId);

            // If the button exists, add it to the buttons array
            if (button) {
               // console.log('Button found.');
                button.classList.add('maxiblocks-custom-pattern');
                const iframe = button.querySelector('iframe');

                if (iframe) {
                    console.log('Iframe found.');
                    const img = document.createElement('img');
                    img.src = `${url}${idPart}/preview.webp`;
                    // img.style.width = iframe.style.width; // Optional: Set the width of the image to match the iframe
                    // img.style.height = iframe.style.height; // Optional: Set the height of the image to match the iframe
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
                // else {
                //     console.log(
                //         'Iframe not found within the button, checking again...'
                //     );
                // }
            }
            // else {
            //     console.log('Button not found yet, checking again...');
            // }
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
