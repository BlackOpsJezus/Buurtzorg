<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
	// create and return the page menu (in html)
	// called by pagebuilder.inc.php init method
	class Mainmenu {
		public function getHtml() {
			$imgUsers = "<img src='".ICONS_PATH."noun_users_50px.png' />";

			$mainmenu = "<ul>";
				if(PAGE == 'home') { $class='active'; } else { $class=''; }
	            $mainmenu .= "<li><a href='".HOME_PATH."' class=$class>Home</a></li>";
			
			    if(PAGE == 'sollicitatie') { $class='active'; } else { $class=''; }
	            $mainmenu .= "<li><a href='".SOLLICITATIE_PATH."' class=$class>Sollicitatie</a></li>";

	            if(PAGE == 'vacature') { $class='active'; } else { $class=''; }
				$mainmenu .= "<li><a href='".VACATURE_PATH."' class=$class>Vacature</a></li>";

				if(PAGE == 'gebruikers') { $class='active'; } else { $class=''; }
				$mainmenu .= "<li><a href='".GEBRUIKERS_PATH."' class=$class>Gebruikers</a></li>";

				if($_SESSION['user']['role'] == 'admin') {
					$mainmenu .= "<li><a href='".ADMIN_PATH."'>Goedkeuring WTV</a></li>";
				}

				if($_SESSION['user']['role'] == 'admin') {
					$mainmenu .= "<li><a href='".GEKOZEN_PATH."'>Stemmen</a></li>";
				}

				if($_SESSION['user']['role'] == 'admin') {
					$mainmenu .= "<li><a href='".MAIL_PATH."'>Mail</a></li>";
				}


				if($_SESSION['user']['role'] == 'admin') {
					$mainmenu .= "<li><a href='".GOEDKEURING_PATH."'>Goedkeuring PO</a></li>";
				}

				if(isset($_SESSION['user']['isloggedin'])) {
					$mainmenu .= "<li><a href='".LOGOUT_PATH."'>Uitloggen</a></li>";
				}

			$mainmenu .= "</ul>";
			return $mainmenu;
		}
	}
