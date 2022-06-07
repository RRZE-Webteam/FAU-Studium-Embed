<?php

namespace FAU_Studium_Embed\Config;

defined('ABSPATH') || exit;

/**
 * Gibt der Name der Option zurück.
 * @return array [description]
 */
function getOptionName()
{
    return 'FAU_Studium_Embed';
}

/**
 * Gibt die Einstellungen des Menus zurück.
 * @return array [description]
 */
function getMenuSettings()
{
    return [
        'page_title'    => __('FAU Studium', 'fau-studium-embed'),
        'menu_title'    => __('FAU Studium', 'fau-studium-embed'),
        'capability'    => 'manage_options',
        'menu_slug'     => 'fau-studium-embed',
        'title'         => __('FAU Studium Settings', 'fau-studium-embed'),
    ];
}

/**
 * Gibt die Einstellungen der Inhaltshilfe zurück.
 * @return array [description]
 */
function getHelpTab()
{
    return [
        [
            'id'        => 'fau-studium-help',
            'content'   => [
                '<p>' . __('Here comes the Context Help content.', 'fau-studium-embed') . '</p>'
            ],
            'title'     => __('Overview', 'fau-studium-embed'),
            'sidebar'   => sprintf('<p><strong>%1$s:</strong></p><p><a href="https://blogs.fau.de/webworking">RRZE Webworking</a></p><p><a href="https://github.com/RRZE Webteam">%2$s</a></p>', __('For more information', 'fau-studium-embed'), __('RRZE Webteam on Github', 'fau-studium-embed'))
        ]
    ];
}

/**
 * Gibt die Einstellungen der Optionsbereiche zurück.
 * @return array [description]
 */
function getSections()
{
    return [
        [
            'id'    => 'basic',
            'title' => __('Basic Settings', 'fau-studium-embed')
        ],
        [
            'id'    => 'advanced',
            'title' => __('Advanced Settings', 'fau-studium-embed')
        ]
    ];
}

/**
 * Gibt die Einstellungen der Optionsfelder zurück.
 * @return array [description]
 */
function getFields()
{
    return [
        'basic' => [
            [
                'name'              => 'text_input',
                'label'             => __('Text Input', 'fau-studium-embed'),
                'desc'              => __('Text input description.', 'fau-studium-embed'),
                'placeholder'       => __('Text Input placeholder', 'fau-studium-embed'),
                'type'              => 'text',
                'default'           => 'Title',
                'sanitize_callback' => 'sanitize_text_field'
            ],
            [
                'name'              => 'number_input',
                'label'             => __('Number Input', 'fau-studium-embed'),
                'desc'              => __('Number input description.', 'fau-studium-embed'),
                'placeholder'       => '5',
                'min'               => 0,
                'max'               => 100,
                'step'              => '1',
                'type'              => 'number',
                'default'           => 'Title',
                'sanitize_callback' => 'floatval'
            ],
            [
                'name'        => 'textarea',
                'label'       => __('Textarea Input', 'fau-studium-embed'),
                'desc'        => __('Textarea description', 'fau-studium-embed'),
                'placeholder' => __('Textarea placeholder', 'fau-studium-embed'),
                'type'        => 'textarea'
            ],
            [
                'name'  => 'checkbox',
                'label' => __('Checkbox', 'fau-studium-embed'),
                'desc'  => __('Checkbox description', 'fau-studium-embed'),
                'type'  => 'checkbox'
            ],
            [
                'name'    => 'multicheck',
                'label'   => __('Multiple checkbox', 'fau-studium-embed'),
                'desc'    => __('Multiple checkbox description.', 'fau-studium-embed'),
                'type'    => 'multicheck',
                'default' => [
                    'one' => 'one',
                    'two' => 'two'
                ],
                'options'   => [
                    'one'   => __('One', 'fau-studium-embed'),
                    'two'   => __('Two', 'fau-studium-embed'),
                    'three' => __('Three', 'fau-studium-embed'),
                    'four'  => __('Four', 'fau-studium-embed')
                ]
            ],
            [
                'name'    => 'radio',
                'label'   => __('Radio Button', 'fau-studium-embed'),
                'desc'    => __('Radio button description.', 'fau-studium-embed'),
                'type'    => 'radio',
                'options' => [
                    'yes' => __('Yes', 'fau-studium-embed'),
                    'no'  => __('No', 'fau-studium-embed')
                ]
            ],
            [
                'name'    => 'selectbox',
                'label'   => __('Dropdown', 'fau-studium-embed'),
                'desc'    => __('Dropdown description.', 'fau-studium-embed'),
                'type'    => 'select',
                'default' => 'no',
                'options' => [
                    'yes' => __('Yes', 'fau-studium-embed'),
                    'no'  => __('No', 'fau-studium-embed')
                ]
            ]
        ],
        'advanced' => [
            [
                'name'    => 'color',
                'label'   => __('Color', 'fau-studium-embed'),
                'desc'    => __('Color description.', 'fau-studium-embed'),
                'type'    => 'color',
                'default' => ''
            ],
            [
                'name'    => 'password',
                'label'   => __('Password', 'fau-studium-embed'),
                'desc'    => __('Password description.', 'fau-studium-embed'),
                'type'    => 'password',
                'default' => ''
            ],
            [
                'name'    => 'wysiwyg',
                'label'   => __('Advanced Editor', 'fau-studium-embed'),
                'desc'    => __('Advanced Editor description.', 'fau-studium-embed'),
                'type'    => 'wysiwyg',
                'default' => ''
            ],
            [
                'name'    => 'file',
                'label'   => __('File', 'fau-studium-embed'),
                'desc'    => __('File description.', 'fau-studium-embed'),
                'type'    => 'file',
                'default' => '',
                'options' => [
                    'button_label' => __('Choose an Image', 'fau-studium-embed')
                ]
            ]
        ]
    ];
}


