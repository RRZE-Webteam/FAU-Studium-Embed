import {
	CurriedSelectorsOf,
	DataRegistry,
	ReduxStoreConfig,
	StoreDescriptor,
} from '@wordpress/data/src/types';

import { DegreeProgram, DegreeProgramId, LanguageCodes } from '../defs';

export type Query = string;
export type QueryArgs = {
	search?: string;
	per_page?: number;
	lang?: LanguageCodes;
};

export type State = {
	queries: Record< Query, Array< DegreeProgramId > | null | undefined >;
	degreePrograms: Record< DegreeProgramId, DegreeProgram | null | undefined >;
};

type Selectors = {
	getDegreeProgram(
		state: State,
		id: number,
		lang: LanguageCodes
	): DegreeProgram | null | undefined;
	getDegreePrograms(
		state: State,
		queryArgs: QueryArgs
	): Array< DegreeProgram > | null | undefined;
	hasDegreePrograms( state: State, queryArgs: QueryArgs ): boolean;
};

type Actions = {
	receiveDegreeProgram( degreeProgram: DegreeProgram ): object;
	receiveDegreePrograms(
		query: Query,
		degreePrograms: Array< DegreeProgram >
	): object;
};

export type DegreeProgramStore = StoreDescriptor<
	ReduxStoreConfig< State, Actions, Selectors >
>;

export type MapSelect< StoreConfig > = (
	select: ( store: string ) => CurriedSelectorsOf< StoreConfig >,
	registry: DataRegistry
) => any; // eslint-disable-line @typescript-eslint/no-explicit-any
