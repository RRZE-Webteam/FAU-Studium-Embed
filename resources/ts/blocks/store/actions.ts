import { RECEIVE_DEGREE_PROGRAMS_LIST, RECEIVE_SINGLE_DEGREE_PROGRAM } from './constants';

export function receiveDegreeProgram(id, degreeProgram) {
    return {
        type: RECEIVE_SINGLE_DEGREE_PROGRAM,
        id,
        degreeProgram,
    };
}

export function receiveDegreePrograms(query, degreePrograms) {
    return {
        type: RECEIVE_DEGREE_PROGRAMS_LIST,
        query,
        degreePrograms,
    };
}
