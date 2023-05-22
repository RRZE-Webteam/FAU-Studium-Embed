import handleDialogs from './shortcode-dialog';
import { Editor } from './types';

((tinymce) => {
    tinymce.create('tinymce.plugins.fau_degree_program_output_shortcodes_plugin', {
        init: (editor: Editor): void => {
            editor.addButton('fau_degree_program_output_shortcodes', {
                title: 'FAU Degree Program Shortcodes',
                icon: 'is-dashicon dashicons dashicons-welcome-learn-more',
                onclick: (): void => handleDialogs(editor),
                onPostRender: (): void => {
                    jQuery('.mce-i-is-dashicon').css('font-family', 'dashicons');
                },
            });
        },
    });

    tinymce.PluginManager.add(
        'fau_degree_program_output_shortcodes_plugin',
        tinymce.plugins['fau_degree_program_output_shortcodes_plugin'], // eslint-disable-line @typescript-eslint/dot-notation
    );
})(window.tinymce);
