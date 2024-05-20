<?php

require_once( TEMPLATE_PATH . '/functions.php' );

if ( !isset($_GET['slug']) || !$_GET['slug'] ) {
	die('Err');
	return;
}

$_GET['slug'] = htmlspecialchars($_GET['slug']);

Game::update_views( $_GET['slug'] );
$game = Game::getBySlug( $_GET['slug'] );
if($game){
	if($game->source == 'self'){
		if(file_exists(ABSPATH.'games/'.$game->slug)){
			$page_title = $game->title;
			$meta_description = str_replace(array('"', "'"), "", strip_tags($game->description));
			$url = $game->url;
			if(file_exists( TEMPLATE_PATH.'/page-splash.php' )){
				require TEMPLATE_PATH.'/page-splash.php';
				return;
			} else {
				?>
				<!DOCTYPE html>
				<html>
				<head>
					<meta charset="utf-8">
					<title><?php echo $page_title ?></title>
					<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
					<meta name="description" content="<?php echo $meta_description ?>">
					<style type="text/css">
						body {
						    color: #eee;
						    position: inherit;
						    margin: 0;
						    padding: 0;
						    overflow: hidden;
						    height: 100%;
						    background: #000;
						}
						#game-content {
						    position: absolute;
						    top: 0;
						    left: 0;
						    width: 0;
						    height: 0;
						    overflow: hidden;
						    max-width: 100%;
						    max-height: 100%;
						    min-width: 100%;
						    min-height: 100%;
						    box-sizing: border-box;
						}
						.splash {
						    background: linear-gradient(-45deg,#7887db,#e86195);
						    position: absolute;
							top: 0;
							left: 0;
							bottom: 0;
							width: 100%;
							z-index: 1;
						}
						.splash-content {
							position: absolute;
							top: 50%;
							left: 50%;
							z-index: 2;
							transform: translate(-50%, -50%);
						}
						.splash-content img {
							width: 180px;
							height: auto;
							border: 2px solid #fff;
							border-radius: 8px;
						}
						.btn-play {
							width: 184px;
							height: 60px;
							font-size: 20px;
							font-weight: bold;
							margin-top: 15px;
							background: rgba(255, 255, 255, 0.8);
							border: none;
							border-radius: 8px;
						}
						.splash-game-title {
							position: absolute;
							top: 95%;
							left: 50%;
							transform: translate(-50%, -50%);
							font-size: 20px;
						}
					</style>
					<script type="text/javascript" src="/js/api.js"></script>
				</head>
				<body>
					<div class="splash" id="splash">
						<div class="splash-content">
							<div class="splash-thumbnail">
								<img src="<?php echo $game->thumb_2 ?>">
							</div>
							<button class="btn-play" onclick="play_game()"><?php _e("Play") ?></button>
						</div>
						<div class="splash-game-title"><?php echo $game->title ?></div>
					</div>
					<iframe id="game-content" frameborder="0" allow="autoplay" allowfullscreen="" seamless="" scrolling="no" data-src="<?php echo $url ?>"></iframe>
					<script type="text/javascript">
						function play_game(){
							document.getElementById("splash").remove();
							<?php if( get_setting_value('show_ad_on_splash') ){ ?>
							ca_api.show_ad();
							<?php } ?>
							document.getElementById("game-content").src = document.getElementById("game-content").dataset.src;
						}
						ca_api.on_ad_closed = ()=>{
							//
						}
					</script>
				</body>
				</html>
				<?php
			}
		}
	}
} else {
	require( ABSPATH . 'includes/page-404.php' );
}

?>