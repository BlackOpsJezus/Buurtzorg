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
class GoedkeuringPage {
	
		public function getHtml() {
			if(defined('ACTION')) {			// process the action obtained is existent
				switch(ACTION) {
					// get html for the required action
					case "create"	: return $this->create(); break;
					case "update"	: return $this->update(); break;
					case "delete"	: return $this->delete();	
				}
			} else { // no ACTION so normal page
				$table 	= $this->getData();		// get users from database in tableform
				$html = $table;
				return $html;
			}
		}


	    // show button with the PAGE $p_sAction and the tekst $p_sActionText
		private function addButton($p_sAction, $p_sActionText) {
			// calculate url and trim all parameters [0..9]
            $url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
			// create new link with PARAM for processing in new page request
			$url = $url . $p_sAction;
			$button = "<button onclick='location.href = \"$url\";'>$p_sActionText</button>";
			return $button;
		}
	
	

		private function getData(){
			// execute a query and return the result
			$sql='SELECT naam, adres, gebdatum, mail, vac_id, naamid FROM `tb_soll` WHERE status = 0 ORDER BY vac_id';
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
				$table .= " <th>Naam</th>
							<th>Adres</th>
							<th>Gebdatum</th>
							<th>Email</th>
							<th>Vac_id</th>
							<th>Goedkeuring</th>
							<th>Foutkeuring</th>";
				// now process every row in the $dbResult array and convert into table
				foreach ($p_aDbResult as $row){
					$i = 0;
					$table .= "<tr>";
						foreach ($row as $col) {
							if($i < 5) {
								$table .= "<td>" . $col . "</td>";
							}
							$i++; 	
						}
	                    // calculate url and trim all parameters [0..9]
	                    $url = rtrim($_SERVER['REQUEST_URI'],"/[0..9]");
						// create new link with parameter (== edit user link!)
						$table 	.= "<td><a href="
								. $url 							// current menu
								. "/update/" . $row["naamid"] 	// add ACTION and PARAM to the link
								. ">$image</a></td>";			// link to edit icon
					
					    $table 	.= "<td><a href="
								. $url 							// current menu
								. "/delete/" . $row["naamid"] 	// add ACTION and PARAM to the link
								. ">$image</a></td>";			// link to delete icon
					
					$table .= "</tr>";
					
				} // foreach
			$table .= "</table>";
			return $table;
		} //function

		
	    //cr[U]d action
		private function update() {
			// remove selected record based om uuid in PARAM
			$sql='UPDATE tb_soll SET status= 1 WHERE naamid="' . PARAM. '"';		
            $result = Database::getData($sql);
			$button = $this->addButton("/../../..", "Terug");	// add "/add" button. This is ACTION button
			// first show button, then table

			return $button ."<br>Deze sollicitant is goed gekeurd  " . PARAM;
		}
	 
	    //cru[D] action
	    private function delete() {
			// remove selected record based om uuid in PARAM
			$sql='DELETE FROM tb_soll WHERE naamid="' . PARAM. '"';		
            $result = Database::getData($sql);
			$button = $this->addButton("/../../..", "Terug");	// add "/add" button. This is ACTION button
			// first show button, then table

			return $button ."<br>Deze sollicitant is verwijderd " . PARAM;
		}
	

	}// class gebruikerPage
?>