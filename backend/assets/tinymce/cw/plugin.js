/**
 * Copyright (c) Tiny Technologies, Inc. All rights reserved.
 * Licensed under the LGPL or a commercial license.
 * For LGPL see License.txt in the project root for license information.
 * For commercial licenses see https://www.tiny.cloud/
 *
 * Version: 5.0.0-1 (2019-02-04)
 */
(function () {
    var hr = (function () {
        'use strict';

        var global = tinymce.util.Tools.resolve('tinymce.PluginManager');

        var register = function (editor) {
            editor.addCommand('InsertCustomWrapper', function () {
                editor.execCommand('mceInsertContent', false, '<div class="cw-wrapper"><p></p></div>');
            });
        };
        var Commands = { register: register };

        var register$1 = function (editor) {
            editor.ui.registry.addButton('cw', {
                icon: 'template',
                tooltip: 'Wrapper',
                onAction: function () {
                    return editor.execCommand('InsertCustomWrapper');
                }
            });
            editor.ui.registry.addMenuItem('cw', {
                icon: 'template',
                text: 'Wrapper',
                onAction: function () {
                    return editor.execCommand('InsertCustomWrapper');
                }
            });
        };
        var Buttons = { register: register$1 };

        global.add('cw', function (editor) {
            Commands.register(editor);
            Buttons.register(editor);
        });

        function Plugin () {
        }

        return Plugin;

    }());
})();
