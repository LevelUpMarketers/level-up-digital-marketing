<?php

namespace TotalTheme\Customizer\Controls;

use WP_Customize_Control;

\defined( 'ABSPATH' ) || exit;

/**
 * Customizer Card Style Select.
 */
class Card_Select extends WP_Customize_Control {

	/**
	 * The control type.
	 */
	public $type = 'totaltheme_card_select';

	/**
	 * Send data to content_template.
	 */
	public function to_json() {
		parent::to_json();

		$this->json['value'] = $this->value();
		$this->json['id']    = $this->id;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 */
	public function render_content() {}

	/**
	 * Render the content
	 */
	public function content_template() { ?>
		<# if ( data.label ) { #>
			<label for="_customize-input-{{ data.id }}" class="customize-control-title">{{ data.label }}</label>
		<# } #>

		<span id="_customize-description-{{ data.id }}" class="description customize-control-description"><?php echo \sprintf( \esc_html__( 'Select a card style to override the default entry design using a preset theme card. %sLearn more%s', 'total' ), '<a href="https://totalwptheme.com/docs/total-theme-cards/" target="_blank" rel="noopener noreferrer">', ' &#8599;</a>' ); ?></span>

		<div class="total-customize-chosen-wrap">
			<select id="_customize-input-{{ data.id }}" data-customize-setting-link="{{ data.id }}">
				<?php foreach ( wpex_choices_card_styles() as $name => $label ) { ?>
					<option value="<?php echo esc_attr( $name ); ?>"<# if ( "<?php echo esc_attr( $name ); ?>" === data.value ) { #> selected<# } #>><?php echo esc_html( $label ); ?></option>
				<?php } ?>
			</select>
		</div>
	<?php }
}
