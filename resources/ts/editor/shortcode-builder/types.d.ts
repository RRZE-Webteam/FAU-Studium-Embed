import { create, PluginManager } from 'tinymce';

export { Editor } from 'tinymce';

export interface DegreeProgram {
    id: number;
    title: {
        de: string;
        en: string;
    };
}

export interface ShortcodeAttributes {
    display: string;
    id: number;
    lang: 'de' | 'en';
    format: 'full' | 'short';
    include: string[];
    exclude: string[];
}

declare global {
    interface Window {
        tinymce: {
            create: typeof create;
            plugins: string[];
            PluginManager: typeof PluginManager;
        };

        fauDegreeProgramData: {
            degreePrograms: DegreeProgram[];
            i18n: {
                degreeProgramFields: object;
                formFields: {
                    title: string;
                    degreeProgram: string;
                    language: string;
                    format: string;
                    include: string;
                    exclude: string;
                    includeExcludeIgnoredNotice: string;
                    excludeIgnoredNotice: string;
                };
            };
        };
    }
}
