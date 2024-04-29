import { DegreeProgramBlockServerDataType } from '../defs';

declare global {
	interface Window {
		DegreeProgramBlockServerData: DegreeProgramBlockServerDataType;
	}
}

export default function serverData() {
	if ( window.DegreeProgramBlockServerData === undefined ) {
		throw new Error( 'Server data could not be loaded.' );
	}

	return window.DegreeProgramBlockServerData as DegreeProgramBlockServerDataType;
}
