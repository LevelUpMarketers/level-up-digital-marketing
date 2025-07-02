<?php

defined( 'ABSPATH' ) || exit;

foreach ( $settings->sections as $section_k => $section_v ) {
	if ( empty( $section_v['settings'] ) ) {
		continue;
	}

	$this->start_controls_section(
		"section_{$section_k}",
		[
			'label' => $section_v['label'],
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		]
	);

	foreach ( $section_v['settings'] as $setting => $setting_args ) {
		if ( ! empty( $setting_args['group'] ) ) {
			$this->add_group_control( $setting_args['group']['id'], $setting_args['group']['args'] );
		} elseif ( ! empty( $setting_args['repeater'] ) ) {
			$repeater = new \Elementor\Repeater();
			foreach ( $setting_args['repeater'] as $repeater_control_id => $repeater_control_settings ) {
				$repeater->add_control( $repeater_control_id, $repeater_control_settings );
			}
			$this->add_control( $setting, $setting_args );
		} elseif ( ! empty( $setting_args['responsive'] ) ) {
			$this->add_responsive_control( $setting, $setting_args );
		} else {
			if ( isset( $setting_args['type'] ) && 'text' === $setting_args['type'] && ! in_array( $setting, [ 'content', 'heading', 'text' ], true ) ) {
				$setting_args['ai'] = [ 'active' => false ];
			}
			$this->add_control( $setting, $setting_args );
		}
	}

	$this->end_controls_section();
}
