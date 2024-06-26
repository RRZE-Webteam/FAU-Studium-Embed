const degreeProgramsFetcher = (): object[] => {
	if ( ! window.fauDegreeProgramData.degreePrograms.length ) {
		return [];
	}

	return window.fauDegreeProgramData.degreePrograms.map(
		( degreeProgram ) => {
			return {
				value: degreeProgram.id,
				text: `${ degreeProgram.title.de } (${ degreeProgram.degree.abbreviation.de })`,
			};
		}
	);
};

export default degreeProgramsFetcher;
