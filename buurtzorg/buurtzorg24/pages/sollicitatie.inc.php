<?php
/*************************************************************
	Pagebuilder framework application
	Learning application for VISTA AO JL2 P5
	Created 2019 by e.steens
*************************************************************/
/*
	Contains details of page information
	returns the built html
	Class Name convention: <pagename>Page
	Must contain iPage interface implementation ie getHtml()
	Called by content.inc.php
*/
class SollicitatiePage extends Core implements iPage{
	
		public function getHtml() {
			if(defined('ACTION')) {			// process the action obtained is existent
				switch(ACTION) {
					// get html for the required action
					case "create"	: return $this->create(); break;
				}
			} else { // no ACTION so normal page
				$table 	= $this->getData();		// get users from database in tableform
				$html = $table;
				return $html;
			}
		}



		private function getData(){
			// execute a query and return the result
			$sql='SELECT vac_titel,vac_tekst,vac_id FROM `tb_vacature` ORDER BY vac_id';
            $result = $this->createTable(Database::getData($sql));

			//TODO: generate JSON output like this for webservices in future
			/*
				$data = Database::getData($sql);
				$json = Database::jsonParse($data);
				$array = Database::jsonParse($json);

				echo "<br />result: ";  print_r(Database::getData($sql));
	            echo "<br /><br />json :" . $json;
	            echo "<br /><br />array :"; print_r($array);
			*/

			return $result;
		} // end function getData()

		private function createTable($p_aDbResult){ // create html table from dbase result
			$image = "<img src='".ICONS_PATH."noun_information user_24px.png' />";
			$table = "<table border='1'>";
				$table .= " <th>Functie</th>
							<th>Info</th>
							<th>Solliciteer</th>";
				// now process every row in the $dbResult array and convert into table
				foreach ($p_aDbResult as $row){
					$i = 0;
					$table .= "<tr>";
						foreach ($row as $col) {
							if($i < 2) {
								$table .= "<td>" . $col . "</td>";
							}
							$i++; 	
						}
	                    // calculate url and trim all parameters [0..9]
	                    $url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
						// create new link with parameter (== edit user link!)
						$table 	.= "<td><a href="
								. $url 							// current menu
								. "/create/" . $row["vac_id"] 	// add ACTION and PARAM to the link
								. ">$image</a></td>";			// link to edit icon
					$table .= "</tr>";
					
				} // foreach
			$table .= "</table>";
			return $table;
		} //function

		// [C]rud action
		// based on sent form 'frmAddUser' fields
		private function create() {
			// use variabel field  from form for processing -->
			if(isset($_POST['frmAddUser'])) {
				return $this->processFormAddUser();
			} // ifisset
			else {
				return $this->addForm();
			} //else
		}

		private function addForm() { // processed in $this->processFormAddUser()
			$url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]"); 	// strip not required info
			// heredoc statement. Everything between 2 HTML labels is put into $html
			
			$sql = "SELECT vac_id FROM tb_vacature WHERE vac_id = ?";
			$aData = array(PARAM);
			//run query and obtain result
			$result = Database::getData($sql,$aData);
			
			$vac_id = $result[0]['vac_id'];
			
			$html = <<<HTML
				<fieldset>
					<legend>Solliciteer</legend>
						<form action="$url" enctype="multipart/formdata" method="post">
							<label>Naam</label>
							<input type="text" name="naam" id="" value="" placeholder="Naam" />
							
							<label>Adres</label>
							<input type="text" name="adres" id="" value="" placeholder="Adres" />
							
							<label>gebdatum</label>
							<input type="date" name="gebdatum" id="" value="" placeholder="Gebdatum" />
							
							<label>email</label>
							<input type="email" name="email" id="" value="" placeholder="Email" />
							
							<label></label>
							<input type="hidden" name="vac_id" value="$vac_id"  />

							<label></label>
							<!-- add hidden field for processing -->
							<input type="hidden" name="frmAddUser" value="frmAddUser" />
							<input type="submit" name="submit" value="Solliciteer" />
						</form>
				</fieldset>
HTML;
			return $html;
		} // function

		 private function processFormAddUser() {
			$naamid     = $this->createUuid();
			
			$naam 	    = $_POST['naam'];
			$adres	    = $_POST['adres'];
			$gebdatum	= $_POST['gebdatum'];
			$mail 	    = $_POST['email'];
			$vac_id     = $_POST['vac_id'];

			// create insert query with all info above
			$sql = "INSERT
						INTO tb_soll
							(naamid, naam, adres, gebdatum, mail, vac_id)
								VALUES
									('$naamid', '$naam', '$adres', '$gebdatum', '$mail', '$vac_id')";

			Database::getData($sql);
			/*
				echo "<br />";
				echo $hash . "<br />";
				echo $uuid . "<br />";
				echo $hashDate . "<br />";
			*/
		return "gesolliciteerd.";
		} //function

	}// class gebruikerPage
?>