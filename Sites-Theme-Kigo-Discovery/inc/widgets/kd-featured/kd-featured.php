<?php

class KD_Featured extends KD_Widget2 {

    public function __construct() {

        parent::__construct(
            'kd_featured', // Base ID
            __( 'KD Featured Properties', 'kd' ), // Name
            array( 'description' => __( 'Displays featured properties slider.', 'kd' ), ) // Args
        );

        $this->filename = 'kd-featured';

        /* Get all saved properties (bapi_keywords_array) to create select options */
        $properties = get_properties();

        $options = [];
        foreach($properties as $property){
            $options[$property['pkid']] = $property['Name'];
        }

        $this->controls = [
            ['name' => 'General', 'fields' =>
                [
                    'title'             => ['type' => 'text',       'label'     => 'Title'],
                    'text'              => ['type' => 'text',       'label'    => 'Button text'],
                    'link'              => ['type' => 'text',       'label'    => 'Button link'],
                    'userandom'         => ['type' => 'checkbox',   'label'     => 'Use API properties',   'default' => false ],
                    'visible_featured'  => ['type' => 'select',     'multiple'  => true,                    'options' => $options,  'label' =>'Select the properties to show in frontend:', 'description' =>'<i class="fa fa-info-circle"></i> All properties listed below have been selected as featured ones in KD options' ]
                ]
            ],
            ['name' => 'Style', 'fields' =>
                [
                    'color' => ['type' => 'color', 'label' => 'Background color'],
                ]
            ],
        ];

    }

}

add_action( 'widgets_init', function(){
    register_widget( 'KD_Featured' );
});