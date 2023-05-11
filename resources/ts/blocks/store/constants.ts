import { State } from './defs';

export const INITIAL_STATE: State = {
    queries: {},
    degreePrograms: {},
};

export const RECEIVE_SINGLE_DEGREE_PROGRAM = 'RECEIVE_SINGLE_DEGREE_PROGRAM';
export const RECEIVE_DEGREE_PROGRAMS_LIST = 'RECEIVE_DEGREE_PROGRAMS_LIST';
