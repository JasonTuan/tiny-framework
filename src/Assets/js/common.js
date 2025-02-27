import $ from 'jquery';
// Import all of Bootstrap's JS
import * as bootstrap from 'bootstrap';
import { defineJQueryPlugin } from "bootstrap/js/src/util";

window.$ = window.jQuery = $;

// defineJQueryPlugin(plugin) {
//     const name = plugin.NAME;
//     const JQUERY_NO_CONFLICT = $.fn[name];
//     $.fn[name] = plugin.jQueryInterface;
//     $.fn[name].Constructor = plugin;
//     $.fn[name].noConflict = () => {
//         $.fn[name] = JQUERY_NO_CONFLICT;
//         return plugin.jQueryInterface;
//     }
// }

defineJQueryPlugin(bootstrap.Modal);
defineJQueryPlugin(bootstrap.Tooltip);
defineJQueryPlugin(bootstrap.Popover);

window.callApiAsync = function (method, url, data = null) {
    return new Promise((resolve, reject) => {
        $.ajax({
            method: method,
            url: url,
            data: data,
            contentType: "application/json",
            success: function (response) {
                resolve(response);
            },
            error: function (error) {
                reject(error);
            }
        });
    });
}

window.route = function (routeName, params = {}) {
    let path = '#';
    app_paths.forEach((route) => {
        if (route.name === routeName) {
            path = route.path;
        }
    });

    const otherParams = [];
    // new URLSearchParams(params).toString();
    for (let key in params) {
        if (path.search(key) === -1) {
            otherParams.push(`${key}=${params[key]}`);
            continue;
        }
        path = path.replace(`{${key}}`, params[key]);
    }

    return path + (otherParams.length > 0 ? '?' + otherParams.join('&') : '');
}

window.refreshToken = function () {
    callApiAsync('GET', route('api.app.token')).then((response) => {
        window.token = response.token;
        console.log('Token refreshed', window.token);
    });
}
