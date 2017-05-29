<div id="content">
	<div id="tekstwcontencie">
		Zapamiętane zdjęcia w galerii:
		
		<form action="/zapomnij" method="post" enctype="multipart/form-data">
		<table>
		<tr>
			<th>Zdjęcie</th>
			<th>Autor</th>
			<th>Tytuł</th>
			<th>Id</th>		
			<th>Save</th>				
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
		<td>
			  <input type="checkbox" name="memorized[]" value="<?php echo $photo['_id'];?>"><br>
		</td>
			</tr>
		<?php endforeach ?>
		<tr>
			<td colspan="5">
				<input type="submit" value="Unsave" name="submit1">	
			</td>
		</tr>
		</table>
		</form>	
	</div>
</div>