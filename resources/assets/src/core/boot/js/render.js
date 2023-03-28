/**
 *------------------------------------------------------------------------------
 *
 *  Render HTML Elements From String/Templates
 *
 */

bind('render', {
    dependencies: ['render/html', 'render/template'],
    value: function(html, template) {
        return Object.freeze({ html, template });
    }
});
