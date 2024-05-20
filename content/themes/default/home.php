<?php include  TEMPLATE_PATH . "/includes/header.php" ?>
<div class="container">
	<div class="game-container">
		<?php widget_aside('top-content') ?>
		<h3 class="item-title"><i class="fa fa-plus" aria-hidden="true"></i><?php _e('NEW GAMES') ?></h3>
		<div class="row">
			<?php
			$games = get_game_list('new', 12, 0, false)['results'];
			foreach ( $games as $game ) { ?>
				<?php include  TEMPLATE_PATH . "/includes/grid.php" ?>
			<?php } ?>
		</div>
		<h3 class="item-title"><i class="fa fa-certificate" aria-hidden="true"></i><?php _e('POPULAR GAMES') ?></h3>
		<div class="row">
			<?php
			$games = get_game_list('popular', 12, 0, false)['results'];
			foreach ( $games as $game ) { ?>
				<?php include  TEMPLATE_PATH . "/includes/grid.php" ?>
			<?php } ?>
		</div>
		<h3 class="item-title"><i class="fa fa-gamepad" aria-hidden="true"></i><?php _e('YOU MAY LIKE') ?></h3>
		<div class="row">
			<?php
			$games = get_game_list('random', 12, 0, false)['results'];
			foreach ( $games as $game ) { ?>
				<?php include  TEMPLATE_PATH . "/includes/grid.php" ?>
			<?php } ?>
		</div>
		<?php widget_aside('bottom-content') ?>
	</div>
	<div class="mb-4 mt-4 hp-bottom-container">
		<?php widget_aside('homepage-bottom') ?>
	</div>
</div>
<?php include  TEMPLATE_PATH . "/includes/footer.php" ?>