		<?php $wp_STAB_postion = get_option('STAB_postion'); ?>
		<div class="scroll-btn-container <?php if($wp_STAB_postion==1){ echo "stab-left"; } if($wp_STAB_postion==2){ echo "stab-center"; }  ?>">
			<?php $wp_STAB_icon_Select = get_option('STAB_icon_Select');
			$wp_STAB_scroll_speed = get_option('STAB_scroll_speed');
			$wp_STAB_whichBtn = get_option('STAB_whichBtn');
			if($wp_STAB_icon_Select != true){
				$wp_STAB_icon_Select =2;
			}
			if($wp_STAB_icon_Select=="13"){
				$stab_up_btn = get_option('stab_up_btn');
				$stab_down_btn = get_option('stab_down_btn');
				if($wp_STAB_whichBtn == 2) { ?>
					<div style="display:none;" class="scroll-to-top" id="scroll-to-top">
						<?php echo '<img alt="&uarr;" width="32" height="32" src="' .$stab_up_btn. '" > '; ?>
					</div>
				<?php } elseif($wp_STAB_whichBtn == 3) { ?>
					<div style="display:block;" class="scroll-to-bottom" id="scroll-to-bottom">
						<?php echo '<img alt="&darr;" width="32" height="32" src="' .$stab_down_btn. '" > '; ?>
					</div>
				<?php } else { ?>
					<div style="display:none;" class="scroll-to-top" id="scroll-to-top">
						<?php echo '<img alt="&uarr;" width="32" height="32" src="' .$stab_up_btn. '" > '; ?>
					</div>
					<div style="display:block;" class="scroll-to-bottom" id="scroll-to-bottom">
						<?php echo '<img alt="&darr;" width="32" height="32" src="' .$stab_down_btn. '" > '; ?>
					</div>
						
				<?php }
			} else {
				if($wp_STAB_whichBtn == 2) { ?>
				<div style="display:none;" class="scroll-to-top" id="scroll-to-top">
					<?php echo '<img alt="&uarr;" width="32" height="32" src="' .plugins_url( 'scroll-top-and-bottom/icon/'.$wp_STAB_icon_Select.'_u.ico' , dirname(__FILE__) ). '" > '; ?>
				</div>
				<?php } elseif($wp_STAB_whichBtn == 3) { ?>
				<div style="display:block;" class="scroll-to-bottom" id="scroll-to-bottom">
				<?php echo '<img alt="&darr;" width="32" height="32" src="' .plugins_url( 'scroll-top-and-bottom/icon/'.$wp_STAB_icon_Select.'_d.ico' , dirname(__FILE__) ). '" > '; ?>
				</div>
				<?php } else { ?> 
				<div style="display:none;" class="scroll-to-top" id="scroll-to-top">
					<?php echo '<img alt="&uarr;" width="32" height="32" src="' .plugins_url( 'scroll-top-and-bottom/icon/'.$wp_STAB_icon_Select.'_u.ico' , dirname(__FILE__) ). '" > '; ?>
				</div>
				<div style="display:block;" class="scroll-to-bottom" id="scroll-to-bottom">
					<?php echo '<img alt="&darr;" width="32" height="32" src="' .plugins_url( 'scroll-top-and-bottom/icon/'.$wp_STAB_icon_Select.'_d.ico' , dirname(__FILE__) ). '" > '; ?>
				</div>
			<?php } } ?>
			</div>	

			<script>
				jQuery(document).ready(function() {

					jQuery('.scroll-to-top').click(function(){
						jQuery("html,body").animate({ scrollTop: 0 }, <?php if($wp_STAB_scroll_speed=="") echo "1000"; else echo "$wp_STAB_scroll_speed";?>);
						return false;
					});
					jQuery('.scroll-to-bottom').click(function(){
						jQuery('html,body').animate({scrollTop: jQuery(document).height()}, <?php if($wp_STAB_scroll_speed=="") echo "1000"; else echo "$wp_STAB_scroll_speed";?>);
						return false;
					});
				});
			</script>