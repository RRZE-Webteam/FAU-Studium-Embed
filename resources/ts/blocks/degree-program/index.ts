import { registerBlockType } from '@wordpress/blocks';

import { name } from './block.json';
import DegreeProgramEdit from './edit';

import './styles.scss';

registerBlockType( name, {
	edit: DegreeProgramEdit,
	save: () => null,
} );
