/**
 * Handles the installation and activation notice for the MaxiBlocks plugin.
 *
 * @listens DOMContentLoaded - Initializes the install notice logic.
 */
document.addEventListener('DOMContentLoaded', () => {
    installMaxiBlocksNotice();
});

/**
 * Initializes and manages the MaxiBlocks plugin installation and activation notice.
 */
function installMaxiBlocksNotice() {
    /** @var {Object} maxiblocks - Global object containing plugin data. */
    const {
        activating,
        installing,
        done,
        activationUrl,
        ajaxUrl,
        pluginSlug,
        nonce,
        pluginStatus,
    } = maxiblocks;

    /** @var {HTMLElement} installStatusText - Text element within the install button. */
    let installStatusText;

    /** @var {HTMLElement} closeButton - Close button element for the notice. */
    const closeButton = document.querySelector(
        '.maxiblocks-go-notice .maxiblocks-go-notice__dismiss'
    );

    /** @var {HTMLElement} noticeContainer - Container element for the notice. */
    const noticeContainer = document.querySelector('.maxiblocks-go-notice');

    /** @var {HTMLElement} installButton - Install button element within the notice. */
    const installButton = document.querySelector(
        '.maxiblocks-go-notice #maxiblocks-go-notice-install-maxiblocks'
    );

    if (installButton) {
        installStatusText = installButton.querySelector('.maxiblocks-go-button__text');
    }

    /**
     * Hides and removes the notice element from the DOM.
     */
    const hideAndRemoveNotice = () => noticeContainer?.remove();

    /**
     * Activates the MaxiBlocks plugin.
     * Updates UI during and after activation process.
     */
    const activateMaxi = async () => {
        installStatusText.textContent = activating;
        await activatePluginUrl(activationUrl);
        installButton.classList.replace('updating-message', 'updated-message');
        installStatusText.textContent = done;
        setTimeout(() => {
            hideAndRemoveNotice();
            window.location.reload();
        }, 1000);
    };

    /**
     * Event handler for install button click.
     * Manages the plugin installation and activation process.
     */
    installButton?.addEventListener('click', async () => {
        installButton.classList.add('updating-message', 'disabled');
        const importStatusIcon = installButton.querySelector('.maxiblocks-go-button__icon');
        importStatusIcon.classList.add('hidden');

        if (pluginStatus === 'installed') {
            await activateMaxi();
            return;
        }

        installStatusText.textContent = installing;
        await installPlugin(pluginSlug);
        await activateMaxi();
    });

    /**
     * Event handler for close button click.
     * Sends a request to dismiss the notice.
     */
    closeButton?.addEventListener('click', async () => {
        const response = await fetch(ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type':
                    'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: `action=maxiblocks-go-theme-dismiss-plugin-notice&nonce=${nonce}`,
        });

        if (response.status === 200) {
            hideAndRemoveNotice();
        }
    });
}

/**
 * Installs a plugin given its slug.
 *
 * @param {string} slug - The slug of the plugin to install.
 * @returns {Promise<Object>} Promise resolving to the result of the installation attempt.
 */
async function installPlugin(slug) {
    return new Promise((resolve) => {
        wp.updates.ajax('install-plugin', {
            slug,
            success: () => resolve({ success: true }),
            error: (err) => resolve({ success: false, code: err.errorCode }),
        });
    });
}

/**
 * Sends a request to activate the plugin.
 *
 * @param {string} url - The URL to send the activation request to.
 * @returns {Promise<Object>} Promise resolving to the result of the activation attempt.
 */
async function activatePluginUrl(url) {
    try {
        const response = await fetch(url);

        if (response.status === 200) {
            return { success: true };
        }
    } catch (err) {
        return { success: false };
    }
}
