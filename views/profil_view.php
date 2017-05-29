<div id="content">
	<div id="tekstwcontencie">
<ul>
	<?php foreach($_SESSION['user'] as $k => $v): ?>
		<li><?php echo $k ?>: <?php echo $v ?></li>
	<?php endforeach;?>
</ul>
	</div>
</div>