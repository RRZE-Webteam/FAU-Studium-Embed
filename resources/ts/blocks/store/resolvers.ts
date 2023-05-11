import apiFetch from '@wordpress/api-fetch';

import { DegreeProgram, DegreeProgramId } from '../defs';
import serverData from '../util/serverData';
import { QueryArgs } from './defs';
import { buildQuery } from './util';

export function getDegreeProgram(id: DegreeProgramId) {
    return async ({ select, dispatch }) => {
        if (id <= 0) {
            return;
        }

        if (select.getDegreeProgram(id)) {
            return;
        }

        try {
            const degreeProgram = await apiFetch({
                url: `${serverData().apiUrl}/wp-json/fau/v1/degree-program/${id}`,
            });
            dispatch.receiveDegreeProgram(id, degreeProgram);
        } catch (e) {
            dispatch.receiveDegreeProgram(id, null);
        }
    };
}

export function getDegreePrograms(queryArgs: QueryArgs) {
    return async ({ select, dispatch }) => {
        if (select.hasDegreePrograms(queryArgs)) {
            return;
        }

        const query = buildQuery(queryArgs);

        try {
            const degreePrograms = await apiFetch<Array<DegreeProgram>>({
                url: `${serverData().apiUrl}/wp-json/fau/v1/degree-program?${query}`,
            });
            dispatch.receiveDegreePrograms(query, degreePrograms);
        } catch (e) {
            dispatch.receiveDegreePrograms(query, null);
        }
    };
}