/**
 * Gibt die Einstellungen der Parameter für Shortcode für den klassischen Editor und für Gutenberg zurück.
 * @return array [description]
 */

function getShortcodeSettings(){
	return [
		'block' => [
		    'blocktype' => 'fau-studium/SHORTCODE-NAME', // dieser Wert muss angepasst werden
		    'blockname' => 'SHORTCODE-NAME', // dieser Wert muss angepasst werden
		    'title' => 'SHORTCODE-TITEL', // Der Titel, der in der Blockauswahl im Gutenberg Editor angezeigt wird
		    'category' => 'widgets', // Die Kategorie, in der der Block im Gutenberg Editor angezeigt wird
		    'icon' => 'admin-users',  // Das Icon des Blocks
		    'tinymce_icon' => 'user', // Das Icon im TinyMCE Editor 
		],
		'Beispiel-Textfeld-Text' => [
			'default' => 'ein Beispiel-Wert',
			'field_type' => 'text', // Art des Feldes im Gutenberg Editor
			'label' => __( 'Beschriftung', 'fau-studium-embed' ),
			'type' => 'string' // Variablentyp der Eingabe
		],
		'Beispiel-Textfeld-Number' => [
			'default' => 0,
			'field_type' => 'text', // Art des Feldes im Gutenberg Editor
			'label' => __( 'Beschriftung', 'fau-studium-embed' ),
			'type' => 'number' // Variablentyp der Eingabe
		],
		'Beispiel-Textarea-String' => [
			'default' => 'ein Beispiel-Wert',
			'field_type' => 'textarea',
			'label' => __( 'Beschriftung', 'fau-studium-embed' ),
			'type' => 'string',
			'rows' => 5 // Anzahl der Zeilen 
		],
		'Beispiel-Radiobutton' => [
			'values' => [
				'wert1' => __( 'Wert 1', 'fau-studium-embed' ), // wert1 mit Beschriftung
				'wert2' => __( 'Wert 2', 'fau-studium-embed' )
			],
			'default' => 'DESC', // vorausgewählter Wert
			'field_type' => 'radio',
			'label' => __( 'Order', 'fau-studium-embed' ), // Beschriftung der Radiobutton-Gruppe
			'type' => 'string' // Variablentyp des auswählbaren Werts
		],
		'Beispiel-Checkbox' => [
			'field_type' => 'checkbox',
			'label' => __( 'Beschriftung', 'fau-studium-embed' ),
			'type' => 'boolean',
			'default'   => true // Vorauswahl: Haken gesetzt
        ],
        'Beispiel-Toggle' => [
            'field_type' => 'toggle',
            'label' => __( 'Beschriftung', 'fau-studium-embed' ),
            'type' => 'boolean',
            'default'   => true // Vorauswahl: ausgewählt
        ],
	'Beispiel-Select' => [
		'values' => [
		    [
			'id' => 'wert1',
			'val' =>  __( 'Wert 1', 'fau-studium-embed' )
		    ],
		    [
			'id' => 'wert2',
			'val' =>  __( 'Wert 2', 'fau-studium-embed' )
		    ],
		],
		'default' => 'wert1', // vorausgewählter Wert: Achtung: string, kein array!
		'field_type' => 'select',
		'label' => __( 'Beschriftung', 'fau-studium-embed' ),
		'type' => 'string' // Variablentyp des auswählbaren Werts
	],
        'Beispiel-Multi-Select' => [
		'values' => [
		    [
			'id' => 'wert1',
			'val' =>  __( 'Wert 1', 'fau-studium-embed' )
		    ],
		    [
			'id' => 'wert2',
			'val' =>  __( 'Wert 2', 'fau-studium-embed' )
		    ],
		    [
			'id' => 'wert3',
			'val' =>  __( 'Wert 3', 'fau-studium-embed' )
		    ],
		],
		'default' => ['wert1','wert3'], // vorausgewählte(r) Wert(e): Achtung: array, kein string!
		'field_type' => 'multi_select',
		'label' => __( 'Beschrifung', 'fau-studium-embed' ),
		'type' => 'array',
		'items'   => [
			'type' => 'string' // Variablentyp der auswählbaren Werte
		]
        ]
    ];
}

