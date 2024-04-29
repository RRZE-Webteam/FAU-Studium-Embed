import React from 'react';

import { useBlockProps } from '@wordpress/block-editor';

import DegreeProgramControls from './components/DegreeProgramControls';
import { DegreeProgramBlock } from './defs';

const DegreeProgramEdit = ( props: DegreeProgramBlock ) => {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<DegreeProgramControls { ...props } />
		</div>
	);
};

export default DegreeProgramEdit;
