/**
 * selective webp polyfill loader
 * won't penalise browser that have support
 */

const origin = window.location.origin;
const testImg = 'data:image/webp;base64,UklGRjIAAABXRUJQVlA4ICYAAACyAgCdASoCAAEALmk0mk0iIiIiIgBoSygABc6zbAAA/v56QAAAAA==';
const webBundleUrl = origin + '/wp-content/themes/hola-theme/assets/js/lib/webp-hero.bundle.js';
const webPolyfillUrl = origin + '/wp-content/themes/hola-theme/assets/js/lib/polyfills.js';

(function () {
    var img = new Image();

    img.onload = () => {
        support = !!(img.width == 2 && img.height == 1)
        if (!support) {
            injectWebpSupport();

        } else {};
    };

    img.onerror = () => {
        injectWebpSupport();
    };

    img.src = testImg;
})();

function injectWebpSupport() {
    var bundle = document.createElement('script');
    var polyfill = document.createElement('script');

    bundle.onload = function () {
        document.head.appendChild(polyfill);
    };
    polyfill.onload = function () {
        var webpMachine = new webpHero.WebpMachine();
        webpMachine.polyfillDocument();
        rmSrcset();
    };

    bundle.src = webBundleUrl;
    polyfill.src = webPolyfillUrl;
    document.head.appendChild(bundle);
};

function rmSrcset() {
    img = document.getElementsByTagName('img');
    for (let i = 0; i < img.length; i++) {
        img[i].removeAttribute('srcset');
    };
};