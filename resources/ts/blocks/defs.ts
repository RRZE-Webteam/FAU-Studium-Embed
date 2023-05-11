export interface Block<Attributes = object> {
    readonly name: string;
    readonly attributes: Attributes;
    readonly isSelected: boolean;
    readonly setAttributes: (attributes: Partial<Attributes>) => void;
    readonly clientId: string;
}

export type DegreeProgramBlockServerDataType = Readonly<{
    apiUrl: string;
    supportedProperties: Record<string, string>;
}>;

export type DegreeProgramId = number;

export type LanguageCodes = 'de' | 'en';

export interface DegreeProgramTranslation {
    title: string;
    lang: LanguageCodes;
    degree: {
        name: string;
        abbreviation: string;
    };
    [key: string]: unknown;
}

export interface DegreeProgram extends DegreeProgramTranslation {
    id: DegreeProgramId;
    translations: Partial<Record<LanguageCodes, DegreeProgramTranslation>>;
}
