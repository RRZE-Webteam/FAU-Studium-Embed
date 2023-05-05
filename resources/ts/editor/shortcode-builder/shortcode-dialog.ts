import { Editor } from 'tinymce';

import degreeProgramsFetcher from './degree-programs-fetcher';
import { buildShortcode, PREFIX_EXCLUDE, PREFIX_INCLUDE } from './shortcode-builder';

const buildGroupedItems = (prefix: string): object[] => {
    const { degreeProgramFields } = window.fauDegreeProgramData.i18n;
    return Object.entries(degreeProgramFields).map(([field, label]): object => {
        return {
            name: `${prefix}${field}`,
            type: 'checkbox',
            text: label,
        };
    });
};

const createShortcodeBuilderDialog = (editor, degreePrograms) => {
    const i18n = window.fauDegreeProgramData.i18n.formFields;

    editor.windowManager.open(
        {
            title: i18n.title,
            body: {
                type: 'container',
                layout: 'flex',
                style: 'max-height: 85vh; overflow-x: hidden; overflow-y: auto;',
                padding: '10 10 10 10',
                items: [
                    {
                        type: 'form',
                        layout: 'flex',
                        direction: 'column',
                        align: 'stretch',
                        items: [
                            {
                                name: 'id',
                                type: 'listbox',
                                values: degreePrograms,
                                label: i18n.degreeProgram,
                            },
                            {
                                name: 'lang',
                                type: 'listbox',
                                values: [
                                    { value: 'de', text: 'Deutsch' },
                                    { value: 'en', text: 'English' },
                                ],
                                label: i18n.language,
                            },
                            {
                                name: 'format',
                                type: 'listbox',
                                values: [
                                    { value: 'short', text: 'Short' },
                                    { value: 'full', text: 'Full' },
                                ],
                                label: i18n.format,
                            },
                            {
                                type: 'label',
                                text: i18n.includeExcludeIgnoredNotice,
                                style: 'font-style: italic',
                            },
                            {
                                type: 'container',
                                layout: 'grid',
                                columns: 2,
                                spacing: 5,
                                items: [
                                    {
                                        type: 'container',
                                        layout: 'flex',
                                        direction: 'column',
                                        spacing: 5,
                                        items: [
                                            { type: 'label', text: i18n.include },
                                            ...buildGroupedItems(PREFIX_INCLUDE),
                                        ],
                                    },
                                    {
                                        type: 'container',
                                        layout: 'flex',
                                        direction: 'column',
                                        spacing: 5,
                                        items: [
                                            { type: 'label', text: i18n.exclude },
                                            {
                                                type: 'label',
                                                text: i18n.excludeIgnoredNotice,
                                                style: 'font-style: italic',
                                            },
                                            ...buildGroupedItems(PREFIX_EXCLUDE),
                                        ],
                                    },
                                ],
                            },
                        ],
                    },
                ],
            },
            onSubmit: (form) => {
                try {
                    editor.insertContent(buildShortcode(form.data));
                    // eslint-disable-next-line @typescript-eslint/no-explicit-any
                } catch (exc: any) {
                    editor.windowManager.alert(exc.message, () => {
                        createShortcodeBuilderDialog(editor, degreePrograms);
                    });
                }
            },
        },
        {},
    );
};

const handleDialogs = (editor: Editor) => {
    const degreePrograms = degreeProgramsFetcher();
    degreePrograms.unshift({
        value: 0,
        text: '',
    });

    createShortcodeBuilderDialog(editor, degreePrograms);
};

export default handleDialogs;
