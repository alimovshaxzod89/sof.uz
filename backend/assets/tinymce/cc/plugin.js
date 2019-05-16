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
            editor.addCommand('InsertCustomCard', function () {
                editor.execCommand('mceInsertContent', false, '<h3 class="custom_card"><span class="hidden"></span>Сарлавҳани киритинг</h3>');
            });
        };
        var Commands = { register: register };

        var register$1 = function (editor) {
            editor.ui.registry.addButton('cc', {
                icon: 'page-break',
                tooltip: 'Card',
                onAction: function () {
                    return editor.execCommand('InsertCustomCard');
                }
            });
            editor.ui.registry.addMenuItem('cc', {
                icon: 'page-break',
                text: 'Card',
                onAction: function () {
                    return editor.execCommand('InsertCustomCard');
                }
            });
        };
        var Buttons = { register: register$1 };

        global.add('cc', function (editor) {
            Commands.register(editor);
            Buttons.register(editor);
        });
        function Plugin () {
        }

        return Plugin;

    }());
})();
