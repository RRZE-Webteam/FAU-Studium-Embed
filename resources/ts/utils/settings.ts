declare global {
	interface Window {
		degreeProgramsOverviewSettings: {
			icon_close: string;
			icon_degree: string;
		};
	}
}

export default {
	...window.degreeProgramsOverviewSettings,
};
