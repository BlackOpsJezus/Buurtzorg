<?php


class MailPage {

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
	$sql='SELECT `naamid`, `naam`, `adres`, `gebdatum`, `mail`, `vac_id` , `punten` FROM `tb_soll` WHERE status = 2 ORDER BY vac_id';
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
		$table .= " <th>Naam ID</th>
					<th>Naam</th>
					<th>Adres</th>
					<th>Gebdatum</th>
					<th>Email</th>
					<th>Vac_id</th>
					<th>Punten</th>
					<th>Mail</th>";
		// now process every row in the $dbResult array and convert into table
		foreach ($p_aDbResult as $row){
			$i = 0;
			$table .= "<tr>";
				foreach ($row as $col) {
					if($i < 7) {
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
						
			

			
			$table .= "</tr>";
			
		} // foreach
	$table .= "</table>";
	return $table;
} //function


//cr[U]d action
private function update() {
 

    $mail             = new PHPMailer();

    $body             = file_get_contents('contents.html');
    $body             = eregi_replace("[\]",'',$body);
    
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->SMTPDebug  = 2;                      // enables SMTP debug information (for testing)
                                                // 1 = errors and messages
                                                // 2 = messages only
    $mail->SMTPAuth   = true;                   // enable SMTP authentication
    $mail->SMTPSecure = "tls";                  // sets the prefix to the servier
    $mail->Host       = "smtp.live.com";        // sets hotmil as the SMTP server
    $mail->Port       = 587;                    // set the SMTP port for the hotmail server
    $mail->Username   = "useyourownemail@hotmail.com";      // hotmail username
    $mail->Password   = "useyourownpassword";           // hotmail password
    $mail->SetFrom('yourname@yourdomain.com', 'First Last');
    $mail->AddReplyTo("name@yourdomain.com","First Last");
    $mail->Subject    = "PHPMailer Test Subject via smtp (hotmail), basic";
    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
    $mail->MsgHTML($body);
    
    $address = "anemail@domain.com";
    $mail->AddAddress($address, "John Doe");
    
    $mail->AddAttachment("images/phpmailer.gif");      // attachment
    $mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
    
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
      echo "Message sent!";
    }
    
    ?>






}

//cru[D] action



}// class gebruikerPage
?>
