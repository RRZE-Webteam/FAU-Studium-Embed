import { _x } from '@wordpress/i18n';

import settings from '../utils/settings';

interface DegreeProgramData {
	id: number;
	title: string;
	degree: {
		abbreviation: string;
		name: string;
	};
	image: string;
	url: string;
	location: string[];
	semester: string[];
	admissionRequirements: string;
	germanLanguageSkills: string;
	lang: string;
}

export interface DegreeProgramApiData {
	id: number;
	title: string;
	degree: {
		abbreviation: string;
		name: string;
	};
	link: string;
	location: string[];
	start: string[];
	teaser_image: { rendered: string };
	admission_requirement_link: { name: string };
	german_language_skills_for_international_students: { name: string };
	lang: string;
}

interface DegreeProgram extends DegreeProgramData {}

class DegreeProgram {
	private constructor( {
		id,
		image,
		url,
		title,
		degree,
		semester,
		location,
		admissionRequirements,
		germanLanguageSkills,
		lang,
	}: DegreeProgramData ) {
		this.id = id;
		this.image = image;
		this.url = url;
		this.title = title;
		this.degree = degree;
		this.semester = semester;
		this.location = location;
		this.admissionRequirements = admissionRequirements;
		this.germanLanguageSkills = germanLanguageSkills;
		this.lang = lang;
	}

	static createDegreeProgram( program: DegreeProgramApiData ): DegreeProgram {
		return new DegreeProgram( < DegreeProgramData >{
			id: program.id,
			title: program.title,
			degree: program.degree,
			image: program.teaser_image.rendered || settings.icon_degree,
			url: program.link,
			location: program.location,
			semester: program.start,
			admissionRequirements: program.admission_requirement_link?.name,
			germanLanguageSkills:
				program.german_language_skills_for_international_students.name,
			lang: program.lang,
		} );
	}

	urlWithLang(): string {
		const url = new URL( this.url );
		url.searchParams.set( 'lang', this.lang );
		return url.toString();
	}

	render( isLocaleSwitched: string ): string {
		return `
			<li class="c-degree-program-preview">
				<div class="c-degree-program-preview__teaser-image">
					${ this.image }
				</div>
				<div class="c-degree-program-preview__title">
					<a class="c-degree-program-preview__link" href="${
						isLocaleSwitched === 'true'
							? this.urlWithLang()
							: this.url
					}" rel="bookmark" aria-labelledby="degree-program-title-${
						this.id
					}"></a>
					<div id="degree-program-title-${ this.id }">
						${ this.title } (<abbr title="${ this.degree.name }">${
							this.degree.abbreviation
						}</abbr>)
						<div class="c-degree-program-preview__subtitle"></div>
					</div>
				</div>
				<div class="c-degree-program-preview__degree">
					<span class="c-degree-program-preview__label">
						${ _x(
							'Type',
							'frontoffice: degree-programs-overview',
							'fau-degree-program-output'
						) }:
					</span>
					${ this.degree.name }
				</div>
				<div class="c-degree-program-preview__start">
					<span class="c-degree-program-preview__label">
						${ _x(
							'Start',
							'frontoffice: degree-programs-overview',
							'fau-degree-program-output'
						) }:
					</span>
					${ this.semester.join( ', ' ) }
				</div>
				<div class="c-degree-program-preview__location">
					<span class="c-degree-program-preview__label">
						${ _x(
							'Location',
							'frontoffice: degree-programs-overview',
							'fau-degree-program-output'
						) }:
					</span>
					${ this.location.join( ', ' ) }
				</div>
				<div class="c-degree-program-preview__admission-requirement">
					<span class="c-degree-program-preview__label">
						${ _x(
							'NC',
							'frontoffice: degree-programs-overview',
							'fau-degree-program-output'
						) }:
					</span>
					${ this.admissionRequirements ? this.admissionRequirements : '' }
				</div>
				<div class="c-degree-program-preview__language-certificates">
					<span class="c-degree-program-preview__label">
						${ _x(
							'Language certificates',
							'frontoffice: degree-programs-overview',
							'fau-degree-program-output'
						) }:
					</span>
					${ this.germanLanguageSkills }
				</div>
			</li>
		`;
	}
}

export default DegreeProgram;
