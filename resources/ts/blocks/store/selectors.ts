import { DegreeProgram, DegreeProgramId, LanguageCodes } from '../defs';
import { QueryArgs, State } from './defs';
import { buildQuery, translateDegreeProgram } from './util';

export function getDegreeProgram(
    state: State,
    id: DegreeProgramId,
    lang: LanguageCodes,
): DegreeProgram | null | undefined {
    const degreeProgram = state.degreePrograms[id];
    if (!degreeProgram) {
        return degreeProgram;
    }
    return translateDegreeProgram(degreeProgram, lang);
}

export function getDegreePrograms(
    state: State,
    queryArgs: QueryArgs,
): Array<DegreeProgram> | null | undefined {
    const ids = state.queries[buildQuery(queryArgs)];
    if (!ids) {
        return ids;
    }
    return ids.reduce((accumulator: Array<DegreeProgram>, id) => {
        const degreeProgram = state.degreePrograms[id];
        if (degreeProgram) {
            accumulator.push(translateDegreeProgram(degreeProgram, queryArgs.lang ?? 'de'));
        }

        return accumulator;
    }, []);
}

export function hasDegreePrograms(state: State, queryArgs: QueryArgs): boolean {
    return state.queries[buildQuery(queryArgs)] !== undefined;
}
