/**
 * NOO Meta Boxes Package.
 *
 * Javascript used in meta-boxes for Post, Page and Portfolio.
 *
 * @package    NOO Framework
 * @subpackage NOO Meta Boxes
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

// Post Format
jQuery( document ).ready( function ( $ ) {
	if ($('#post-formats-select').length > 0) {
		// Add class for the child boxes

		// Image
		$('#_noo_wp_post_meta_box_image').addClass('post-formats post-format-image');
		$('label[for="_noo_wp_post_meta_box_image-hide"]').addClass('post-formats post-format-image');

		// Gallery
		$('#_noo_wp_post_meta_box_gallery').addClass('post-formats post-format-gallery');
		$('label[for="_noo_wp_post_meta_box_gallery-hide"]').addClass('post-formats post-format-gallery');

		// Video
		$('#_noo_wp_post_meta_box_video').addClass('post-formats post-format-video');
		$('label[for="_noo_wp_post_meta_box_video-hide"]').addClass('post-formats post-format-video');
		
		// Link
		$('#_noo_wp_post_meta_box_link').addClass('post-formats post-format-link');
		$('label[for="_noo_wp_post_meta_box_link-hide"]').addClass('post-formats post-format-link');

		// Quote
		$('#_noo_wp_post_meta_box_quote').addClass('post-formats post-format-quote');
		$('label[for="_noo_wp_post_meta_box_quote-hide"]').addClass('post-formats post-format-quote');

		// Status
		$('#_noo_wp_post_meta_box_status').addClass('post-formats post-format-status');
		$('label[for="_noo_wp_post_meta_box_status-hide"]').addClass('post-formats post-format-status');

		// Audio
		$('#_noo_wp_post_meta_box_audio').addClass('post-formats post-format-audio');
		$('label[for="_noo_wp_post_meta_box_audio-hide"]').addClass('post-formats post-format-audio');

		// Show the active format type
		var checkedElement = $('#post-formats-select').find('input:checked');
		$('.post-formats').hide();
		$('.' + checkedElement.attr('id')).show();

		// When click, display the according format type.
		$('#post-formats-select').find('input').click(function () {
			$this = $(this);
			$('.post-formats').hide();
			$('.' + $this.attr('id')).show();
		});
	}
} );

// Page Template
jQuery( document ).ready( function ( $ ) {
	if ($('#page_template').length > 0) {
		// Add class for the child boxes

		// Sidebar
		$('#_noo_wp_page_meta_box_sidebar').addClass('page-templates page-template-page-left-sidebar_php page-template-page-right-sidebar_php');
		$('label[for="_noo_wp_page_meta_box_sidebar-hide"]').addClass('page-templates page-template-page-left-sidebar_php page-template-page-right-sidebar_php');

		// Show the active format type
		var selectedVal = $('#page_template option:selected').val();
		selectedVal = selectedVal.replace(".", "_");
		$('.page-templates').hide();
		$('.page-template-' + selectedVal).show();

		/**
		 * Hide setting when select page half map
		 */
		if ( selectedVal === 'property-half-map_php' ) {
			$('._noo_wp_page_menu_style, ._noo_wp_page_nav_position, ._noo_wp_page_footer_style, hr, ._noo_wp_page_hide_title, ._heading_image').hide();
			$('.menu_header, .menu_footer, #noo_page_layout').show();
		}else if ( selectedVal === 'property-search_php' ) {
			$('._noo_wp_page_menu_style, ._noo_wp_page_nav_position, ._noo_wp_page_footer_style, hr, ._noo_wp_page_hide_title, ._heading_image').show();
			$('.page_search_template, #noo_page_layout').show();
			$('.noo_property_submit_style, .menu_header, .menu_footer').hide();
		} else {
			$('._noo_wp_page_menu_style, ._noo_wp_page_nav_position, ._noo_wp_page_footer_style, hr, ._noo_wp_page_hide_title, ._heading_image').show();
			$('.menu_header, .menu_footer, #noo_page_layout').hide();
		}

		// When choose template with sidebar, display the Sidebar meta-box
		$('#page_template').change(function () {
			var selectedVal = $('#page_template option:selected').val();
			selectedVal = selectedVal.replace(".", "_");
			$('.page-templates').hide();
			$('.page-template-' + selectedVal).show();
			
			/**
			 * Hide setting when select page half map
			 */
			if ( selectedVal === 'property-half-map_php' ) {
				$('._noo_wp_page_menu_style, ._noo_wp_page_nav_position, ._noo_wp_page_footer_style, hr, ._noo_wp_page_hide_title, ._heading_image').hide();
				$('.menu_header, .menu_footer, #noo_page_layout').show();
				$('.page_search_template').hide();
			}else if ( selectedVal === 'property-search_php' ) {
				$('._noo_wp_page_menu_style, ._noo_wp_page_nav_position, ._noo_wp_page_footer_style, hr, ._noo_wp_page_hide_title, ._heading_image').show();
				$('.page_search_template, #noo_page_layout').show();
				$('.noo_property_submit_style, .menu_header, .menu_footer').hide();
			} else {
				$('._noo_wp_page_menu_style, ._noo_wp_page_nav_position, ._noo_wp_page_footer_style, hr, ._noo_wp_page_hide_title, ._heading_image').show();
				$('.menu_header, .menu_footer, .noo_property_submit_style, .page_search_template, #noo_page_layout').hide();
			}
		});
	}

	if ( $('#page_search_template').length > 0 ) {
		if ( $('#page_search_template_1:checked').val() === 'half-map' ) {
			$('._noo_wp_page_menu_style, ._noo_wp_page_nav_position, ._noo_wp_page_footer_style, hr, ._noo_wp_page_hide_title, ._heading_image').show();
			$('.menu_header, .menu_footer').show();
			$('.page_search_template').hide();
		}

		$('#page_search_template').on('change', 'input[type="radio"]', function(event) {
			event.preventDefault();
			var value_search_template = $(this).val();
			if ( value_search_template === 'half-map' ) {
				$('._noo_wp_page_menu_style, ._noo_wp_page_nav_position, ._noo_wp_page_footer_style, hr, ._noo_wp_page_hide_title, ._heading_image').show();
				$('.menu_header, .menu_footer').show();
			} else {
				$('._noo_wp_page_menu_style, ._noo_wp_page_nav_position, ._noo_wp_page_footer_style, hr, ._noo_wp_page_hide_title, ._heading_image').show();
				$('.menu_header, .menu_footer').hide();
			}
		});

	}

	var property_template = function() {

		if ( $('#property_template').length > 0 ) {
			$('.property_sidebar').addClass('page-template-sidebar page-template-left_sidebar');
			// Show the active format type
			var check = $('.property_layout:checked').val();
			$('.property_sidebar').hide();
			$('.page-template-' + check).show();

			$('.property_layout').change(function () {
				var check = $('.property_layout:checked').val();
				$('.property_sidebar').hide();
				$('.page-template-' + check).show();
			});

		}

	}
	property_template();
} );