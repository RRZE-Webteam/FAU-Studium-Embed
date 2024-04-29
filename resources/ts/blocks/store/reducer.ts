import { produce } from 'immer';

import { DegreeProgram } from '../defs';
import {
	INITIAL_STATE,
	RECEIVE_DEGREE_PROGRAMS_LIST,
	RECEIVE_SINGLE_DEGREE_PROGRAM,
} from './constants';

const reducer = produce( ( draft, action ) => {
	switch ( action.type ) {
		case RECEIVE_SINGLE_DEGREE_PROGRAM:
			draft.degreePrograms[ action.id ] = action.degreeProgram;
			break;
		case RECEIVE_DEGREE_PROGRAMS_LIST:
			if ( action.degreePrograms ) {
				draft.queries[ action.query ] = action.degreePrograms.map(
					( item: DegreeProgram ) => item.id
				);
				action.degreePrograms.forEach(
					( degreeProgram: DegreeProgram ) => {
						draft.degreePrograms[ degreeProgram.id ] =
							degreeProgram;
					}
				);
			} else {
				draft.queries[ action.query ] = action.degreePrograms;
			}
			break;
		default:
			break;
	}
}, INITIAL_STATE );

export default reducer;
