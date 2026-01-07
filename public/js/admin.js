// Admin JS entry (Vite)
console.log('admin.js loaded');

document.addEventListener('DOMContentLoaded', function(){
    // small helper: toggle sidebar active link based on route (blade already sets .active)
    // future admin JS features (AJAX, data tables) go here
});



// Simple fetch deduplicator to avoid repeated identical requests firing concurrently/rapidly.
// It returns the same in-flight Promise when the same URL is requested again until it resolves.
(function installFetchDeduper() {
    if (!window.fetch) return;
    if (window._fetchDeduperInstalled) return;
    window._fetchDeduperInstalled = true;

    const inFlight = new Map();
    const origFetch = window.fetch.bind(window);

    window.fetch = function(input, init) {
        try {
            const url = (typeof input === 'string') ? input : (input && input.url) ? input.url : JSON.stringify(input);
            // Use URL + method + body as key to distinguish different requests
            const method = (init && init.method) || (typeof input === 'object' && input.method) || 'GET';
            const body = (init && init.body) || '';
            const key = `${method.toUpperCase()}::${url}::${body}`;

            if (inFlight.has(key)) {
                // Return the same promise for identical in-flight request
                console.warn('Fetch deduper: returning existing in-flight promise for', url);
                try {
                    const existingStack = window._fetchDeduperStacks && window._fetchDeduperStacks.get ? window._fetchDeduperStacks.get(key) : null;
                    const currentStack = (new Error()).stack;
                    console.groupCollapsed('Fetch deduper stack trace for duplicate request: ' + url);
                    if (existingStack) console.log('Original initiator stack:\\n', existingStack);
                    console.log('Current initiator stack:\\n', currentStack);
                    console.groupEnd();
                    // store sample for later inspection
                    if (!window._fetchDeduperSamples) window._fetchDeduperSamples = [];
                    window._fetchDeduperSamples.push({ key, url, time: Date.now(), original: existingStack, current: currentStack });
                } catch (e) {
                    // ignore logging errors
                }
                return inFlight.get(key);
            }

            const stack = (new Error()).stack;
            try {
                if (!window._fetchDeduperStacks) window._fetchDeduperStacks = new Map();
                window._fetchDeduperStacks.set(key, stack);
            } catch (e) {}

            const promise = origFetch(input, init).finally(() => {
                // clear the in-flight entry after settled
                setTimeout(() => {
                    inFlight.delete(key);
                    try { if (window._fetchDeduperStacks) window._fetchDeduperStacks.delete(key); } catch (e) {}
                }, 0);
            });

            inFlight.set(key, promise);
            return promise;
        } catch (err) {
            return origFetch(input, init);
        }
    };
})();

