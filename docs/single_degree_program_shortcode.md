# Single degree program shortcode

To render a single degree program, use the `[fau-studium display="degree-program"]` shortcode.
The only required attribute is `id`, the degree program's post ID.

## Attributes

| Attribute | Description                                                                                            | Required | Default | Valid options     |
|-----------|--------------------------------------------------------------------------------------------------------|----------|---------|-------------------|
| `display` | Entry point for the single degree view program rendering                                               | yes      |         | `degree-program`  |
| `id`      | ID of the degree program to be rendered                                                                | yes      |         | Post ID           |
| `lang`    | Language                                                                                               | no       | `de`    | `de` or `en`      |
| `include` | Comma-separated list of fields to be included (ignored if `format` is `short`)                         | no       |         | See list below    |
| `exclude` | Comma-separated list of fields to be excluded (ignored if `include` is defined or `format` is `short`) | no       |         | See list below    |
| `format`  | Full (all fields) of short (title and link) format                                                     | no       | `full`  | `full` or `short` |

## List of supported fields for including/excluding

| Field ID                                            | Description                                                                     |
|-----------------------------------------------------|---------------------------------------------------------------------------------|
| `teaser_image`                                      |                                                                                 |
| `title`                                             |                                                                                 |
| `subtitle`                                          |                                                                                 |
| `standard_duration`                                 | Regelstudienzeit                                                                |
| `start`                                             | Studienbeginn                                                                   |
| `number_of_students`                                | Studierendenzahl                                                                |
| `teaching_language`                                 | Unterrichtssprache                                                              |
| `attributes`                                        | Attribute                                                                       |
| `degree`                                            | Abschlüsse                                                                      |
| `faculty`                                           | Fakultät                                                                        |
| `location`                                          | Studienort                                                                      |
| `subject_groups`                                    | Fächergruppen                                                                   |
| `videos`                                            |                                                                                 |
| `content.about`                                     | Worum geht es im Studiengang?                                                   |
| `content.structure`                                 | Aufbau und Struktur                                                             |
| `content.specializations`                           | Studienrichtungen und Schwerpunkte                                              |
| `content.qualities_and_skills`                      | Was sollte ich mitbringen?                                                      |
| `content.why_should_study`                          | Gute Gründe für ein Studium an der FAU                                          |
| `content.career_prospects`                          | Welche beruflichen Perspektiven stehen mir offen?                               |
| `admission_requirement_link`                        | Tipps zur Bewerbung                                                             |
| `details_and_notes`                                 | Details und Anmerkungen                                                         |
| `start_of_semester`                                 | Semesterstart                                                                   |
| `semester_dates`                                    | Semestertermine                                                                 |
| `examinations_office`                               | Prüfungsamt                                                                     |
| `examination_regulations`                           | Studien- und Prüfungsordnung                                                    |
| `module_handbook`                                   | Modulhandbuch                                                                   |
| `url`                                               | Studiengang-URL                                                                 |
| `department`                                        | Department/Institut (URL)                                                       |
| `student_advice`                                    | Allgemeine Studienberatung                                                      |
| `subject_specific_advice`                           | Beratung aus dem Fach                                                           |
| `service_centers`                                   | Beratungs- und Servicestellen der FAU                                           |
| `info_brochure`                                     | Infobroschüre Studiengang                                                       |
| `semester_fee`                                      | Semesterbeitrag                                                                 |
| `abroad_opportunities`                              | Wege ins Ausland                                                                |
| `keywords`                                          | Schlagworte                                                                     |
| `area_of_study`                                     | Studienbereich                                                                  |
| `combinations`                                      | Kombinationsmöglichkeiten                                                       |
| `limited_combinations`                              | Eingeschränkt Kombinationsmöglichkeiten                                         |
| `notes_for_international_applicants`                | Hinweise für internationale Bewerber                                            |
| `student_initiatives`                               | StuVe/FSI                                                                       |
| `apply_now_link`                                    | Bewerben                                                                        |
| `entry_text`                                        | Einstiegtext (werbend)                                                          |
| `content_related_master_requirements`               | Inhaltliche Zugangsvoraussetzungen Master                                       |
| `application_deadline_winter_semester`              | Bewerbungsfrist Wintersemester                                                  |
| `application_deadline_summer_semester`              | Bewerbungsfrist Sommersemester                                                  |
| `language_skills`                                   | Sprachkenntnisse                                                                |
| `language_skills_humanities_faculty`                | Sprachkenntnisse nur für die Philosophische Fakultät und Fachbereich Theologie  |
| `german_language_skills_for_international_students` | Sprachnachweise/Deutschkenntnisse für internationale Bewerberinnen und Bewerber |
| `degree_program_fees`                               | Studiengangsgebühren                                                            |
