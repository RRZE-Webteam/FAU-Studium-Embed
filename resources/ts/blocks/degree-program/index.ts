import { registerBlockType } from '@wordpress/blocks';

import block from './block.json';
import DegreeProgramEdit from './edit';

import './styles.scss';

const { name } = block;

registerBlockType( name, {
	edit: DegreeProgramEdit,
	save: () => null,
} );
