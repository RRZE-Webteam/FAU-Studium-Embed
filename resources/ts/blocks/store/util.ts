import { DegreeProgram, DegreeProgramTranslation, LanguageCodes } from '../defs';
import { Query, QueryArgs } from './defs';

/**
 * Query params are sorted to be used as state keys.
 * We skip the `lang` parameter to avoid duplicated requests and improve caching.
 * Responses for `de` contain English translations,
 * so any translation can be calculated later.
 */
export function buildQuery(queryArgs: QueryArgs, skipParams = ['lang']): Query {
    const normalizedQueryArgs = Object.keys(queryArgs)
        .sort()
        .reduce((accumulator, key) => {
            if (skipParams.includes(key)) {
                return accumulator;
            }

            accumulator[key] = String(queryArgs[key]);
            return accumulator;
        }, {});

    const params = new URLSearchParams(normalizedQueryArgs);
    return params.toString();
}

export function translateDegreeProgram(
    degreeProgram: DegreeProgram,
    lang: LanguageCodes,
): DegreeProgram {
    if (degreeProgram.lang === lang || !degreeProgram.translations[lang]) {
        return degreeProgram;
    }

    const { translations, ...other } = degreeProgram;
    const { [lang]: newBase, ...otherTranslations } = translations;

    return {
        id: degreeProgram.id,
        ...(newBase as DegreeProgramTranslation),
        translations: {
            [degreeProgram.lang]: other,
            ...otherTranslations,
        },
    };
}

export default {};
