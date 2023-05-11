import React, { useCallback, useEffect, useMemo, useState } from 'react';
import { find } from 'lodash';

import { BaseControl, FormTokenField } from '@wordpress/components';
import { useDebounce } from '@wordpress/compose';
import { useSelect } from '@wordpress/data';
import { _x } from '@wordpress/i18n';

import { DegreeProgram, LanguageCodes } from '../../defs';
import { store as degreeProgramStore } from '../../store';
import { DegreeProgramStore, MapSelect } from '../../store/defs';

interface SelectDegreeProgramControlProps {
    value: number;
    lang: LanguageCodes;
    onChange(value: number): void;
}

const MAX_SUGGESTIONS = 20;

const degreeProgramToToken = (degreeProgram: DegreeProgram): string =>
    `${degreeProgram.title} (${degreeProgram.degree.abbreviation})`;
export default function SelectDegreeProgramControl({
    value,
    lang = 'de',
    onChange,
}: SelectDegreeProgramControlProps) {
    const [formTokenFieldValue, setFormTokenFieldValue] = useState<string>('');
    const [search, setSearch] = useState('');
    const debouncedSearch = useDebounce(setSearch, 500);

    const degreeProgram: DegreeProgram | null | undefined = useSelect<
        MapSelect<DegreeProgramStore>
    >((select) => select(degreeProgramStore.name).getDegreeProgram(value, lang), [value, lang]);

    const searchedDegreePrograms: Array<DegreeProgram> | null | undefined = useSelect<
        MapSelect<DegreeProgramStore>
    >(
        (select) =>
            select(degreeProgramStore.name).getDegreePrograms({
                search,
                lang,
                per_page: MAX_SUGGESTIONS,
            }),
        [search, lang],
    );

    const findDegreeProgramByToken = useCallback<(token: string) => DegreeProgram | undefined>(
        (token) => {
            const availableDegreePrograms = [...(searchedDegreePrograms ?? [])];
            if (degreeProgram) {
                availableDegreePrograms.push(degreeProgram);
            }

            return find(availableDegreePrograms, (item) => degreeProgramToToken(item) === token);
        },
        [degreeProgram, searchedDegreePrograms],
    );

    useEffect(() => {
        if (degreeProgram) {
            setFormTokenFieldValue(degreeProgramToToken(degreeProgram));
        }
    }, [degreeProgram]);

    const suggestions = useMemo<Array<string>>(() => {
        if (degreeProgram || !searchedDegreePrograms) {
            return [];
        }

        return searchedDegreePrograms.map(degreeProgramToToken);
    }, [searchedDegreePrograms, degreeProgram]);

    const onChangeTokens = (tokens) => {
        const selectedTitle = tokens[0] ?? '';
        const newDegreeProgram = findDegreeProgramByToken(tokens[0]);

        setFormTokenFieldValue(selectedTitle);
        onChange(newDegreeProgram?.id ?? 0);
        setSearch('');
    };

    return (
        <BaseControl>
            <div tabIndex={-1} role="listbox">
                <FormTokenField
                    label={_x(
                        'Select degree program',
                        'backoffice: block editor',
                        'fau-degree-program-output',
                    )}
                    maxLength={1}
                    value={formTokenFieldValue ? [formTokenFieldValue] : []}
                    suggestions={suggestions}
                    onChange={onChangeTokens}
                    onInputChange={debouncedSearch}
                    maxSuggestions={MAX_SUGGESTIONS}
                    __experimentalShowHowTo={false}
                    __experimentalValidateInput={(token) => !!findDegreeProgramByToken(token)}
                    __experimentalExpandOnFocus
                />
                {searchedDegreePrograms === null && (
                    <p>
                        {_x(
                            'Could not load suggestion list',
                            'backend: block editor',
                            'fau-degree-program-output',
                        )}
                    </p>
                )}
            </div>
        </BaseControl>
    );
}
