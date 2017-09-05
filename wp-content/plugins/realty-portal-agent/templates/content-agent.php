<?php
/**
 * Content Agent
 *
 * @package Realty_Portal
 * @author  NooTeam <suppport@nootheme.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $agent;

if ( !is_object( $agent ) ) {
	return false;
}
?>
<li class="profile-detail-block">
    <div class="media">
        <div class="media-left">
            <figure>
                <a href="<?php echo $agent->permalink() ?>" title="<?php echo $agent->title() ?>">
					<?php echo $agent->agent_avatar( 'rp-property-thumbnail' ); ?>
                </a>
            </figure>
            <button class="rp-button hidden-xs" onclick="window.location.href='<?php echo $agent->permalink() ?>'">
	            <?php echo esc_html__( 'View My Properties', 'realty-portal-agent' ); ?>
            </button>
        </div>
        <div class="media-body">
            <div class="profile-description">
                <h2 class="agent-title">
                    <a href="<?php echo $agent->permalink() ?>" title="<?php echo $agent->title() ?>">
						<?php echo $agent->title() ?>
                    </a>
                </h2>
                <p class="position"><?php echo $agent->agent_info( 'position' ) ?></p>
                <p><?php echo $agent->agent_info( 'about' ) ?></p>
                <ul class="profile-contact">
                    <li>
                        <span><?php echo esc_html__( 'Phone:', 'realty-portal-agent' ); ?></span>
                        <a href="tel:<?php echo $agent->agent_info( 'phone' ) ?>"><?php echo $agent->agent_info( 'phone' ) ?></a>
                    </li>
                    <li>
                        <span><?php echo esc_html__( 'Mobile:', 'realty-portal-agent' ); ?></span>
                        <a href="tel:<?php echo $agent->agent_info( 'mobile' ) ?>"><?php echo $agent->agent_info( 'mobile' ) ?></a>
                    </li>
                </ul>
                <ul class="profile-social">
					<?php RP_Template::get_template( 'loop/social.php', '', '', RP_AGENT_TEMPLATES ); ?>
                </ul>
                <button class="rp-button visible-xs" onclick="window.location.href='<?php echo $agent->permalink() ?>'">
		            <?php echo esc_html__( 'View My Properties', 'realty-portal-agent' ); ?>
                </button>
            </div>
        </div>
    </div>
</li>