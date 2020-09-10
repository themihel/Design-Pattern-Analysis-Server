var VERSION = 1; // only the number should be changed. The position of the number is relevant for the app.
// Explanation can be found in the readme.

/**
 * Prepare history event for tracking changes
 */
function initTools() {
    (function(history){
        var pushState = history.pushState;
        history.pushState = function(state) {
            if (typeof history.onpushstate == "function") {
                history.onpushstate({state: state});
            }
            return pushState.apply(history, arguments);
        };
    })(window.history);
}

/**
 * Check if user is logged in
 * Example how "hacky" this could be: If variable is available fine, if not, user is not logged in
 * You have to find your way to analyze the platform
 *
 * @returns {boolean}
 */
function isLoggedIn() {
    try {
        var username = window._sharedData.config.viewer.username;
        return true;
    }
    catch (e) {
        return false;
    }
}

/**
 * Set Body classes for styles and track current route
 */
function pageChange() {
    var bodyElement = $('body');

    // remove classes
    bodyElement.removeClass().addClass('loaded');

    // add clases based on route and state
    if (isLoggedIn()) {
        bodyElement.addClass('loggedIn');
        var pathname = window.location.pathname;
        var patternStories = new RegExp('\/stories\/(highlights\/)?.*\/');
        var current = 'unknown';

        switch (pathname) {
            case '/':
                current = 'home';
                break;
            case '/some_other_url/':
                current = 'some_url';
                break;
            default:
                // complex cases
                if (patternStories.test(pathname)) {
                    current = 'stories';

                    if (pathname.match(patternStories)[1] == 'highlights/') {
                        current += '_highlights';
                    }
                } else {
                    current = 'other'; // the list of actions should be as complete as possible
                }
        }
        // Track nav action
        track("NAV_" + current.toUpperCase());
        bodyElement.addClass(current);

    } else {
        bodyElement.addClass('loggedOut');
    }
}

/**
 * Activate instance or page reload
 */
function run() {
    pageChange();

    if (isLoggedIn()) {
        window.onpopstate = history.onpushstate = function (e) {setTimeout(pageChange(), 100);};
    }

    // Also event listeners on "onClick" events can be added, anything you need
}

/**
    Android tracking helper (implemented in the corresponding app)
 */
function track(trackingKey) {
    Android.track(trackingKey, VERSION);
}

/**
 Wait until scripts are present and init scripts
 */
var i = setInterval(function(){
    if (document.readyState !== 'complete') {
        console.log("complete");
        return;
    }
    clearInterval(i);

    if (document.body.classList.contains('loaded')) {
        console.log("loaded");
        return;
    }

    document.body.classList.add('loaded');
    initTools();
    run();
}, 100);