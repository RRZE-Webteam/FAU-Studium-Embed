const path = require('path');
const fs = require('fs');

const configPath = path.join(__dirname, '/tsconfig.eslint.json');


const foldersUnderTsDirectory = fs
  .readdirSync('resources/ts', { withFileTypes: true })
  .filter(dirent => dirent.isDirectory() && dirent.name !== 'defs')
  .map(dirent => dirent.name);

/** @type {import('eslint').Linter.Config} */
module.exports = {
    extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/recommended',
        'airbnb-typescript',
        'plugin:prettier/recommended',
        'plugin:react-hooks/recommended',
        'plugin:jsx-a11y/recommended'
    ],
    plugins: ['@typescript-eslint', 'simple-import-sort', 'unused-imports', 'jsx-a11y', 'react-hooks'],
    parser: '@typescript-eslint/parser',
    parserOptions: {
        project: configPath,
    },
    rules: {
        'unused-imports/no-unused-imports': 'error',
        'import/order': 'off',
        'simple-import-sort/imports': [
            'error',
            {
                groups: [
                    // External dependencies
                    // 1. `react` related packages come first
                    // 2. Other packages (apart from @wordpress)
                    ['^react', '^(@(?!wordpress))?\\w'],
                    // WordPress packages
                    ['^@wordpress/.*|$'],
                    // Internal imports
                    // 1. Side effect imports
                    // 2. Parent imports. Put `..` last
                    // 3. Other relative imports. Put same-folder imports and `.` last,
                    [`^(${foldersUnderTsDirectory.join('|')})(/.*|$)`],
                    [
                        '^\\u0000',
                        '^\\.\\.(?!/?$)',
                        '^\\.\\./?$',
                        '^\\./(?=.*/)(?!/?$)',
                        '^\\.(?!/?$)',
                        '^\\./?$',
                    ],
                    // Type imports
                    // 1. Parent types
                    // 2. Local types
                    ['^[\\./]?defs', '^\\.defs\\.ts'],
                    // Style imports
                    ['^.+\\.s?css$'],
                ],
            },
        ],
        'simple-import-sort/exports': 'error',
        'react/jsx-props-no-spreading': 'off',
        'no-param-reassign': [
            'error',
            {
                props: true,
                // Allow param re-assignment for immer
                ignorePropertyModificationsForRegex: ["^draft"]
            },
        ],
        'react-hooks/exhaustive-deps': 'off',
    },
};
