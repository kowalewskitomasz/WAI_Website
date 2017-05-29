<div id="content">
	<div id="tekstwcontencie">
		<form action="/upload" method="post" enctype="multipart/form-data">
			Wybierz zdjecie do uploadu:	<br><input type="file" name="fileToUpload" id="fileToUpload"><br>
			Znak wodny:<br><input type="text" name="watermark" id="watermark"><br>
			Autor:<br> <input type="text" name="author"><br>
			Tytuł:<br> <input type="text" name="title"><br>
			<input type="submit" value="Wrzuć zdjęcie" name="submit">
		</form>
		<br><br>
		Zdjęcia w galerii:
		
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
			
		<?php if(is_user_logged_in()): ?>
		<tr>
			<td colspan="5">
				<input type="submit" value="Save" name="submit1">	
			</td>
		</tr>
		<?php endif; ?>	
		</table>
		</form>	
	</div>
</div>