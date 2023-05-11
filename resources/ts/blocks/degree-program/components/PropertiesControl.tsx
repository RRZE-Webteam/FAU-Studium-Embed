import React from 'react';

import { BaseControl, FormTokenField } from '@wordpress/components';

import serverData from '../../util/serverData';

interface Props {
    label: string;
    value: Array<string>;
    onChange(value: Array<string>): void;
}
const propertyToTokenMap = {};
const tokenToPropertyMap = {};

Object.entries(serverData().supportedProperties).forEach(([property, title]) => {
    const token = title;
    propertyToTokenMap[property] = token;
    tokenToPropertyMap[token] = property;
});

const suggestions = Object.values<string>(propertyToTokenMap).sort();

export default function PropertiesControl({ label, value, onChange }: Props) {
    return (
        <BaseControl>
            <div tabIndex={-1} role="listbox">
                <FormTokenField
                    label={label}
                    value={value.map((item) => propertyToTokenMap[item])}
                    suggestions={suggestions}
                    onChange={(tokens) =>
                        onChange(tokens.map((token) => tokenToPropertyMap[token as string]))
                    }
                    __experimentalShowHowTo={false}
                    __experimentalValidateInput={(token) => !!tokenToPropertyMap[token]}
                    __experimentalExpandOnFocus
                />
            </div>
        </BaseControl>
    );
}
