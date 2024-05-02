import { Block, LanguageCodes } from '../defs';

type DegreeProgramBlockAttributes = Readonly< {
	id?: number;
	lang: LanguageCodes;
	include?: Array< string >;
	exclude?: Array< string >;
	format: 'full' | 'short';
} >;

export type DegreeProgramBlock = Block< DegreeProgramBlockAttributes >;
