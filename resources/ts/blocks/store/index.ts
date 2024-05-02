import { createReduxStore, register } from '@wordpress/data';

import * as actions from './actions';
import reducer from './reducer';
import * as resolvers from './resolvers';
import * as selectors from './selectors';

export const STORE_NAME = 'degree-program';

const storeConfig = () => ( {
	reducer,
	actions,
	selectors,
	resolvers,
} );

export const store = createReduxStore( STORE_NAME, storeConfig() );

register( store );
