import React from 'react';

import { Flex, FlexBlock, SelectControl } from '@wordpress/components';
import { _x } from '@wordpress/i18n';

import { LanguageCodes } from '../../defs';
import { DegreeProgramBlock } from '../defs';
import PropertiesControl from './PropertiesControl';
import SelectDegreeProgramControl from './SelectDegreeProgramControl';

const DegreeProgramControls = (props: DegreeProgramBlock): JSX.Element => {
    const { attributes, setAttributes } = props;

    return (
        <>
            <SelectDegreeProgramControl
                value={attributes.id ?? 0}
                lang={attributes.lang}
                onChange={(id) => setAttributes({ id })}
            />
            <Flex>
                <FlexBlock>
                    <SelectControl
                        label={_x(
                            'Language',
                            'backoffice: block editor',
                            'fau-degree-program-common',
                        )}
                        options={[
                            { label: 'Deutsch', value: 'de' },
                            { label: 'English', value: 'en' },
                        ]}
                        value={attributes.lang}
                        onChange={(lang) => setAttributes({ lang: lang as LanguageCodes })}
                    />
                </FlexBlock>
                <FlexBlock>
                    <SelectControl
                        label={_x(
                            'Format',
                            'backoffice: block editor',
                            'fau-degree-program-common',
                        )}
                        options={[
                            { label: 'Short', value: 'short' },
                            { label: 'Full', value: 'full' },
                        ]}
                        value={attributes.format}
                        onChange={(format) => setAttributes({ format: format as 'short' | 'full' })}
                    />
                </FlexBlock>
            </Flex>
            {attributes.format === 'full' && !attributes.exclude?.length && (
                <PropertiesControl
                    label={_x('Include', 'backoffice: block editor', 'fau-degree-program-common')}
                    value={attributes.include ?? []}
                    onChange={(include) => setAttributes({ include })}
                />
            )}
            {attributes.format === 'full' && !attributes.include?.length && (
                <PropertiesControl
                    label={_x('Exclude', 'backoffice: block editor', 'fau-degree-program-common')}
                    value={attributes.exclude ?? []}
                    onChange={(exclude) => setAttributes({ exclude })}
                />
            )}
        </>
    );
};

export default DegreeProgramControls;
