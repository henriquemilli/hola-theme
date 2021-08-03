/**
 * selective webp polyfill loader
 * won't penalise browser that have support
 */

const origin = window.location.origin;
const testWebpUrl = origin + '/wp-content/themes/hola-theme/assets/js/other/test.webp';
const webBundleUrl = origin + '/wp-content/themes/hola-theme/assets/js/lib/webp-hero.bundle.js';
const webPolyfillUrl = origin + '/wp-content/themes/hola-theme/assets/js/lib/polyfills.js';

(function() {
    var img = new Image();

    img.onload = function() {
        support = !!(img.height > 0 && img.width > 0) 
        if ( ! support ) {
            injectWebpSupport();
        };
    };

    img.onerror = function() {
        injectWebpSupport();
    };
    
    img.src = testWebpUrl;
})();

function injectWebpSupport() {
    var bundle = document.createElement('script');
    var polyfill = document.createElement('script');

    bundle.onload = function () {
        document.head.appendChild(polyfill);
    };
    polyfill.onload = function() {
        var webpMachine = new webpHero.WebpMachine();
        webpMachine.polyfillDocument();
    };

    bundle.src = webBundleUrl;
    polyfill.src = webPolyfillUrl;
    document.head.appendChild(bundle);
};