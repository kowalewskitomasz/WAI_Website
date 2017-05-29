<br>
<form action="/zapamietaj" method="post" enctype="multipart/form-data">
<table>
		<tr>
			<th>Zdjęcie</th>
			<th>Autor</th>
			<th>Tytuł</th>
			<th>Id</th>	
			<?php if(is_user_logged_in()): ?>	
			<th>Save</th>		
			<?php endif; ?>		
		</tr>
		<?php 
		if(isset($photos)):
		foreach ($photos as $photo): ?>
		<tr>
		<td>
			<a href="<?php echo $photo['pathToImageWatermarked'];?>" target="_blank" />
				<img src="<?php echo $photo['pathToImageThumbnail'];?>" />	
			</a>
		</td>
		<td>
			<?php echo $photo['author'];?>
		</td>
		<td>
			<?php echo $photo['title'];?>
		</td>
		<td>
			<?php echo $photo['_id'];?>
		</td>
		<?php if(is_user_logged_in()): ?>	
		<td>
			<?php 
			$photo_id = $photo['_id'];
			$checked = "";
			$checked = check_if_checked($photo_id)?>
			  <input type="checkbox" name="memorized[]" value="<?php echo $photo['_id'];?>" <?php echo $checked?>><br>
		</td>	
		<?php endif; ?>	
			</tr>
		<?php endforeach ?>
		<?php endif; ?>	
			
		<?php if(is_user_logged_in()): ?>
		<tr>
			<td colspan="5">
				<input type="submit" value="Save" name="submit1">	
			</td>
		</tr>
		<?php endif; ?>	
</table>
</form>	