<nav id="underheader">
	<div class="drop">
		<ul class="drop_menu">
		
			<?php if(is_user_logged_in()): ?>
			<li>
				<a href='galeria_zapamietane'>Zapamiętane</a>
			</li>
			<?php endif; ?>
			<li>
				<a href='galeria_search'>Wyszukiwanie</a>
			</li>
			<li>
				<a href='galeria'>Galeria</a>
			</li>
			<li>
				<a href='poco'>Po co?</a>
			</li>
			<li>
				<a href='etykieta'>Etykieta</a>
			</li>
			<li>
				<a href='mojtrening'>Mój trening</a>
				<ul>
					<li><a href='mojtreningklata'>Klata</a></li>
					<li><a href='mojtreningplecy'>Plecy </a></li>
					<li><a href='mojtreningnogi'>Nogi</a></li>
					<li><a href='mojtreningbarki'>Barki</a></li>
				</ul>
			</li>	
			
			<?php if(!is_user_logged_in()): ?>
			<li>
			   <a href='zaloguj'>Zaloguj</a>				
			</li>
			<li>	
			<a href='zarejestruj'>Zarejestruj</a>			
			</li>
			<?php endif; ?>
			
			<?php if(is_user_logged_in()): ?>
			<li>	
			   <a href='wyloguj'>Wyloguj</a>			
			</li>
			<li>	
			   <a href='profil'>Profil</a>			
			</li>
			<?php endif;?>
		</ul>
	</div>
</nav>
