tinymce.PluginManager.add('myembed', function (editor, url) {
    var openDialog = function () {
        return editor.windowManager.open({
            title: 'Embed',
            body: {
                type: 'panel',
                items: [
                    {
                        type: 'textarea',
                        name: 'code',
                        label: 'Embed'
                    }
                ]
            },
            buttons: [
                {
                    type: 'cancel',
                    text: 'Close'
                },
                {
                    type: 'submit',
                    text: 'Insert',
                    primary: true
                }
            ],
            onSubmit: function (api) {
                var data = api.getData();
                // Insert content when the window form is submitted

                editor.insertContent(data.code);
                api.close();
            }
        });
    };

    // Add a button that opens a window
    editor.ui.registry.addButton('myembed', {
        text: '',
        icon: 'embed',
        onAction: function () {
            // Open window
            openDialog();
        }
    });

    // Adds a menu item, which can then be included in any menu via the menu/menubar configuration
    editor.ui.registry.addMenuItem('myembed', {
        text: 'Embed plugin',
        onAction: function () {
            // Open window
            openDialog();
        }
    });

    return {
        getMetadata: function () {
            return {
                name: "Embed",
                url: "https://abs.twimg.com/favicons/favicon.ico"
            };
        }
    };
});