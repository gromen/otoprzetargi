/**
 * NOO VC Admin Package.
 *
 * Javascript used in meta-boxes for Post and Page.
 *
 * @package    NOO Framework
 * @subpackage NOO VC Admin
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

(function ( $ ) {
	$( function () {
		/*
		 Class used in edit form and editor models to save/render param type
		 */
		window.vc.atts.radio_image = {
			/**
			 * Used to save multiple values in single string for saving/parsing/opening
			 * @param param
			 * @returns {string}
			 */
			parse: function ( param ) {
				var arr, newValue;

				arr = [];
				newValue = '';
				$( 'input[name=' + param.param_name + ']', this.content() ).each( function () {
					var self;

					self = $( this );
					if ( this.checked ) {
						arr.push( self.attr( 'value' ) );
					}
				} );
				if ( 0 < arr.length ) {
					newValue = arr.join( ',' );
				}
				return newValue;
			},
			/**
			 * Used in shortcode saving
			 * Default: '' empty (unchecked)
			 * Can be overwritten by 'std'
			 * @param param
			 * @returns {string}
			 */
			defaults: function ( param ) {
				return ''; // needed for saving - without this default value for param will be first value in array
			}
		};
	} );
})( window.jQuery );