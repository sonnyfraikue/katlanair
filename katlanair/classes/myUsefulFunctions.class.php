<?php
//This is a list of my useful functions ---->17/12/2007


class account_types {
	
		
	function generatePassword($length = 9, $strength = 0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
		
		$password = '';
		$alt = time () % 2;
		for($i = 0; $i < $length; $i ++) {
			if ($alt == 1) {
				$password .= $consonants [(rand () % strlen ( $consonants ))];
				$alt = 0;
			} else {
				$password .= $vowels [(rand () % strlen ( $vowels ))];
				$alt = 1;
			}
		}
		return $password;
	}
	
	function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
		$file = $path . $filename;
		$file_size = filesize ( $file );
		$handle = fopen ( $file, "r" );
		$content = fread ( $handle, $file_size );
		fclose ( $handle );
		$content = chunk_split ( base64_encode ( $content ) );
		$uid = md5 ( uniqid ( time () ) );
		$name = basename ( $file );
		$header = "From: " . $from_name . " <" . $from_mail . ">\r\n";
		$header .= "Reply-To: " . $replyto . "\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
		$header .= "This is a multi-part message in MIME format.\r\n";
		$header .= "--" . $uid . "\r\n";
		$header .= "Content-type:text/html; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$header .= $message . "\r\n\r\n";
		$header .= "--" . $uid . "\r\n";
		$header .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n"; // use diff. tyoes here
		$header .= "Content-Transfer-Encoding: base64\r\n";
		$header .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";
		$header .= $content . "\r\n\r\n";
		$header .= "--" . $uid . "--";
		mail ( $mailto, $subject, "", $header );
		return TRUE;
	
	}
			
	function getDaysInMonth($month, $year) {
		if ($month < 1 || $month > 12) {
			return 0;
		}
		
		$d = $this->daysInMonth [$month - 1];
		
		if ($month == 2) {
			// Check for leap year
			// Forget the 4000 rule, I doubt I'll be around then...
			

			if ($year % 4 == 0) {
				if ($year % 100 == 0) {
					if ($year % 400 == 0) {
						$d = 29;
					}
				} else {
					$d = 29;
				}
			}
		}
		
		return $d;
	}
	
	
	function MyUploader2($uploaddir, $postFileName) 

	{
		
		$uploadfile = $uploaddir . basename ( $_FILES [$postFileName] ['name'] );
		
		$uploadfile = preg_replace ( '/.JPG/', '.jpg', $uploadfile );
		
		if (move_uploaded_file ( $_FILES [$postFileName] ['tmp_name'], $uploadfile )) 

		{
			$uploaddir = preg_replace ( '/\/var\/www\/html\/clients/', '..', $uploaddir ); //STRIPPING VAR/WWW/HTML
			

			$_POST ["$postFileName"] = $uploaddir . basename ( $_FILES [$postFileName] ['name'] );
			
			//$thumb_depth = $this->thumb_depth;
			//$thumb_width = round((($w * $thumb_depth) / $h));
			

			//$thumb_name = "$dest/$newname" . "-thumb.jpg";
			//exec("/usr/bin/convert '$pic' -resize $thumb_width" . "x$thumb_depth $thumb_name", $out, $st);
			

			return 1;
		
		} 

		else {
			
			$error .= 'Upload error';
			
			return 0;
		}
	
	}
	

	
	
	
	function themeSelectorBuilder($clientId, $root, $site, $tradState) {
		
		$toggle = ($tradState == 1) ? "<a class='transparentLink' href='?site=$site&&ts=0'><img id='toggleImg' src='images/Tick-24x24.png' alt='On'/></a>" : "<a class='transparentLink' href='?site=$site&&ts=1'><img id='toggleImg' src='images/Error-24x24.png' alt='Off'/></a>";
		
		$root = '../' . $root . '/images/covers/thumbs/';
		
		$themeSelectorBreakdown .= '<table cellspacing="0" cellpadding="0" id="themeSelectorTable">';
		$themeSelectorBreakdown .= '<tr><td id="selectorHeading" colspan="2"><font size="-2">Include basic UK dates: ' . $toggle . '</font></td></tr>';
		$themeSelectorBreakdown .= '<tr><td id="selectorHeading" colspan="2">Pick a theme...</td></tr><tr>';
		
		if (is_dir ( $root )) {
			if ($myDir = opendir ( $root )) {
				
				$i = 1;
				
				while ( ($file = readdir ( $myDir )) !== false ) {
					
					if ($file == '.') {
						continue;
					}
					if ($file == '..') {
						continue;
					}
					if ($file == 'cover_default.png') {
						continue;
					}
					
					$theme = explode ( '.', $file );
					
					$themeSelectorBreakdown .= '<td><a href="?site=' . $site . '&&theme=' . $theme [0] . '"><img class="selectorImg" src="' . $root . $file . '" alt="theme ' . $theme [0] . '"/></a></td>';
					
					if ($i % 2) {
						;
					} else {
						$themeSelectorBreakdown .= '</tr><tr>';
					}
					
					$i ++;
				}
				
				return $themeSelectorBreakdown .= '</table>';
				closedir ( $myDir );
			}
		} else {
			
			echo $root . ' not directory!';
		}
	}
	
	function calculateMonth($page, $start_month, $start_year, $endMonth, $endYear, $frm, $pgFurl, $activeMonthUrl) {
		$tmp = $start_month + $page - 1;
		$month = date ( "n", mktime ( 0, 0, 0, $tmp, 1, $start_year ) );
		$year = date ( "Y", mktime ( 0, 0, 0, $tmp, 1, $start_year ) );
		
		$nextMonth = date ( "n", mktime ( 0, 0, 0, $tmp + 1, 1, $start_year ) );
		$nextYear = date ( "Y", mktime ( 0, 0, 0, $tmp + 1, 1, $start_year ) );
		$prevMonth = date ( "n", mktime ( 0, 0, 0, $tmp - 1, 1, $start_year ) );
		$prevYear = date ( "Y", mktime ( 0, 0, 0, $tmp - 1, 1, $start_year ) );
		
		if (($start_month == $month) && ($start_year == $year)) {
			
			$backButton = '&&bk=1&&pg=cover';
		
		} else {
			$backButton = "&&bk=1&&activeYear=$prevYear&&activeMonth=$prevMonth&&pg=workarea&&pgNum=$pgFurl";
		}
		
		

		if (($endMonth == $month) && ($endYear == $year)) {
			$forwardButton = "&&activeYear=$year&&fd=1&&pg=checkout";
		} else {
			$forwardButton = "&&fd=1&&activeYear=$nextYear&&activeMonth=$nextMonth&&pg=workarea&&pgNum=$pgFurl";
		
		}
		
		if (($_GET ['frm'] == 'checkout')) {
			
			$forwardButton = "&&activeYear=$year&&fd=1&&pg=checkout";
		}
		
		return array ($month, $year, $forwardButton, $backButton );
	
	}
	
	function callingCountryClass($iso3166Code) {
		
		require_once 'countries.shipping.rates.class.php';
		
		$myCountriesInstance = new dbcountry ( );
		
		$query = "SELECT `postage_band` FROM `shared_dev_resources`.`postage_rates` WHERE `country_code` = '$iso3166Code' LIMIT 1;";
		
		if ($myCountriesInstance->query ( $query ) && $myCountriesInstance->numRows ()) {
			
			$postageBandArray = $myCountriesInstance->getArray ();
			
			$postageBand = $postageBandArray ['postage_band'];
			
			$query = "SELECT `band_rate` FROM `shared_dev_resources`.`postage_band` WHERE `postage_band` = '$postageBand';";
			
			if ($myCountriesInstance->query ( $query ) && $myCountriesInstance->numRows ()) {
				
				$bandRateArray = $myCountriesInstance->getArray ();
				
				$bandRate = $bandRateArray ['band_rate'];
				
				return $bandRate;
			} else {
				return FALSE;
			}
		
		}
	}
	
	function countryOptionsList($countryArray, $selected, $dataSet, $elementChoice) {
		
		$selected = (isset ( $selected )) ? $selected : 'United Kingdom';
		
		$country .= "<select name='$elementChoice' id='country' size='1' class='myFormElements'>";
		
		switch ($dataSet) {
			case 1 :
				foreach ( $countryArray as $eachCountryArray ) {
					
					if ($eachCountryArray ['country'] == $selected) {
						$chosen = "Selected";
					} else {
						$chosen = NULL;
					}
					
					$country .= "<option $chosen value='{$eachCountryArray['country_id']}'>{$eachCountryArray['country']}</option>";
				
				}
				break;
			
			default :
				foreach ( $countryArray as $eachCountryArray ) {
					
					if ($eachCountryArray ['country'] == $selected) {
						$chosen = "Selected";
					} else {
						$chosen = NULL;
					}
					
					$country .= "<option $chosen value='{$eachCountryArray['country_id']}-{$eachCountryArray['region']}-{$eachCountryArray['country']}'>{$eachCountryArray['country']}</option>";
				
				}
				break;
		}
		
		$country .= "</select>";
		
		return $country;
	}
	
	function mainform($formtype, $sfpg, $textsearch, $textpost) {
		
		if ($sfpg == 1) {
			
			$elements = "<input type='text' value='$textsearch' name='textsearch' id='textsearch'/>";
			$Btn = "<td id='searchFormTabler1c2'><input type='submit' class='btn' name='sbmit' value='findit'/></td>";
			$switchval = 2;
		} else {
			$elements = "<textarea type='text' name='textpost' id='textpost' onkeypress='charcounter();' onchange='charcounter();'> $textpost</textarea>";
			$Btn = "<td id='searchFormTabler1c2'><input type='submit' name='sndit' class='btn' value='insertit'/></td>";
			$switchval = 1;
		}
		
		$breakdown .= "<div class='storyDiv'><table id='searchFormTable'>";
		
		$breakdown .= '<tr><td colspan="2" class="leftAlign"><span id="chardisplay"></span></td> <td> <a class="defaultLink" title="Show Search Form" href="index.php?sfpg=' . $switchval . '"><input id="change" type="button" name="change" value="change"/></a> </td></tr>';
		
		$breakdown .= '<tr> <td colspan="2" id="searchFormTabler1c1"> ' . $elements . ' </td>    ' . $Btn . '</tr>';
		
		$breakdown .= "</table></div>";
		
		return $breakdown;
	}
	
	function ShortenText($text, $chars) {
		
		//THIS METHOD CHECKS TO SEE THE LENGTH OF THE STRING AND IF LONGER THAN LENGTH SUBMITTED THENN REDUCE AND PROVIDE TO POPUP 
		$longText = $text;
		
		if (strlen ( $text ) >= $chars) {
			
			$text = $text . " ";
			
			$text = substr ( $text, 0, $chars );
			
			$text = substr ( $text, 0, strrpos ( $longText, ' ' ) );
			
			$text = "$text ...<a href=\"#\" title=\"$longText\">more</a>";
		
		}
		
		return $text;
	
	}
	
	function getcontent($sfpg) {
		$query = "SELECT s.* FROM `stories` s LEFT JOIN `users_hiddenstories` h ON s.`story_id` = h.`story_id` AND h.`user_id` = '{$_COOKIE['userid']}' WHERE h.`user_id` IS NULL";
		
		//mail ( 'sonny@feburman.co.uk', 'query', $query );
		

		$mydb = new db ( );
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$storyArray = $mydb->getFullArray ();
			
			$i = 1;
			$chars = 600;
			foreach ( $storyArray as $eachStory ) {
				
				$eachStory ['story_text'] = preg_replace ( '/<br \/>/', ' ', $eachStory ['story_text'] );
				
				$longText = $eachStory ['story_text'];
				$text = $eachStory ['story_text'];
				
				if (strlen ( $text ) >= $chars) {
					$text = substr ( $text, 0, $chars );
					
					$text = substr ( $text, 0, strrpos ( $longText, ' ' ) );
					
					$text = "$text...";
				}
				
				$storyBreakdown .= "<div class='storyDiv'>
													
							<table cellspacing='0' cellpadding='0' class='storyT'>
							<tr>
							<td class='storyTc1'>
							$i
							</td>
							</tr>
							
							<tr>
							<td class='storyTc2'>
							$text
							</td>
							</tr>
							
							<tr>
							<td class='storyTc3'>
							
							<table cellspacing='0' cellpadding='0' class='shrinkT'>
							<tr>
							<td id='shrinkTc1'>
							shrink
							</td>
							<td id='shrinkTc2'>
							more
							</td>							
							<td id='shrinkTc3'>
							<a class='defaultLink' href='#' name='{$eachStory['story_id']}' onclick='wait({$eachStory['story_id']})'>waiting</a>
							</td>
							 
							<td id='shrinkTc4'>
							<a class='defaultLink' href='#' name='{$eachStory['story_id']}' onclick='hide({$eachStory['story_id']})'>hide</a>
							</td>
							</tr>
							</table>
							
							</td>
							</tr>
							</table>
							
							</div>";
				
				$i ++;
			}
			
			return $storyBreakdown;
		}
	
	}
	
	function hidestory($storyid) {
		
		$mydb = new db ( );
		
		$query = "SELECT `user_id` FROM `users_hiddenstories` WHERE `user_id` = '{$_COOKIE['userid']}' AND `story_id` = '$storyid';";
		if ($mydb->query ( $query ) && ! $mydb->numRows ()) {
			
			$query = "INSERT INTO `users_hiddenstories` (`user_id`, `story_id`) VALUES('{$_COOKIE['userid']}', '$storyid')";
			
			if ($mydb->query ( $query ) && $mydb->getAffRows ()) {
			
			}
		
		}
		
		return $response;
	
	}
	
	function waitstory($storyid) {
		
		$mydb = new db ( );
		
		$query = "SELECT `user_id` FROM `users_waitingstories` WHERE `user_id` = '{$_COOKIE['userid']}' AND `story_id` = '$storyid';";
		if ($mydb->query ( $query ) && ! $mydb->numRows ()) {
			
			$query = "INSERT INTO `users_waitingstories` (`user_id`, `story_id`) VALUES('{$_COOKIE['userid']}', '$storyid')";
			
			if ($mydb->query ( $query ) && $mydb->getAffRows ()) {
			
			}
		
		}
		
		return $response;
	
	}
	
	function paycallcheck($jid) {
		$mydb = new db ( );
		$query = "SELECT `vat`, `shipping_price`, `sub_total`, `job_id`, `total`, `paper_size`, `orientation`, `quantity` FROM `jobs` WHERE `address1` IS NOT NULL AND `city` IS NOT NULL AND `postalcode` IS NOT NULL AND `fname` IS NOT NULL AND `sname` IS NOT NULL AND `job_id` = '{$_SESSION['jobArray']['job_id']}';";
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$jobArray = $mydb->getArray ();
			
			$pay = <<<heredoc
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="adForm" id="adForm">
<input type="hidden" name="variableName" value="allowedValue">		
<input type="hidden" name="cmd" value="_xclick">	
<input type="hidden" name="quantity" value="{$jobArray ['quantity']}">
<input type="hidden" name="return" value="http://www.simpleposters.com/thankyou.php?jid=$jid">
<input type="hidden" name="currency_code" value="GBP">
<input type="hidden" name="business" value="sonny@feburman.co.uk">
<input type="hidden" name="amount" value="{$jobArray['sub_total']}">
<input type="hidden" name="shipping" value="{$jobArray['shipping_price']}">

<input type="hidden" name="item_name" value="Poster ({$jobArray ['paper_size']} {$jobArray ['orientation']} $jid)">
<input type="hidden" name="item_number" value="$jid">
<input type="hidden" name="lc" value="GB">
<input type="hidden" name="rm" value="2">
<input type="hidden" name="invoice" value="$jid">
<div id="buttonpay" onclick="document.adForm.submit();" class="fileBtn">Order</div> <div id="buttonrestart" class="fileBtn" onclick="logout();">Restart</div>
</form>
heredoc;
			return $pay;
		}
	
	}
	
	function costcall($jid) {
		$mydb = new db ( );
		$query = "SELECT `paper_size`, `retouch` FROM `jobs` WHERE `job_id` = '$jid' LIMIT 1;";
		//mail('sonnyfraikue@gmail.com','query',$query);
		

		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$productidarray = $mydb->getArray ();
		
		}
		if (! is_null ( $productidarray ['retouch'] )) {
			$query = "SELECT `unit_cost` FROM `cost_codes` WHERE `product_id` = '{$productidarray['retouch']}';";
			
			if ($mydb->query ( $query ) && $mydb->numRows ()) {
				$retouchArray = $mydb->getArray ();
				$retouchcost = number_format ( $retouchArray ['unit_cost'], 2, '.', '' );
				$inclretouch = "(retouching included)";
			}
		
		}
		
		$query = "SELECT `unit_cost` FROM `cost_codes` WHERE `product_id` = '{$productidarray['paper_size']}';";
		
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$costArray = $mydb->getArray ();
		}
		
		$unitcost = number_format ( $costArray ['unit_cost'], 2, '.', '' );
		$quantity = $_SESSION ['jobArray'] ['quantity'];
		$shipping = number_format ( 6.50, 2, '.', '' );
		
		$subtotal = number_format ( (($unitcost * $quantity) + $retouchcost), 2, '.', '' );
		$vat = number_format ( 0.15 * ($subtotal + $shipping), 2, '.', '' );
		$total = number_format ( $vat + $shipping + $subtotal, 2, '.', '' );
		
		$totalsTable .= "<table cellspacing='0' cellpadding='0' id='totalsT'>";
		$totalsTable .= "<tr><td>Unit price</td><td>&pound; $unitcost $inclretouch</td></tr>";
		$totalsTable .= "<tr><td>P&P</td><td>&pound; $shipping</td></tr>";
		$totalsTable .= "<tr><td>Sub total</td><td>&pound; $subtotal</td></tr>";
		$totalsTable .= "<tr><td>Vat</td><td>&pound; $vat</td></tr>";
		$totalsTable .= "<tr><td>Total</td><td>&pound; $total</td></tr>";
		$totalsTable .= "</table>";
		
		$query = "UPDATE `jobs` SET `total` = '$total', `sub_total` = '$subtotal', `shipping_price` = '$shipping', `unit_price` = '$unitcost' WHERE `job_id` = '$jid';";
		
		if ($mydb->query ( $query ) && $mydb->getAffRows ()) {
		
		}
		
		return $totalsTable;
	}
	
	function papersizes($pid, $default,$caller) {
		$mydb = new db ( );
		if (!is_int($pid)) {
			$query	=	"SELECT `product_id` FROM `products` WHERE `product_id` = '$pid';";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$pidArray	=	$mydb->getArray();
				$pid	=	$pidArray['product_id'];
			}
			
		}
		$query = "SELECT * FROM `psizes-products` pp LEFT JOIN `psizes` ps ON pp.`ps_id` = ps.`ps_id` WHERE pp.`product_id` = '$pid';";
		
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$ppArray = $mydb->getFullArray ();
			$sizesAreabreakdown	=	"<table cellspacing='0' cellpadding='0' style='width:100%;'>";
			foreach ( $ppArray as $eachpp ) {
				$selected = ($default == $eachpp['ps_id']) ? 'checked' : NULL; 
				$eachpp['name']	=	trim($eachpp['name']); 
				$sizesAreabreakdown .= "<tr>";
				$sizesAreabreakdown	.= "<td style='color:{$eachpp['color']};text-transform:uppercase;font-size:13px;'>{$eachpp['name']}";
				$sizesAreabreakdown	.= "<div class='smTxt'>({$eachpp['width']} x {$eachpp['height']})</div>";
				$sizesAreabreakdown	.= "</td>";
				$sizesAreabreakdown	.= "<td>";
				$sizesAreabreakdown	.= "<input id=\"{$eachpp['name']}\" class=\"szEl\" $selected type=\"radio\" name=\"papersizes\" value=\"{$eachpp['ps_id']}\"/>";
				$sizesAreabreakdown	.= "</td></tr>";
				
				$sizesAreabreakdownjs	.=($sizesAreabreakdownjs)?"&&{$eachpp['name']},{$eachpp['ps_id']},{$eachpp['width']},{$eachpp['height']},{$eachpp['color']}":"{$eachpp['name']},{$eachpp['ps_id']},{$eachpp['width']},{$eachpp['height']},{$eachpp['color']}";
			}
			$sizesAreabreakdown	.=	"</table>";
		
		}
		if($caller == 'php')
		{
		return $sizesAreabreakdown;
		}
		else
		{
			return $sizesAreabreakdownjs;
		}
	}
	
	function productsarray() {
		$mydb	=	new  db();
		$query	=	"SELECT * FROM `products` WHERE `activation_time` IS NOT NULL;";
		if ($mydb->query($query)&&$mydb->numRows()) {
			return $mydb->getFullArray();
		}
		
	}
	
	function productid($jobid) {
		$mydb	=	new  db();
		$query	=	"SELECT `product_id` FROM `jobs` WHERE `job_id` = '$jobid';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$productid	=	$mydb->getArray();
			return $productid['product_id'];
		}
		
	}
	
	function productcarousel($jobid,$array) {
		
			$productarray	=	$this->productsarray();
		
	foreach ($productarray as $eachproduct) {
		
					
			$product_image	=	($eachproduct['product_id'] == $this->productid($jobid))?strtolower($eachproduct['description']):strtolower($eachproduct['description']).'_opacity';
			$product_image	=	preg_replace('/ /','',$product_image);
			
			$prodArray	.=	($prodArray)?','.$product_image:$product_image;
			$productdropdown	.=	"<div class=\"pBlok\" style=\"background-image:url(images/$product_image.png);position:relative;float:left;\" id=\"$product_image\"></div>";
		}
		if ($array == 0) {
			return '<div id="cah">'.$productdropdown.'</div>';
		}
		else 
		{
			return $prodArray;
		}
		
	}
	
function pcarousel($pid) {
		
			$productarray	=	$this->productsarray();
		
	foreach ($productarray as $eachproduct) {
		
					
			$product_image	=	($eachproduct['product_id'] == $pid)?strtolower($eachproduct['description']):strtolower($eachproduct['description']).'_opacity';
			$product_image	=	preg_replace('/ /','',$product_image);
			
			$prodArray	.=	($prodArray)?','.$product_image:$product_image;
			$productdropdown	.=	"<div class=\"pBlokt\" style=\"background-image:url({$_SESSION['serverArray']['host_name']}/images/$product_image.png);position:relative;float:left;\" id=\"{$eachproduct['product_id']}pb\"></div>";
		}
		if ($array == 0) {
			return '<div id="cah">'.$productdropdown.'</div>';
		}
		else 
		{
			return $prodArray;
		}
		
	}
	
	function productname($jobid) {
	$mydb = new db ( );
		$query	=	"SELECT `description_sh` FROM `products` WHERE `product_id` = '{$this->productid($jobid)}';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$pnameshArray	=	$mydb->getArray();	
			return $pnameshArray['description_sh'];
		}
	}
	
	function productnamenoid($pid) {
	$mydb = new db ( );
		$query	=	"SELECT `description_sh` FROM `products` WHERE `product_id` = '$pid';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$pnameshArray	=	$mydb->getArray();	
			return $pnameshArray['description_sh'];
		}
	}
	
	function sizeid($jobid) {
		$mydb	=	new  db();
		$query	=	"SELECT `paper_size` FROM `jobs` WHERE `job_id` = '$jobid' LIMIT 1;";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$papersizeArray	=	$mydb->getArray();
		}
		
		return $papersizeArray['paper_size'];
	}
	
		
	
	function stepsummary($jid,$step) {
		$mydb	=	new  db();
		
		switch ($step) {
			case 1:
			$query	=	"SELECT p.`description`, ps.`width`, ps.`height`, ps.`name` FROM `jobs` AS j LEFT JOIN `products` AS p ON j.`product_id` = p.`product_id` LEFT JOIN `psizes` AS ps ON j.`paper_size` = ps.`ps_id` WHERE j.`job_id` = '$jid'";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$productSizeArray					=	$mydb->getArray();
				$prodimage							=	strtolower(preg_replace('/ /', '',$productSizeArray['description'])); 
				$stepBreakdown						.=	"<div style=\"position:absolute;top:5px;left:5px;width:46px;height:65px;background-image:url(images/$prodimage.png);\" id=\"{$productSizeArray['description']}\"></div>";
				$stepBreakdown						.=	"<div style=\"position:absolute;color:#666;font-size:28px;left:70px;top:5px;\">{$productSizeArray['description']}</div>";
				$stepBreakdown						.=	"<div style=\"position:absolute;color:#666;font-size:18px;left:70px;top:38px;\">{$productSizeArray['name']} ({$productSizeArray['width']} x {$productSizeArray['height']})</div>";
				$stepBreakdown						.=	"<div style=\"position:absolute;color:#666;font-size:10px;left:70px;top:60px;\">Paper/canvas dimension are in inches</div>";
			}
			
			break;
					
			case 2;
			
			$query			=	"SELECT `laminate` FROM `jobs` WHERE `job_id` = '$jid';";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$laminateArray	=	$mydb->getArray();
			}
			
			$imgdata		=	 $this->imgdata($jid);
			$imgdataArray	=	explode(',',$imgdata);
			if ($imgdataArray[0]) {
				$thumb	=	"<img height=\"40px\" src=\"{$imgdataArray[0]}\"/>";
			}
			
			$img			 =	'<img src="images/unknown.gif" />';
			$stepBreakdown	.=	'<div style="position:absolute;left:5px;top:5px;">'.$img.'</div>';
			$stepBreakdown	.=	($thumb)?'<div style="position:absolute;left:70px;top:33px;">'.$thumb.'</div>':null;
			$stepBreakdown	.=	'<div style="position:absolute;left:70px;top:0px;color:#666;font-size:28px;">'.$laminateArray['laminate'].'</div>';
			break;
			
			case 3;
			$query	=	"SELECT `address1` FROM `jobs` WHERE `job_id` = '$jid' AND `address1` IS NOT NULL LIMIT 1;";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$address1Array	=	$mydb->getArray();
			}
			
			$query	=	"SELECT `address2` FROM `jobs` WHERE `job_id` = '$jid' AND `address2` IS NOT NULL AND `address2` != '' LIMIT 1;";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$address2Array	=	$mydb->getArray();
			$address1bit	=	($address1Array['address1'])?$address1Array['address1'].', ':null;
			$stepBreakdown	.=	"<div style=\"width:150px;position:absolute;bottom:50px;left:70px;text-align:left;\">$address1bit{$address2Array['address2']}</div>";
				
			}
		else 
			{
				$stepBreakdown	.=	"<div style=\"width:150px;position:absolute;bottom:50px;left:70px;text-align:left;\">Street</div>";
			}
			
		$query	=	"SELECT `address3` FROM `jobs` WHERE `job_id` = '$jid' AND `address3` IS NOT NULL AND `address3` != '' LIMIT 1;";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$address3Array	=	$mydb->getArray();
				$stepBreakdown	.=	"<div style=\"width:150px;position:absolute;bottom:30px;left:70px;text-align:left;\">{$address3Array['address3']}</div>";
				
			}
		else 
			{
				$stepBreakdown	.=	"<div style=\"width:150px;position:absolute;bottom:30px;left:70px;text-align:left;\">city</div>";
			}
			
			$query	=	"SELECT `postalcode` FROM `jobs` WHERE `job_id` = '$jid' AND `postalcode` IS NOT NULL AND `postalcode` != '' LIMIT 1;";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$postalcodeArray	=	$mydb->getArray();
				$stepBreakdown	.=	"<div style=\"width:150px;position:absolute;bottom:10px;left:70px;text-align:left;\">{$postalcodeArray['postalcode']}</div>";
			}
		else 
			{
				$stepBreakdown	.=	"<div style=\"width:150px;position:absolute;bottom:10px;left:70px;text-align:left;\">postcode/zip</div>";
			}
		
			
			$query	=	"SELECT `email` FROM `jobs` WHERE `job_id` = '$jid' AND `email` IS NOT NULL AND `email` != '' LIMIT 1;";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$emailArray	=	$mydb->getArray();
				$stepBreakdown	.=	"<div style=\"width:150px;position:absolute;bottom:70px;left:70px;text-align:left;\">{$emailArray['email']}</div>";
			}
			else 
			{
				$stepBreakdown	.=	"<div style=\"width:150px;position:absolute;bottom:70px;left:70px;text-align:left;\">email</div>";
			}
			
			$stepBreakdown	.=	"<div style=\"height:65px;width:46px;position:absolute;top:5px;left:5px;background-image:url(images/van.gif);\"></div>";
			break;
		}
		return $stepBreakdown;	
	}
	
	
	
	function createorderarea($jobid) {
		$mydb = new db ( );
		//$myusefulInstance = new account_types ( );
		
		$query = "SELECT * FROM `jobs` WHERE `job_id` = '$jobid' LIMIT 1;";
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$_SESSION ['jobArray'] = $mydb->getArray ();
		
		}
		
		$query = "SELECT m.*, spc.`content` FROM `metadata` AS m, `site_page_content` AS spc WHERE m.`page_id` = '1' AND spc.`page_id` = m.`page_id`  LIMIT 1;";
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$metaArray = $mydb->getArray ();
		}
		
		//creating countries select element
		$query = "SELECT * FROM `countries`;";
		
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$countriesArray = $mydb->getFullArray ();
			
			foreach ( $countriesArray as $eachcountry ) {
				
				$selected = ($_SESSION ['jobArray'] ['country_id'] == $eachcountry ['country_id']) ? "selected='1'" : NULL;
				
				$countryBreakdown .= "<option $selected value='{$eachcountry['country_id']}'>{$this->ShortenText($eachcountry['country'],22)}</option>";
			}
		}
			
		//returning short name  for product
		
		
		$glossselected = ($_SESSION ['jobArray'] ['laminate'] == 'canvas') ? 'checked' : NULL;
		$semiglossselected = ($_SESSION ['jobArray'] ['laminate'] == 'semimatte') ? 'checked' : NULL;
		$noglossselected = ($_SESSION ['jobArray'] ['laminate'] == 'canvas') ? 'checked' : NULL;
		$portraitselected = ($_SESSION ['jobArray'] ['orientation'] == 'portrait') ? 'checked' : NULL;
		$landscapeselected = ($_SESSION ['jobArray'] ['orientation'] == 'landscape') ? 'checked' : NULL;
		
		$singleimposelected = ($_SESSION ['jobArray'] ['imposition'] == 'single') ? 'checked' : NULL;
		$doubleimposelected	= ($_SESSION ['jobArray'] ['imposition'] == 'double') ? 'checked' : NULL;
		
		$retouchnone = (is_null ( $_SESSION ['jobArray'] ['retouch'] )) ? 'checked' : NULL;
		$retouchactive = (! is_null ( $_SESSION ['jobArray'] ['retouch'] )) ? 'checked' : NULL;
		
		$retouchnone = (is_null ( $_SESSION ['jobArray'] ['retouch'] )) ? "checked" : NULL;
		$retouchactive = ($_SESSION ['jobArray'] ['retouch']) ? "checked" : NULL;
		$notes = (is_null ( $_SESSION ['jobArray'] ['notes'] )) ? 'Add retouch notes' : $_SESSION ['jobArray'] ['notes'];
		$glosstable .= "<table id='glT' cellspacing='0' cellpadding='0'><tr><td class='halfwdith'><input $semiglossselected type='radio' name='coating' class='coating' id='1coating' value='semimatte'/></td><td class='halfwdith'><input $noglossselected type='radio' class='coating' name='coating' id='2coating' value='canvas'/></td></tr>";
		$glosstable .= "<tr><td class='halfwdith'>semi-matte</td><td class='halfwdith'>canvas</td></tr>";
		$glosstable .= "</table>";
		 
		$sidestable .= "<table id='glT' cellspacing='0' cellpadding='0'><tr><td class='halfwdith'><input $singleimposelected type='radio' name='imposition' value='single'/></td><td class='halfwdith'><input $doubleimposelected type='radio' name='imposition' value='double'/></td></tr>";
		$sidestable .= "<tr><td class='halfwdith'>single</td><td class='halfwdith'>double</td></tr>";
		$sidestable .= "</table>";
	
		$hmtext .= "<table id='sizesT'>";
		$hmtext .= "<tr><td><div id=\"hb\">{$metaArray['content']}</div></td></tr>";
		$hmtext .= "</table>";
		
		$clpsdiv1 .= '<div id="clpsdiv1">';
		$clpsdiv1 .= '<form name="orderForm3" id="orderForm3" action="post.handler.php" method="POST" onsubmit="return sendForm(\'orderForm3\');">';
		$clpsdiv1 .= "<table id='sizesT' cellpadding='0' cellspacing='0'>";
		$clpsdiv1 .= "<tr><td class='grH' colspan='2'><h3>products.</h3></td></tr>";
		$clpsdiv1 .= "<tr><td colspan='2' id='crH'>{$this->productcarousel($jobid,0)}</td></tr>";
		$clpsdiv1 .= "<tr><td class='grH' id='crt' colspan='2'><h3>{$this->productname($jobid)} Sizes</h3></td></tr>";
		$clpsdiv1 .= "<tr><td><span style='color:#aaa;font-size:10px;float:left;margin-bottom:2px;'>Measurements in inches <br/>Paper thickness 250gsm</span></td> <td><div id='pr' style='visibility:hidden;'><img src='images/loading.gif' alt='loading...'/></div></td></tr>";
		$clpsdiv1 .= "<tr><td colspan='2' id='sHol'>{$this->papersizes ( $this->productid($jobid),$this->sizeid($jobid),'php')}</td></tr>";
		$clpsdiv1 .= "<tr><td colspan='2' style='height:10px;'></td></tr>";
		$clpsdiv1 .= "</table>";
		$clpsdiv1 .= "</form>";
		$clpsdiv1 .= '</div>';
		
		$clpsdiv2 .= '<div id="clpsdiv2">';
		
		$clpsdiv2 .= "<table id='sizesT' cellpadding='0' cellspacing='0'>";
		
		$clpsdiv2 .= "<tr><td class='grH' colspan='2'><h3>Preferences</h3></td></tr>";
		$clpsdiv2 .= ($_GET['product']=='flyers')?"<tr><td>Imposition</td><td>$sidestable</td></tr>":null;
		$clpsdiv2 .= "<tr><td></td><td>$glosstable</td></tr>";
		
		$clpsdiv2 .= "<tr><td colspan='2' class='grH'><h3>Choose your picture.</h3></td></tr>";
		$clpsdiv2 .= "<tr><td><div id='button' class='fileBtn'>Upload</div></td> <td><a target=\"_top\" title=\"Image-Library\" href=\"image-library\"><div id='libtn'>Library</div></a></td> </tr>";
		$clpsdiv2 .= "<tr><td colspan='2'>{$this->imgsumary($_SESSION['jobArray']['job_id'])}</td></tr>";
		$clpsdiv2 .= "</table>";
		
		$clpsdiv2 .= '</div>';
		$clpsdiv3 .= '<div id="clpsdiv3">';
		$clpsdiv3 .= '<form name="orderForm1" id="orderForm1" action="post.handler.php" method="POST" onsubmit="return sendForm(\'orderForm1\');">';
		$clpsdiv3 .= "<table id='sizesT' cellpadding='0' cellspacing='0'>";
		$clpsdiv3 .= "<tr><td colspan='2' class='grH'><h3>Shipping.</h3></td></tr>";
		$clpsdiv3 .= "<tr><td>Quantity</td><td><input id='qnty' type='text' name='quantity' value='{$_SESSION['jobArray']['quantity']}'/></td></tr>";
		$clpsdiv3 .= "<tr><td>First name</td><td><input class='textline' type='text' name='fname' value='{$_SESSION['jobArray']['fname']}'/></td></tr>";
		$clpsdiv3 .= "<tr><td>Last name</td><td><input class='textline' type='text' name='sname' value='{$_SESSION['jobArray']['sname']}'/></td></tr>";
		$clpsdiv3 .= "<tr><td>Hse/Flat Number</td><td><input class='textline' type='text' name='address1' value='{$_SESSION['jobArray']['address1']}'/></td></tr>";
		$clpsdiv3 .= "<tr><td>Street</td><td><input class='textline' type='text' name='address2' value='{$_SESSION['jobArray']['address2']}'/></td></tr>";
		$clpsdiv3 .= "<tr><td>Town/City</td><td><input class='textline' type='text' name='address3' value='{$_SESSION['jobArray']['address3']}'/></td></tr>";
		$clpsdiv3 .= "<tr><td>County/State</td><td><input class='textline' type='text' name='county' value='{$_SESSION['jobArray']['county']}'/></td></tr>";
		$clpsdiv3 .= "<tr><td>Country</td><td><select id='country' name='country'>$countryBreakdown</select></td></tr>";
		$clpsdiv3 .= "<tr><td>Postcode/Zip</td><td><input class='textline' type='text' name='postalcode' value='{$_SESSION['jobArray']['postalcode']}'/></td></tr>";
		$clpsdiv3 .= "<tr><td>Email</td><td><input class='textline' type='text' name='email' value='{$_SESSION['jobArray']['email']}'/></td></tr>";
		$clpsdiv3 .= "<tr><td>Phone</td><td><input class='textline' type='text' name='phone' value='{$_SESSION['jobArray']['phone']}'/></td></tr>";
		$clpsdiv3 .= "</table>";
		$clpsdiv3 .= "</form>";
		$clpsdiv3 .= '</div>';
		
		$orderArea .= "<table cellpadding='0' cellspacing='0' id='oAT'  >";
		
		$orderArea .= "<tr><td><div id='dv1' class='divCaller'><h2>Step 1</h2><div class=\"inChev\" id=\"chv1\"></div></div><div id='dv1_r'>{$this->stepsummary($jobid,1)}</div></td></tr>";
		$orderArea .= "<tr><td id='divHolder1'>$clpsdiv1</td></tr>";
		$orderArea .= "<tr><td style='height:3px;'></td></tr>";
		
		$orderArea .= "<tr><td><div id='dv2' class='divCaller'><h2>Step 2</h2><div class=\"inChev\" id=\"chv2\"></div></div><div id='dv2_r'>{$this->stepsummary($jobid,2)}</div></td></tr>";
		$orderArea .= "<tr><td id='divHolder2'>$clpsdiv2</td></tr>";
		$orderArea .= "<tr><td style='height:3px;'></td></tr>";
		
		$orderArea .= "<tr><td><div id='dv3' class='divCaller'><h2>Step 3</h2><div class=\"inChev\" id=\"chv3\"></div></div><div id='dv3_r'>{$this->stepsummary($jobid,3)}</div></td></tr>";
		$orderArea .= "<tr><td id='divHolder3'>$clpsdiv3</td></tr>";
		$orderArea .= "<tr><td style='height:3px;'></td></tr>";
		$orderArea .= '</form>';
		
		$orderArea .= "<tr><td id='s3'>{$this->costcall($_SESSION['jobArray']['job_id'])}</td></tr>";
		$orderArea .= "<tr><td id='s4'><div id='flbtns' class='fl'>{$this->paycallcheck($_SESSION['jobArray']['job_id'])}</div></td></tr>";
		$orderArea .= "</table>";
		
		return $orderArea;
	}
	
	function imgdata($jid) {
		$mydb	=	new  db();
		
	//handling library requests
		if (preg_match('/customize/',$_SERVER['HTTP_REFERER'],$matcharray)) {
			$explodedescription	=	explode('customize/',$_SERVER['HTTP_REFERER']);
			$explodedescription	=	explode('/',$explodedescription[1]);
			$explodedescription[2]	=	preg_replace('/-/',' ',$explodedescription[2]);
						
			$query	=	"SELECT u.`file_name`, u.`orig_file`, u.`width`, u.`height`, u.`upload_id`, u.`description`, us.`user_id` FROM `uploads` u LEFT JOIN `users` us ON u.`user_id` = us.`user_id` WHERE u.`upload_id` = '{$explodedescription[0]}' LIMIT 1;";
		
			if ($mydb->query($query)&&$mydb->numRows()) {
			$dataArray	=	$mydb->getArray();
			$thmbfile	=	pathinfo($dataArray['file_name']);
			$filename	=	"{$_SESSION['serverArray']['host_name']}/assets/asset{$dataArray['user_id']}/{$thmbfile['filename']}.thumb.{$thmbfile['extension']}";
			return $filename.','.$dataArray['orig_file'].','.$dataArray['width'].','.$dataArray['height'].','.$dataArray['upload_id'].','.$dataArray['description'];
		}
		}
		
		//dealing with jquery imgdata requests
		if (preg_match('/userid=/',$jid,$matcharray)) {
			
		$user_idArray	=	explode('=',$jid);
		$query	=	"SELECT `file_name`, `orig_file`, `width`, `height`, `upload_id`, `description` FROM `uploads` WHERE `user_id` = '{$user_idArray[1]}' ORDER BY `upload_id` DESC LIMIT 1;";
		}
		else 
		{
			$query	=	"SELECT `file_name`, `orig_file`, `width`, `height`, `upload_id`, `description` FROM `uploads` WHERE `job_id` = '$jid' ORDER BY `upload_id` DESC LIMIT 1;";
		}
				
		if ($mydb->query($query)&&$mydb->numRows()) {
			$dataArray	=	$mydb->getArray();
			
			$thmbfile	=	pathinfo($dataArray['file_name']);
			$filename	=	($user_idArray)?"{$_SESSION['serverArray']['host_name']}/assets/asset{$user_idArray[1]}/{$thmbfile['filename']}.thumb.{$thmbfile['extension']}":"{$_SESSION['serverArray']['host_name']}/jobs/job$jid/{$thmbfile['filename']}.thumb.{$thmbfile['extension']}";
			
			return $filename.','.$dataArray['orig_file'].','.$dataArray['width'].','.$dataArray['height'].','.$dataArray['upload_id'].','.$dataArray['description'];
		}
	}
	
	function udsc($uid) {
		$mydb	=	new  db();
		$query	=	"SELECT `description` FROM `uploads` WHERE `upload_id` = '$uid';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$desc	=	$mydb->getArray();
			return $desc['description'];
		}
		
	}
	
	
	
	function imgsumary($jid) {
		$dataArray	=	explode(',',$this->imgdata($jid));
		
		
		if ($dataArray[0]) {
			//$thmbfile	=	pathinfo($dataArray[0]);
			$filename	=	$dataArray[0];
		}
		else 
		{
			$filename	=	null;
		}
		
		if ($dataArray[1]) {
			$originalfile	=	'<p>'.$this->ShortenText($dataArray[1],18).'</p>';
		}
		
		
		$width		=	($dataArray[2])?'<p>'.$dataArray[2]:null;
		$height		=	($dataArray[3])?$dataArray[3]:null;
		
		$applytocanvas	=	($filename)?"<p><a id=\"atc\" href=\"#\">Click to apply</a></p>":null;
		$thumbnail	=	($filename)?"<img id=\"ulimg\" src=\"$filename\" alt=\"$filename\"/>":null;
		$ex			=	($width&&$height)?'X':null;
		$pix		=	($width&&$height)?'pixels</p>':null;
		
		$imgsmry	.=	"<table id=\"imgT\" cellspacing=\"0\" cellpadding\"0\">";
		$imgsmry	.=	"<tr><td id=\"imgTr1c1\" class=\"imgTr1\">$thumbnail<input type=\"hidden\" id=\"upload_id\" value=\"{$dataArray[4]}\"/></td><td id=\"imgTr1c2\" class=\"imgTr1\">$originalfile $width $ex $height $pix$applytocanvas</td></tr>";
		
		$imgsmry	.=	"</table>";
		
		return $imgsmry;
	}
	
	function job_images_apply($jid,$upload_id,$pg_num) {
		$mydb	=	new  db();
		$query	=	"SELECT `ji_id` FROM `job_images` WHERE `job_id` = '$jid' AND `upload_id` = '$upload_id' AND `pg_num` = '$pg_num';";
		if ($mydb->query($query)&&!$mydb->numRows()) {
			$query	=	"INSERT `job_images` (`job_id`, `upload_id`, `pg_num`) VALUES('$jid', '$upload_id', '$pg_num')";
			if ($mydb->query($query)&&$mydb->getAffRows()) {
				return true;
			}
			
		}
		
	}
	
	
	
	function removeasset($userid) {
		$mydb = new db ( );
		if ($useridarray	=	explode('cls',$userid)) {
			$userid	=	$useridarray[1];
		}
		
		$query = "DELETE FROM `handsometoad`.`uploads` WHERE `upload_id` = '$userid'";
						
		if ($mydb->query($query)&&$mydb->getAffRows()) {
		return TRUE;
		}
		
	}
	
	function canvaswidth() {
		$mydb	=	new  db();
		$query	=	"SELECT `canvas_width` FROM `clients` LIMIT 1;";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$carray	=	$mydb->getArray();
			return $carray['canvas_width'];
		}
		
	}
	
	
	function displayArea($jid) {
		$mydb = new db ( );
		$time = time ();
		$canvaswidth	=	$this->canvaswidth();
		
		$query = "SELECT `paper_size`, `assetname` FROM `jobs` WHERE `job_id` = '{$_SESSION['jobArray']['job_id']}';";
		
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$papersizeArray = $mydb->getArray ();
			$imgfile = $_SESSION['serverArray']['host_name']."jobs/job$jid/{$papersizeArray ['assetname']}?t=$time";
			
			$fileinfo = pathinfo ( "jobs/job$jid/" . $papersizeArray ['assetname'] );
			$ext = strtolower ( $fileinfo ['extension'] );
			
			if (file_exists ( "jobs/job$jid/$jid" . ".workfile.$ext" )) {
				$imgfile = $_SESSION['serverArray']['host_name']."/jobs/job$jid/$jid" . ".workfile.$ext?t=$time";
			}
			$imgfile = trim ( $imgfile );
			//returning the max size for posters
			
			//getting zoom factor
		$query	=	"SELECT `width`, `height`, `wf_width`, `wf_height` FROM `uploads` WHERE `job_id` = '$jid' ORDER BY `upload_id` DESC LIMIT 1;";
			
			if ($mydb->query($query)&&$mydb->numRows()) {
				$uploadArray	=	$mydb->getArray();
				
					//dealing with landscape here
					$x	=	round($uploadArray['wf_width']);
					$y	=	round($uploadArray['wf_height']);
					
					$top	= round($uploadArray['wf_height']/5);
			
			
				$cropId	=	($_GET['docrop']=='true')?"id=\"cropbox\"":null;
			
				if ($uploadArray['width'] > $uploadArray['height']) {
					$prop	=	"width=\"400px\"";
				}
				else 
				{
					$prop	=	"height=\"400px\"";
				}
				
				$x	 =	$x.'px';
				$y	 =	$y.'px';
				$top =  $top.'px';
				
								 
				$img = ($papersizeArray ['assetname']) ? $gotocanvastart."<img $prop id=\"cropbox\" id=\"jcrop_target\" src=\"$imgfile\" id=\"workfile\" alt=\"workfile\"/>" : NULL;
				
			
			$query	=	"SELECT `x`, `y` FROM `retouch_comments` WHERE `job_id` = '$jid'";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$commentsArray	=	$mydb->getFullArray();
				
				foreach ($commentsArray as $eachcommentsArray) {
					$xx	=	$eachcommentsArray['x'].'px';
					$yy	=	$eachcommentsArray['y'].'px';
					
					$canvasbreakdown	.=	"<div class=\"postit\" style=\"position:absolute;left:$xx;top:$yy;\"></div>";
				}
				
			}
			}
			
			if ($_GET['state'] == 'canvas') {
			$divbreakdown = "{$this->toolbar($jid)}<div id=\"cropboxholder\" style='width:$x;height:$y;top:$top;margin:auto;text-align:center;position:relative;'>$img$canvasbreakdown</div>";
			}
			if (!$_GET['state']) 
			{
			$divbreakdown =	"<div id=\"tas\"></div>"; 
			}
			
			
			
		return $divbreakdown;
				
		}
	
	}
	
	function fullproducts($jid) {
		$mydb	 =	new db();	
		
		$query	=	"SELECT `product_id`, `paper_size` FROM `jobs` WHERE `job_id` = '$jid';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$pinfoArray	=	$mydb->getArray();
			
		}
		$query	=	"SELECT tp.`id`, tp.`t_id`, tp.`page`, t.`name` FROM `template_product` tp LEFT JOIN `templates` t ON tp.`t_id` = t.`t_id` WHERE tp.`product_id` = '{$pinfoArray['product_id']}'";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$templateArray	=	$mydb->getFullArray();
			foreach ($templateArray as $eachtemplate) {
				$image	=	preg_replace('/ /','',$eachtemplate['name']);
				$image	=	'templates/'.$image.'_thmb.png';
				$templatebreakdown	.=	($templatebreakdown)?"&&{$eachtemplate['id']},{$eachtemplate['t_id']},{$eachtemplate['page']},{$eachtemplate['name']},$image":"{$eachtemplate['id']},{$eachtemplate['t_id']},{$eachtemplate['page']},{$eachtemplate['name']},$image";
			}
			
		}
		
		return $templatebreakdown;
	}
	
function savecoords($id,$x,$y) {
		$mydb	=	new  db();
		$x		=	preg_replace('/px/','',$x);
		$y		=	preg_replace('/px/','',$y);		
		$id		=	preg_replace('/com/','',$id);
		
		$query	=	"UPDATE `retouch_comments` SET `x` = '$x', `y` = '$y' WHERE `comment_id`	=	'$id'";
		
		if ($mydb->query($query)&&$mydb->getAffRows()) {
		return true;
		}
		
	}
	
	function toolbar($jid) {
	$mydb	=	new db();
		//showing btn states
		
		$altools	=	($_GET['doalign'] == 'true')?'toolsactive':'tools';
		
		
		$coltools	=	($_GET['docol'] == 'true')?'toolsactive':'tools';
		
		
		$query		=	"SELECT `job_id` FROM `uploads` WHERE `job_id` = '$jid';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$paratools	=	'toolsactive';
			$crtools	=	'toolsactive';
			$rotools	=	'toolsactive';
			
		}
		else 
		{
			$paratools	=	'tools';
			$crtools	=	'tools';
			$rotools	=	'tools';
		}
		
		$cropTool	=	"<div title=\"crop image\" id=\"crop\" class=\"$crtools\"></div>";
		$rotateTool	=	"<div title=\"rotate image\" id=\"rotate\" class=\"$rotools\"></div>";
		$allignTool	=	"<div title=\"text alignment\" id=\"alignment\" class=\"$altools\"></div>";
		
		$paraTool	=	"<div title=\"Add text\" id=\"paragraph\" class=\"$paratools\"></div>"; 		
		$colorTool	=	"<div title=\"add color mask\" id=\"color\" class=\"$coltools\"></div>";
		$saveTool	=	"<div title=\"retouch comment\" onclick=\"ac('$jid')\" id=\"comment\" class=\"tools\"></div>";
		$generalpr	=	"<div id=\"gpr\"><img src=\"images/ajaxloader.gif\" alt=\"loading\"/></div>";
		
			$coords	=	<<<heredoc
		<div id="myc">
		<form>
			<input class="coords" type="text" size="4" id="x" name="x" />
			<input class="coords" type="text" size="4" id="y" name="y" />
			<input class="coords" type="text" size="4" id="x2" name="x2" />
			<input class="coords" type="text" size="4" id="y2" name="y2" />
			<input class="coords" type="text" size="4" id="w" name="w" />
			<input class="coords" type="text" size="4" id="h" name="h" />
		</form>
</div>
<div id="save">Save</div>
heredoc;
		if ($_GET['state'] == 'canvas') {
			$mydb		=	new  db();
			$query		=	"SELECT pt.`pages` FROM `products_templates` pt LEFT JOIN `jobs` j ON j.`product_id`	=	pt.`product_id` WHERE j.`job_id` = '$jid';";
			if ($mydb->query($query)&&$mydb->numRows()) {
				$pgArray	=	$mydb->getArray();
				
				if ($pgArray[pages] == 1) {
					$stylenext		="style=\"opacity:0.2;filter:alpha(opacity=20);\"";
					$styleprev		="style=\"opacity:0.2;filter:alpha(opacity=20);\"";
				}
				
				if ($pgArray[pages] > 1) {
					
					$stylenext		="style=\"opacity:1;filter:alpha(opacity=100);cursor:pointer;\"";
					$styleprev		="style=\"opacity:0.2;filter:alpha(opacity=20);\"";
				}
				
				
			}
			
			$nextbtn	=	"<div $stylenext class=\"pgbtns\" id=\"next\"></div>";
			$prevbtn	=	"<div $styleprev class=\"pgbtns\" id=\"prev\"></div>";
			
			$tholder	.=	"<div id=\"pgdsc\"><span id=\"inpg\">{$pgArray[pages]}</span> <span style=\"line-height:20px;margin-left:-5%;\">page template</span>$prevbtn<span id=\"curpage\">0</span>$nextbtn</div>";
			$tholder	.=	"<div id=\"toolbar\">$coords$cropTool$rotateTool$allignTool$colorTool$saveTool$generalpr</div>";
			return $tholder;
		}
		
		
	}
        
        function templatecropper($pg,$tid,$tleft,$ttop,$twidth,$theight,$tratio)
        {
            //pulling template file data
            $mydb   =   new db();
            $resource = NewMagickWand ();
            $time     = time();
            $query  =   "SELECT `file_name`, `width`, `height` FROM `uploads` WHERE `template_id` = '$tid' AND `pg` = '$pg' ORDER BY `upload_id` DESC";
            if($mydb->query($query)&&$mydb->numRows()){
                $tdata  =   $mydb->getArray(); 
                $twidth =   ($twidth / 100)*$tdata['width'];
                $theight=   ($theight/100)*$tdata['height'];
                $tleft  =   ($tleft*$tdata['width'])/100;
                $ttop   =   ($ttop*$tdata['height'])/100;
                $file   =   '../templates/'.$tdata['file_name'];
                if (MagickReadImage ( $resource, $file )) {
                    
                    if(MagickCropImage ( $resource, $twidth, $theight, $tleft, $ttop ))
                    {
                        mail('sonnyfraikue@gmail.com', 'templatecropper', "$resource, $twidth, $theight, $tleft, $ttop");
                        MagickWriteImage ( $resource, $file );  
                        return $time; 
                    }
                }
                else{
                    mail('sonnyfraikue@gmail.com', 'file not read', $file);
                }
            }
            
        }




        function cropper($jid, $x, $y, $x2, $y2, $w, $h) {
		$mydb	  =	new  db();
		$resource = NewMagickWand ();
		$t		  =	time();
		$query	  =	"SELECT `file_name`, `width`, `height`, `wf_width`, `wf_height`, `ratio` FROM `uploads` WHERE `job_id` = '$jid';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$uploadArray	=	$mydb->getArray();
			$assetname		=	$uploadArray['file_name'];
			$assetname		=	explode('?',$assetname);
			$assetname		=	$assetname[0];
			
			
				$xfact	=	$uploadArray['width']/$uploadArray['wf_width'];
				$yfact	=	$uploadArray['height']/$uploadArray['wf_height'];
			
			
		}
		
		
		$file = "jobs/job$jid/$assetname";
		
		$fileinfo = pathinfo ( $file );
		$ext = strtolower ( $fileinfo ['extension'] );
		//$newfile = "jobs/job$jid/$jid" . "_crop.$ext";
		$newfile1 = "jobs/job$jid/$jid" . ".workfile.$ext";
		$file = "jobs/job$jid/$jid" . ".$ext";
				
		if (MagickReadImage ( $resource, $file )) {
			
			if (MagickCropImage ( $resource, $w*$xfact, $h*$yfact, $x*$xfact, $y*$yfact )) {
				//MagickWriteImage ( $resource, $newfile );
				MagickWriteImage ( $resource, $newfile1 );
				if ($w > $h) {
				$viewWidth	=	400;
				$prop	=	"width=\"400px\"";
				}
				else 
				{
				$viewHeight	=	400;
				$prop	=	"height=\"400px\"";
				}
				
				
				
				return '<img '.$prop.' src="' . $newfile1 . '?t='.$t.'" alt="cropped asset" title="cropped asset"/>';
			}
		}
	}
	
	function rotate($jid) {
		
		$resource = NewMagickWand ();
		$mydb = new db ( );
		
		$query = "SELECT `file_name`, `wf_width`, `wf_height`, `width`, `height` FROM `uploads` WHERE `job_id` = '$jid' LIMIT 1;";
		
		
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$assetArray = $mydb->getArray ();
			$expfile	=	explode('?',$assetArray['file_name']);
			
			$fileinfo = pathinfo ( $expfile[0] );
			$ext =  $fileinfo ['extension'];
			$file = "jobs/job$jid/$jid.workfile.$ext";
			$filelg = "jobs/job$jid/$jid.$ext";
						
			if (MagickReadImage ( $resource, $file )) {
				
				if (MagickRotateImage ( $resource, null, 90 )) {
					if (MagickWriteImage ( $resource, $file )) {
						list($img_w,$img_h)		=	getimagesize($file);
						$query	=	"UPDATE `uploads` SET `wf_width` = '$img_w', `wf_height` = '$img_h', `height` = '{$assetArray['width']}', `width` = '{$assetArray['height']}' WHERE `job_id` = '$jid';";
						if ($mydb->query($query)&&$mydb->getAffRows()) {
						if (MagickReadImage ( $resource, $filelg )) {
							if (MagickRotateImage ( $resource, null, 90 )) {
								MagickWriteImage ( $resource, $filelg );
							}
							
							
						}
						
							
							
						}
					$time	=	time();
					return "$file?t=$time,$img_w,$img_h";
					}
				}
			
			}
		
		}
	
	}
	
	function clearimgs($jid) {
		$mydb = new db ( );
		
		$query = "SELECT `assetname` FROM `jobs` WHERE `job_id` = '$jid' LIMIT 1;";
		if ($mydb->query ( $query ) && $mydb->numRows ()) {
			$assetArray = $mydb->getArray ();
			
			$file = "jobs/job$jid/{$assetArray['assetname']}";
			$fileinfo = pathinfo ( $file );
			$ext = strtolower ( $fileinfo ['extension'] );
		}
		$cropfile = "jobs/job$jid/$jid" . "_crop.$ext";
		$rotatefile = "jobs/job$jid/$jid" . "_rotate.$ext";
		
		if (file_exists ( $cropfile )) {
			unlink ( $cropfile );
		}
		
		if (file_exists ( $rotatefile )) {
			unlink ( $rotatefile );
		}
	
	}
	function showproducts() {
		
		if ($productArray	=	$this->productsarray()) {
			
			foreach ($productArray as $eachproductArray) {
				$breakdown	.=	"<a href='index.php?product={$eachproductArray['description']}'><div class='navbtns'>{$eachproductArray['description']}</div></a>";
			}
		}
		return $breakdown;
	}
	
	
	function nav() {
		
		return "<div id='nav'> 
		<a href='{$_SESSION['serverArray']['host_name']}/?state=canvas'><div id='canvas' class='navbtns'>Canvas</div></a> 
		<a href='{$_SESSION['serverArray']['host_name']}/?state=products'><div class='navbtns'>Browse templates</div></a> 
		<div id='pLayer'>{$this->showproducts()}</div></div>";
	}
	
	function bnav() {
		$logout = ($_SESSION ['user_id']||$_SESSION ['simplecp']) ? "<a class='glink' href='{$_SESSION['clientArray']['http_host']}/logout.php'>Logout</a>" : null;
		$tasks = ($_SESSION ['user_id']||$_SESSION ['simplecp']) ? "<a class='glink' href='{$_SESSION['clientArray']['http_host']}/cp/?md=template&state=canvas#'>Templates</a>" : null;
		
		return "<div id='bnav'><div style='width:800px;text-align:center;margin:auto;'> <div style=\"float:left;\">$recommended <a class='glink' href='press.php'>Webmasters</a> <a class='glink' href='press.php'>Press releases</a> <a class='glink' href='contact.php'>Contact us</a> <a class='glink' href='{$_SESSION['clientArray']['http_host']}/cp'>Control panel</a> <a class='glink' href='sponsored.links.php'>Links</a> <a class='glink' href='terms.php'>Disclaimer</a> <a class='glink' href='quality.php'>Quality</a> <a class='glink' href='{$_SESSION['clientArray']['http_host']}/?state=products'>Products</a> <a class='glink' href='index.php'>Home</a>$tasks$logout</div></div></div>";
	}
	
	function linkcreator($sitepath) {
		
		// send post data  
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, "$sitepath" );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$buffer = curl_exec ( $ch );
		curl_close ( $ch );
		
		$buffer = explode ( 'title>', $buffer );
		//print_r($buffer);
		$title = strip_tags ( $buffer [1] );
		return $title = preg_replace ( '/>/', '', $title );
	}
	
function anchor($jobid) {
		$mydb	=	new  db();
		$time	=	time();
		
		$query	=	"SELECT `wf_width`, `wf_height` FROM `uploads` WHERE `job_id` = '$jobid' AND `wf_width` IS NOT NULL AND `wf_height` IS NOT NULL LIMIT 1;";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$ridArray	=	$mydb->getArray();
		}
			
		$c_width	=	30; 
		$c_height	=	20;
		
		$max_x		=	$ridArray['wf_width'] - $c_width;
		$max_y		=	$ridArray['wf_height'] - $c_height;
		
		$rand_x		=	rand(0,$max_x);
		$rand_y		=	rand(0,$max_y);
		
		$query	=	"INSERT INTO `retouch_comments` (`job_id`, `x`, `y`, `creation_timestamp`) VALUES ('$jobid', '$rand_x', '$rand_y', '$time')";
		
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			
		return $mydb->getInsID().'&S&'.$rand_x.'&S&'.$rand_y;
		
		}
		
	}
	
	function adspace($jobid) {
		$tophalf		=	"<div id='bs'><div class='bsT'>product & size</div></div><div id='bs2'><div class='bsT'>add image</div></div><div id='bs3'><div class='bsT'>checkout</div></div>";
		$productArray	=	$this->productsarray();
		foreach ($productArray as $eachproduct) {
			$bottombreakdown	.=	"<div class='fp'><div class='fpt'>{$eachproduct['description']}</div></div>";
		}
		if(!$_GET['state']){
			$prev	=	"<div id=\"pprev\"></div>";
			$next	=	"<div id=\"pnext\"></div>";
		return "<div id='aSpace'><div id='tophalf'>$tophalf</div><div id='bottomhalf'>$prev$next<div id='proler'>$bottombreakdown</div></div></div>";
		}
	}
	function adproduct($id,$jobid) {
		$mydb	=	new  db();
		
		if (!is_int($id)) {
		if ($idExplode	=	explode('_',$id)) {
			$id 		= 	strtolower($idExplode[0]);
		}
		$query	=	"SELECT `product_id`, `description` FROM `products`;";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$descArray	=	$mydb->getFullArray();
			foreach ($descArray as $each) {
				$strspc	=	strtolower(preg_replace('/ /','',$each['description']));
		
				if($strspc == $id)
				{
				$id	=	$each['product_id'];	
				break;
				}
				
			}
		}
		}
		
		
		$query	=	"UPDATE `jobs` SET `product_id` = '$id' WHERE `job_id` = '$jobid' LIMIT 1;";
		
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			return true;
		}
	}
	
	function sizedata($jobid) {
		$mydb	=	new  db();
		if ($jobid == 0) {
		$query	=	"SELECT * FROM `handsometoad`.`psizes` WHERE `ps_id` = '2';";
		}
		else
		{
		$query	=	"SELECT j.`paper_size`, j.`product_id`, pz.*, hu.`wf_width`, hu.`wf_height` FROM `handsometoad`.`jobs` AS j LEFT JOIN `handsometoad`.`psizes` AS pz ON j.`paper_size` = pz.`ps_id` LEFT JOIN `handsometoad`.`uploads` AS hu ON j.`job_id` = hu.`job_id` WHERE j.`job_id` = '$jobid'";
		}
		if ($mydb->query($query)&&$mydb->numRows()) {
		$sizedata	=	 $mydb->getArray();
		$sizedata['wf_width']	=	($sizedata['wf_width'])?$sizedata['wf_width']:0;
		$sizedata['wf_height']	=	($sizedata['wf_height'])?$sizedata['wf_height']:0;
		return "{$sizedata['paper_size']},{$sizedata['product_id']},{$sizedata['width']},{$sizedata['height']},{$sizedata['color']},{$sizedata['wf_width']},{$sizedata['wf_height']}";
		}
	}
	
	function randassets() {
		$mydb	=	new  db();
		echo $query	=	"SELECT u.`file_name`, u.`user_id`, u.`width`, u.`height`, u.`description`, us.`fname`, us.`sname` FROM `uploads` u LEFT JOIN `users` us ON u.`user_id` = us.`user_id` WHERE u.`job_id` = '0' AND u.description != 'Please add a description for your image' AND `template_id` IS NULL ORDER BY RAND() LIMIT 0,1;"; 
		if ($mydb->query($query)&&$mydb->numRows()) {
			$uploadarray	=	$mydb->getArray();
			$uploadarray['description']	=	ucfirst($uploadarray['description']);
			$fileinfo	=	pathinfo($uploadarray['file_name']);
			$file		=	$fileinfo['filename'].'.workfile.'.$fileinfo['extension'];
			$innerdiv	.=	"<div style='width:400px;text-align:center;height:245px;overflow:hidden;top:25px;position:absolute;left:40px;'><img width=\"400px\" src=\"{$_SESSION['serverArray']['host_name']}/assets/asset{$uploadarray['user_id']}/$file\" alt=\"{$uploadarray['fname']} {$uploadarray['sname']}'s digital asset\"/></div>";
			$innerdiv	.=	"<p id='fdsc'>{$uploadarray['fname']} {$uploadarray['sname']} - {$uploadarray['description']} - {$uploadarray['width']} x {$uploadarray['height']} pixels.</p>";
			return $innerdiv;
		}
		
	}
	
	function registerArtist() {
		$content	.=	"<div style=\"color:#3399ff;font-size:30px;position:absolute;top:50px;left:10px;\">Create your account in <span style=\"font-size:40px;color:orange;\">1 step</span></div>";
		$content	.=	"<div style=\"color:#66ff66;font-size:20px;position:absolute;top:110px;right:10px;text-align:right;\">and start earning from your <span style=\"font-size:40px;color:red;\">digital art</span></div>";
		$content	.=	"<div style=\"color:yellow;font-size:60px;position:absolute;top:170px;text-align:center;width:100%;\">today</div>";
		
		return $registration	=	"<div id=\"foyereg\">$content</div>";
	}
	
	
	
	function promoSpaceArtist() {
	$pbbox	=	($_GET['md']=='register')?$this->registerArtist():$this->randassets();
	return $promobreakdown	.=	"<div id=\"pbh\" class=\"pbox\">{$this->visualCats()} <div class=\"pbbox\">$pbbox</div></div>";
	}
	
	function adSpaceArtist() {
		return $ad	.=	"<div class=\"abox\"><div id=\"wtp\">the web-to-print platform.</div></div>";
	}
	
	function regcontent($hide) {
		if ($hide == 1) {
			}
		else 
		{
			$logincontent	.=	"<input style=\"padding-left:3px;font-size:18px;width:232px;height:20px;position:absolute;left:20px;top:60px;border:1px solid #ddd;color:#666;\" type=\"text\" name=\"email\" id=\"loginemail\" value=\"email\">";
			$logincontent	.=	"<input style=\"padding-left:3px;font-size:18px;width:150px;height:20px;position:absolute;left:20px;top:168px;border:1px solid #ddd;\" type=\"text\" name=\"captcha\" id=\"captcha\">";
		}
		
		
		$logincontent	.=	"<img style=\"width:150px;height:30px;position:absolute;left:20px;top:100px;border:1px solid #ddd;\" src=\"../captcha.php?t=".time()."\"/>";
		
		$logincontent	.=	"<div style=\"position:absolute;left:20px;top:150px;color:#666;font-size:12px;\">Type the letters above:</div>";
		
		return $logincontent;
	}
	
	function logincontent($hide) {
		if ($hide == 1) {
			
		}
		else 
		{
		$logincontent	.=	"<input style=\"color:#666;padding-left:3px;font-size:18px;width:232px;height:20px;position:absolute;left:20px;top:60px;border:1px solid #ddd;\" type=\"text\" name=\"email\" id=\"loginemail\" value=\"email\">";
		$logincontent	.=	"<input style=\"color:#666;padding-left:3px;font-size:18px;width:232px;height:20px;position:absolute;left:20px;top:100px;border:1px solid #ddd;\" type=\"text\" name=\"pswd\" id=\"pswd\" value=\"password\">";
		$logincontent	.=	"<input style=\"color:#666;position:absolute;left:20px;top:140px;border:1px solid #ddd;\" type=\"checkbox\" name=\"rembme\" id=\"rembme\">";	
		$logincontent	.=	"<div style=\"line-height:20px;font-size:14px;color:#666666;width:114px;height:24px;position:absolute;left:20px;top:212px;border:3px solid #bbbbbb;background-image:url('../images/button2.jpg');cursor:pointer;\" id=\"sdata\">enter</div>";
		}
		
		$logincontent	.=	"<div style=\"position:absolute;left:40px;top:140px;color:#666;font-size:12px;\">remember me</div>";
		$logincontent	.=	"<div style=\"position:absolute;left:40px;top:160px;color:#666;font-size:12px;\">difficulty logging in? <a class=\"link\" href=\"#\">click here</a></div>";
		return $logincontent;
	}
	
	
	function loginregisterportal() {
	
	if($_GET['md'] == 'register')
	{
		return $portalbreakdown	.=	"<div id=\"lrph\" class=\"lrport\"><div class=\"lrportu\"> <div style=\"z-index:2;bottom:0px;left:0px;position:absolute;width:104px;height:19px;background-image:url('{$_SESSION['serverArray']['host_name']}/images/tab_light.png');color:#666;font-size:12px;cursor:pointer;line-height:20px;\" id=\"login\">login</div> <div style=\"z-index:3;position:absolute;width:104px;height:19px;background-image:url('{$_SESSION['serverArray']['host_name']}/images/tab_dark.png');bottom:0px;left:80px;color:#666;font-size:12px;cursor:pointer;line-height:20px;\" id=\"register\">register</div> </div> <div id=\"liA\" style=\"width:275px;height:318px;position:absolute;bottom:0px;left:0px;\">{$this->logincontent(1)}</div> </div>";
	}
	else
	{
		return $portalbreakdown	.=	"<div id=\"lrph\" class=\"lrport\"> </div>";
	}
	}
	
	function visualCats() {
		$mydb	=	new  db();
		
		$query	=	"SELECT * FROM `art_cats` ORDER BY `order` ASC;";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$catsarray	=	$mydb->getFullArray();
			//$catsbreakdown	.=	"<div style=\"width:400px;height:82px;text-align:center;margin-left:auto;margin-right:auto;background-color:yellow;\">";
			foreach ($catsarray as $eachcatsarray) {
				$catsbreakdown	.=	"<div style=\"cursor:pointer;position:relative;float:left;width:80px;height:82px;background-image:url({$_SESSION['serverArray']['host_name']}/{$eachcatsarray['icon_path']});background-repeat:no-repeat;margin-right:20px;margin-left:20px;\"> <div style=\"position:absolute;width:100%;bottom:5px;font-size:11px;left:0px;color:#333;\">{$eachcatsarray['category']}</div> </div>";
			}
			//$catsbreakdown	.=	"<div>";
			return $catsbreakdown;
		}
		
	}
	
	function loginf($email,$password)
	{
		$mydb		=	new  db();
		$md5pswd	=	md5($password);
		$query		=	"SELECT * FROM `users` WHERE `email` = '$email' AND `passwd` = '$md5pswd' LIMIT 1;";
		//mail('sonny@feburman.co.uk','loginf',$query);
		if ($mydb->query($query)&&$mydb->numRows()) {
			$_SESSION['userArray']	=	$mydb->getArray();
			$_SESSION['user_id']	=	$_SESSION['userArray']['user_id'];
			return 'userid='.$_SESSION['userArray']['user_id'];
		}
	}
	
	function froglogo() {
		return "<div id=\"logo\" style=\"background-image:url({$_SESSION['serverArray']['host_name']}/images/frog_little.png);z-index:30;width:60px;height:89px;position:absolute;left:233px;top:5px;\"></div>";
	}
	
function registerf($email,$captcha)
	{
		$mydb		=	new  db();
		$captcha	=	md5($captcha);
		$password	=	$this->generatePassword(8,3);
		$md5pswd	=	md5($password);
		
		if ($captcha == $_SESSION['key']) {
		
		$query	=	"SELECT `user_id` FROM `users` WHERE `email` = '$email' LIMIT 1;";
		if ($mydb->query($query)&&!$mydb->numRows()) {
			$useridArray	=	$mydb->getArray();
			//creating an account 
			$query	=	"INSERT INTO `users` (`email`, `passwd`) VALUE('$email', '$md5pswd')";
			if ($mydb->query($query)&&$mydb->getAffRows()) {

	$message	=			<<<heredoc
Welcome to handsometoad.com, you are now only a few steps away from selling digital copies of your artistic works.

Please find your login below;

email:$email

password:$password

Go to http://www.handsometoad.com/art to login.

You will be prompted to update your password to something memorable when you first login.

Should you have any queries you may contact us at info@handsometoad.co.uk

Looking forward to a great partnership.

Handsome Toad
				
heredoc;
				mail($email,'Welcome to handsometoad.com',$message);
				$_SESSION['user_id']	=	$mydb->getInsID();
				return $mydb->getInsID();
			}
			
		}
		else 
		{
			return "$email already registered.";
		}
			
	}
	else 
	{
		return 'captcha ';
	}
	
	
	
		
	}
	
	function artistoverview($userid) {
		$mydb	=	new db();
		$query	=	"SELECT `fname`, `sname`, `email`, `phone` FROM `users` WHERE `user_id` = '$userid';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$artistArray	=	$mydb->getArray();
		}
		
		//checking upload data
		$query	=	"SELECT `upload_id` FROM `uploads` WHERE `user_id` = '$userid' AND `job_id` = '0';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$uploadArray	=	$mydb->getFullArray();
			$uploadcount	=	count($uploadArray);
		}
		$uploadcount	=	($uploadcount)?$uploadcount:0;
		
		$query	=	"SELECT `upload_id` FROM `uploads` WHERE `user_id` = '$userid' AND `job_id` = '0' AND `description` NOT LIKE 'Please add a description for your image';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$uploadArray		=	$mydb->getFullArray();
			$descriptioncount	=	count($uploadArray);
		}
		$descriptioncount	=	($descriptioncount)?$descriptioncount:0;
		
		//retrieving payment method
		$query	=	"SELECT pm.`name` FROM `payment_info` pi LEFT JOIN `payment_method` pm ON pi.`p_id` = pm.`p_id` WHERE pi.`user_id` = '{$_SESSION['user_id']}';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$whereArray	=	$mydb->getArray();
			
		}
		
		$whereArray['name']	=	($whereArray['name'])?$whereArray['name']:'e.g paypal, transfer etc';
		
		$personalinfo	.=	"<div style=\"cursor:pointer;width:100%;position:absolute;top:10px;left:0px;\"><h1>Personal information</h1></div>";
		$personalinfo	.=	"<div style=\"cursor:pointer;font-size:12px;line-height:23px;height:120px;width:90%;position:absolute;top:65px;left:24px;text-align:left;float:left;\"><ul><li>Build your profile, this is information site users will read about you.<span class=\"bluesf\">[{$artistArray['fname']} {$artistArray['sname']}]</span></li><li>Telephone numbers. <span class=\"bluesf\">[{$artistArray['phone']}]</span>.</li> <li>Email.<span class=\"bluesf\">[{$artistArray['email']}]</span></li></ul></div>";
		
		$transaction	.=	"<div style=\"cursor:pointer;width:100%;position:absolute;top:10px;left:0px;\"><h1>Transactions</h1></div>";
		$transaction	.=	"<div style=\"cursor:pointer;font-size:12px;line-height:23px;height:120px;width:90%;position:absolute;top:65px;left:24px;text-align:left;float:left;\"><ul><li>See details of your current earnings.</li><li>Monitor asset statistics e.g Most popular assets.</li></ul></div>";
		
		$paymentinfo	.=	"<div style=\"cursor:pointer;width:100%;position:absolute;top:10px;left:0px;\"><h1>Payment information</h1></div>";
		$paymentinfo	.=	"<div style=\"cursor:pointer;font-size:12px;line-height:23px;height:120px;width:90%;position:absolute;top:65px;left:24px;text-align:left;float:left;\">Tell us...<ul><li>how <span class=\"bluesf\">[{$whereArray['name']}]</span></li><li>and when <span class=\"bluesf\">[e.g every 28 Days]</span></li>to pay your money.</ul></div>";
		
		$gallery		.=	"<div style=\"cursor:pointer;width:100%;position:absolute;top:10px;left:0px;\"><h1>Gallery</h1></div>";
		$gallery		.=	"<div style=\"cursor:pointer;font-size:12px;line-height:23px;height:120px;width:90%;position:absolute;top:65px;left:24px;text-align:left;float:left;\"><ul><li>Upload digital copies of your artistic work.<span class=\"bluesf\">[$uploadcount]</span></li><li>Decide which products your art is printed on.</li><li>Add/Edit descriptions for your art.<span class=\"bluesf\">[$descriptioncount of $uploadcount]</span></li></ul></div>";
		
		$template		.=	"<div style=\"cursor:pointer;width:100%;position:absolute;top:10px;left:0px;\"><h1>Templates</h1></div>";
		$template		.=	"<div style=\"cursor:pointer;font-size:12px;line-height:23px;height:120px;width:90%;position:absolute;top:65px;left:24px;text-align:left;float:left;\"><ul><li>Create templates for greeting cards, postcards and more.</li><li>Decide where text goes, color, fontsize etc.</li><li>Allow or dis-allow draggable text, and a whole lot more.</li></ul></div>";
		
		
		$box	.=	"<div id=\"b1\"><a class=\"thickbox\" title=\"{$artistArray['fname']}'s personal information\" href=\"personal.info.php?keepThis=true&TB_iframe=true&height=600&width=800\">$personalinfo</a></div>";
		$box	.=	"<div id=\"b2\"><a class=\"thickbox\" title=\"Transaction\" href=\"transaction.php?height=400&width=800\">$transaction</a></div>";
		$box	.=	"<div id=\"b3\"><a class=\"thickbox\" title=\"Payment information\" href=\"payment.info.php?keepThis=true&TB_iframe=true&height=600&width=800\">$paymentinfo</a></div>";
		$box	.=	"<div id=\"b4\"><a class=\"thickbox\" title=\"{$artistArray['fname']}'s assets\" href=\"gallery.php?keepThis=true&TB_iframe=true&height=650&width=850\">$gallery</a></div>";
		$box	.=	"<div id=\"b5\"><a class=\"thickbox\" title=\"Templates\" href=\"../cp/index.php?md=template&state=canvas&keepThis=true&TB_iframe=true&height=600&width=800\">$template</a></div>";
		
		return $box;
	}
	
function lgt($path)
{
	return $logout	=	"<a href=\"$path\"><div style=\"width:50px;height:50px;background-color:red;position:absolute;top:20px;left:743px;z-index:10;\"><p>logout</p></div></a>";
}
	
function savefield($table,$field,$value,$whereIDNAME,$whereID) {
	
	$mydb	=	new db();
	$query	=	"SELECT `$field` FROM `$table` WHERE `$whereIDNAME` = '$whereID'";
	$value	=	(($field == 'fname')||($field == 'sname'))?ucfirst($value):$value;
	
	if ($mydb->query($query)&&$mydb->numRows()) {
	$query	=	"UPDATE `handsometoad`.`$table` SET `$field` = '$value' WHERE `$whereIDNAME` = '$whereID'";
	}
	else 
	{
		$query	=	"INSERT INTO `handsometoad`.`$table` (`$field`, `$whereIDNAME`) VALUES('$value', '$whereID')";
	}
	
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		return "<img src=\"{$_SESSION['serverArray']['host_name']}/images/tick.png\" alt=\"...\" />";
	}
}

function saypages($pid) {
	$mydb	=	new  db();
	$query	=	"SELECT `number_of_pages` FROM `products` WHERE `product_id` = '$pid';";
	if ($mydb->query($query)&&$mydb->numRows()) {
		$saypages	=	$mydb->getArray();
		return $saypages['number_of_pages'];
	}	
}

function sonofsaypages($tid)
{
	$mydb	=	new  db();
	$query	=	"SELECT `t_id` FROM `template_product` WHERE `t_id` = '$tid'";
	if ($mydb->query($query)&&$mydb->numRows()) {
		return $mydb->numRows();
	}
	
}

function productpages() {
	;
}



function serverdata($clientid) {
	$mydb	=	new  db();
	$query	=	"SELECT * FROM `server_details` LIMIT 1;";
	if ($mydb->query($query)&&$mydb->numRows()) {
		return 	$mydb->getArray();
	}
}


function glibrary($category,$focus) {
	$mydb	=	new db();	
	$query	=	"SELECT u.*, us.`fname`, us.`sname` FROM `uploads` u  LEFT JOIN `users` us ON u.`user_id` = us.`user_id` WHERE u.`user_id` IS NOT NULL;";
	if ($category) {
		$category	=	preg_replace('/-/',' ',$category);
		$query	=	"SELECT u.*, ac.`category`, ac.`cat_id`, usr.`fname`, usr.`sname` FROM `uploads-art_cats` uc LEFT JOIN uploads u ON uc.`upload_id` = u.`upload_id` LEFT JOIN art_cats ac ON uc.`cat_id` = ac.`cat_id` LEFT JOIN `users` usr ON u.`user_id` = usr.`user_id` WHERE u.`user_id` IS NOT NULL AND ac.`category` = '$category' ";
	}
	
	if ($focus) {
		$focus	=	preg_replace('/-/',' ',$focus);
		$query	=	"SELECT u.*, usr.`fname`, usr.`sname` FROM `uploads` u LEFT JOIN `users` usr ON u.`user_id` = usr.`user_id` WHERE u.`description` = '$focus' LIMIT 1;";
	}
	if ($_GET['search']) {
		$_GET['search']	=	trim($_GET['search']);
		$query	=	"SELECT u.*, usr.`fname`, usr.`sname` FROM `uploads` u LEFT JOIN `users` usr ON u.`user_id` = usr.`user_id` WHERE u.`description` REGEXP '{$_GET['search']}' LIMIT 1;";
	}
	
	
	$serverdata	=	$this->serverdata(1);
	if ($mydb->query($query)&&$mydb->numRows()) {

		$uploadArray	=	$mydb->getFullArray();
		foreach ($uploadArray as $eachupload) {
			
			$pathinfo	=	pathinfo($eachupload['orig_file']);
			$eachupload['orig_file']	=	$serverdata['host_name'].'/assets/asset'.$eachupload['user_id'].'/'.$pathinfo['filename'].'.thumb.'.$pathinfo['extension'];
			$breakdown	.=	($breakdown)?'&&&'.$eachupload['upload_id'].','.$eachupload['orig_file'].','.$this->ShortenText($eachupload['description'],10).','.$eachupload['description'].','.$eachupload['fname'].','.$eachupload['sname']:$eachupload['upload_id'].','.$eachupload['orig_file'].','.$this->ShortenText($eachupload['description'],10).','.$eachupload['description'].','.$eachupload['fname'].','.$eachupload['sname'];
		}
		return $breakdown;
	}
}

function gassets($userid) {
	$mydb	=	new db();
	$query	=	"SELECT * FROM `uploads` WHERE `user_id` = '$userid';";
	$serverdata	=	$this->serverdata(1);
	if ($mydb->query($query)&&$mydb->numRows()) {

		$uploadArray	=	$mydb->getFullArray();
		foreach ($uploadArray as $eachupload) {
			
			$pathinfo	=	pathinfo($eachupload['orig_file']);
			$eachupload['orig_file']	=	$serverdata['host_name'].'/assets/asset'.$userid.'/'.$pathinfo['filename'].'.thumb.'.$pathinfo['extension'];
			$breakdown	.=	($breakdown)?'&&&'.$eachupload['upload_id'].','.$eachupload['orig_file'].','.$this->ShortenText($eachupload['description'],10):$eachupload['upload_id'].','.$eachupload['orig_file'].','.$this->ShortenText($eachupload['description'],10);
		}
	
		return $breakdown;
	}
	
}

function uldestore($uid,$description) {
	$mydb	= new  db();
	$query	=	"UPDATE `uploads` SET `description` = '$description' WHERE `upload_id` = '$uid'";
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		return TRUE;
	}
	
}

function artcats() {
	$mydb	=	new  db();

	$query	=	"SELECT * FROM `art_cats`";
if ($mydb->query($query)&&$mydb->numRows()) {
	$categories	=	$mydb->getFullArray();
	return $categories;
	
}

}

function assettocats($assetid,$catid) {
	
	if ($assetid&&$catid) {
	$mydb	=	new  db();
	$query	=	"SELECT `id` FROM `uploads-art_cats` WHERE `upload_id` = '$assetid' AND `cat_id` = '$catid' LIMIT 1;";
	if ($mydb->query($query)&&!$mydb->numRows()) {
	$query	=	"INSERT INTO `uploads-art_cats` (`upload_id`, `cat_id`) VALUES('$assetid', '$catid')";
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		return TRUE;
	}
	}
	else 
	{
		$query	=	"DELETE FROM `uploads-art_cats` WHERE `upload_id` = '$assetid' AND `cat_id` = '$catid'";
		
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			
			return FALSE;
		}
		
	}
	
	}
	
}

function catsperasset($assetid,$includeasset) {
	$mydb	=	new  db();
	$query	=	"SELECT `cat_id` FROM `uploads-art_cats` WHERE `upload_id` = '$assetid';";
	
	
	if ($mydb->query($query)&&$mydb->numRows()) {
		$uploadcatsarray	=	$mydb->getFullArray();
		foreach ($uploadcatsarray as $eachcatid) {
			$catsbreakdown	.=	($catsbreakdown)?','.$eachcatid['cat_id']:$eachcatid['cat_id'];
			$catsbreakdowninc	.=	($catsbreakdowninc)?','.$assetid.'&&'.$eachcatid['cat_id']:$assetid.'&&'.$eachcatid['cat_id'];
		}
		if ($includeasset == 1) {
		return $catsbreakdowninc;
		}
		else 
		{
		return $catsbreakdown;
		}
	}
	
}

function showsmallcats($assetid,$js) {
	$mydb	=	new db();
	
	$left	=	10;
		$incr	=	13;
		$px	=	'px';
	foreach ($this->artcats() as $eachcat) {
		$query	=	"SELECT `upload_id` FROM `uploads-art_cats` WHERE `upload_id` = '$assetid' AND `cat_id` = '{$eachcat['cat_id']}';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$showcat	=	"background-color:#{$eachcat['background_color']};";
		}
		else 
		{
			$showcat	=	null;
		}
		
		
		$catbreakdown	.=	"<div title=\"{$eachcat['category']}\" style=\"cursor:pointer;border:1px solid #ccc;width:10px;height:10px;float:left;position:absolute;left:$left$px;$showcat\"></div>";
		$left 			 =	$left + $incr;
	}
	
	return $catbreakdown	=	"<div style=\"width:100%;height:12px;background-color:#fff;position:absolute;opacity:0.8;filter:alpha(opacity=80);\">".$catbreakdown."</div>";
	
	
	
}

function assetcheck($uid) {
	$mydb	=	new  db();
	$query	=	"SELECT `width`, `height` FROM `uploads` WHERE `upload_id` = '$uid';";
	
	
	if ($mydb->query($query)&&$mydb->numRows()) {
		$uploadArray	=	$mydb->getArray();
		
		//getting product dimensions
		$query	=	"SELECT `width`, `height`, `ps_id`, `min_ppmm` FROM `psizes`;";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$psArray	=	$mydb->getFullArray();
			$myc		=	4;
			$mmeq		=	25.4;
			foreach ($psArray as $eachproductsize) {
			$eachproductsize['width']	=		$eachproductsize['width']*$mmeq;
			$eachproductsize['height']	=		$eachproductsize['height']*$mmeq;
				if ((($uploadArray['width'] >= ($eachproductsize['width']*$eachproductsize['min_ppmm'])))&&(($uploadArray['height'] >= ($eachproductsize['height']*$eachproductsize['min_ppmm'])))) {
					
					//populating
					$query	=	"INSERT INTO `uploads-psizes` (`upload_id`, `ps_id`) VALUES('$uid', '{$eachproductsize['ps_id']}')";
					if ($mydb->query($query)&&$mydb->getAffRows()) {
						//SELECTING PRODUCTS FROM psizes-products 
						$query	=	"SELECT `product_id` FROM `psizes-products` WHERE `ps_id` = '{$eachproductsize['ps_id']}'";
						if ($mydb->query($query)&&$mydb->numRows()) {
							$productsArray	=	$mydb->getFullArray();
							foreach ($productsArray as $eachproduct) {
								$query	=	"INSERT INTO `uploads-products` (`upload_id`, `product_id`) VALUES('$uid', '{$eachproduct['product_id']}')";
								if ($mydb->query($query)&&$mydb->getAffRows()) {
									
								}
								
							}
							
						}
						
						
					}
					
					
				}
				
				
				
			}
			
		}
		
		
	}
	
}


function highlightproducts($uid) {
	$mydb	=	new  db();
	$query	=	"SELECT DISTINCT up.`product_id` , p.`description` FROM `uploads-products` up LEFT JOIN `products` p ON up.`product_id` = p.`product_id` WHERE up.`upload_id` = '$uid';";
	if ($mydb->query($query)&&$mydb->numRows()) {
		$pidArray	=	$mydb->getFullArray();
		foreach ($pidArray as $eachpid) {
			$pidbreakdown	.=	($pidbreakdown)?','.$eachpid['description']:$eachpid['description'];
		}
		return $pidbreakdown;
		
	}
	
}

function setpage($pg,$pid) {
	$mydb	=	new db();
	$query	=	"SELECT `product_id` FROM `page_objects` WHERE `product_id` = '$pid';";
	if ($mydb->query($query)&&!$mydb->numRows()) {
	$query	=	"INSERT INTO `page_objects` (`product_id`, `page`) VALUES('$pid', '$pg')";
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			return true;
		}
	}
}




function addtext($tid,$pg) {
	$mydb	=	new  db();
	$x		=	rand(0,132);
	$x_new	=	round(100*($x/331));
	$y		=	rand(0,418);
	$y_new	=	round(100*($y/468));	
	$w		=	40;
	$h		=	(50/468)*100;
	
//retrieving po_id
		$query	=	"SELECT `po_id` FROM `page_objects` WHERE `obj_type` IS NULL AND `t_id` = '$tid'";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$poArray	=	$mydb->getArray();
		}
		
	$query	=	"UPDATE `page_objects` SET `obj_type` = 'TEXT', `x` = '$x_new', `y` = '$y_new', `w` = '$w', `h` = '$h' WHERE `obj_type` IS NULL AND `t_id` = '$tid';";
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		
		
		return $poArray['po_id'].",".$x_new.",".$y_new.",".$w.",".$h;
	}
	else 
	{
	$x		=	rand(0,132);
	$x_new	=	round(100*($x/331));
	$y		=	rand(0,418);
	$y_new	=	round(100*($y/468));	
	$w		=	40;
	$h		=	(50/468)*100;	
		//insert new text object
		$query	=	"INSERT INTO `page_objects` (`t_id`, `page`, `obj_type`, `x`, `y`, `w`, `h`) VALUES('$tid', '$pg', 'TEXT', '$x_new','$y_new', '$w', '$h')	";
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			return $mydb->getInsID().",".$x_new.",".$y_new.",".$w.",".$h;
		}
		
	}
	
}

function showobj($pg,$tid) {
	$mydb	=	new  db();
	
	//inserting objects for page onto job
	if (!$_GET['md']&&$_GET['state'] == 'canvas') {
	$query	=	"SELECT `t_id` FROM `job_objects` WHERE `job_id` = '{$_SESSION ['jobArray'] ['job_id']}' AND `t_id` = '$tid' AND `page` = '$pg'";
	
	if ($mydb->query($query)&&!$mydb->numRows()) {
	$query	=	"SELECT * FROM `page_objects` WHERE `t_id` = '$tid' AND `page` = '$pg';";
		if ($mydb->query($query)&&$mydb->numRows()) {
			$objArray	=	$mydb->getFullArray();
			
			foreach ($objArray as $eachobj) {
				$query	=	"SELECT `t_id` FROM `job_objects` WHERE `job_id` = '{$_SESSION ['jobArray'] ['job_id']}' AND `t_id` = '{$eachobj['t_id']}' AND `page` = '{$eachobj['page']}' AND `obj_type` = '{$eachobj['obj_type']}' AND `x` = '{$eachobj['x']}' AND `y` = '{$eachobj['y']}' AND `w` = '{$eachobj['w']}' AND `h` = '{$eachobj['h']}'";
				
				if ($mydb->query($query)&&!$mydb->numRows()) {
					$query	=	"INSERT INTO `job_objects` (`po_id`, `job_id`, `t_id`, `page`, `obj_type`, `x`, `y`, `w`, `h`) VALUES('{$eachobj['po_id']}', '{$_SESSION ['jobArray'] ['job_id']}', '{$eachobj['t_id']}', '{$eachobj['page']}', '{$eachobj['obj_type']}', '{$eachobj['x']}', '{$eachobj['y']}', '{$eachobj['w']}', '{$eachobj['h']}')";
					
					if ($mydb->query($query)&&$mydb->getAffRows()) {
						
					}
					
				}
				
			}
			
		}
	}
	
	}
			
	
	$query	=	"SELECT * FROM `job_objects` WHERE `job_id` = '{$_SESSION ['jobArray'] ['job_id']}' AND `page` = '$pg' AND `obj_type` = 'TEXT' AND `t_id` = '$tid'";
	//
	if (!$_GET['md']&&$_GET['state'] == 'canvas'&&$mydb->query($query)&&$mydb->numRows()) {
		
	}
	else 
	{
		$query	=	"SELECT * FROM `page_objects` WHERE `t_id` = '$tid' AND`page` = '$pg';";
		$mydb->query($query)&&$mydb->numRows();
	}
		$objArray	=	$mydb->getFullArray();
		foreach ($objArray as $eachobj) {
			$objbreakdown	.=	($objbreakdown)?"&&{$eachobj['po_id']},{$eachobj['x']},{$eachobj['y']},{$eachobj['w']},{$eachobj['h']},{$eachobj['lock']},{$eachobj['content_text']},{$eachobj['font_id']},{$eachobj['font_color']},{$eachobj['font_size']}":"{$eachobj['po_id']},{$eachobj['x']},{$eachobj['y']},{$eachobj['w']},{$eachobj['h']},{$eachobj['lock']},{$eachobj['content_text']},{$eachobj['font_id']},{$eachobj['font_color']},{$eachobj['font_size']}";
		}
		return $objbreakdown;
}

function adjustcoordinates($x,$y,$poid,$t_width,$t_height) {
	$mydb	=	new  db();
	$poid	=	explode('tb',$poid);
	//mail('sonny@feburman.co.uk','adjustcoordinates',"$x,$y,$poid,$t_width,$t_height");
	
	$x_new	=	round(100*($x/$t_width));
	$y_new	=	round(100*($y/$t_height));
	
	$query	=	"UPDATE `page_objects` SET `x` = '$x_new', `y` = '$y_new' WHERE `po_id` = '$poid[1]';";
	
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		;
	}
	
}

function adjustside($id,$side,$value) {
	$mydb	=	new  db();
	$query	=	"UPDATE `page_objects` SET `$side` = '$value' WHERE `po_id` = '$id';";
	//mail('sonny@feburman.co.uk','adjustside',$query);
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		if ($side == 'font_id') {
			return $value;			
		}
		else 
		{
			return TRUE;
		}
	}
	
}

function insert_update_job_objects($poid,$jid,$tid,$pg,$objtype,$x,$y,$w,$h,$canv_x,$canv_y) {
	
	$poid	=	explode('tb',$poid);
	if ($poid[1]) {
		
	$mydb	=	new  db();	
	$x_new	=	round(100*($x/$canv_x));
	$y_new	=	round(100*($y/$canv_y));
	
	$query	=	"SELECT `po_id` FROM `handsometoad`.`job_objects` WHERE `po_id` = '{$poid[1]}' AND `job_id` = '$jid';";
	
	if ($mydb->query($query)&&$mydb->numRows()) {
		$query	=	"UPDATE `handsometoad`.`job_objects` SET `x` = '$x_new', `y` = '$y_new', `w` = '$w', `h` = '$h' WHERE `job_id` = '$jid' AND `po_id` = '{$poid[1]}';";
		
	if ($mydb->query($query)&&$mydb->getAffRows()) {
			;
		}
	}
	 else
	 {
	 	$query	=	"INSERT INTO `handsometoad`.`job_objects` (`po_id`, `job_id`, `t_id`, `page`, `obj_type`, `x`, `y`, `w`, `h`) VALUES('{$poid[1]}', '$jid', '$tid', '$pg', '$objtype', '$x_new', '$y_new', '$w', '$h')";
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			;
		}
	 }
	}
	
	

	
	
	
}

function check_job_objects($jid,$page) {
	$mydb	=	new  db();
	$query	=	"SELECT `po_id` FROM `job_objects` WHERE `page` = '$page' AND `job_id` = '$jid';";
	if ($mydb->query($query)&&$mydb->numRows()) {
		return 'hi';
	}
	
}


function dobj($id) {
	$mydb	=	new  db();
	$id	=	explode('cl',$id);
	
	$query	=	"DELETE FROM `job_objects` WHERE `po_id` = '$id[1]';";
	
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		return true;
	}
	
}

function dpobj($id) {
	$mydb	=	new  db();
	
	$query	=	"DELETE FROM `page_objects` WHERE `po_id` = '$id';";
	
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		return true;
	}
	
}

function newtemplate($name,$egtext,$numpages,$pid) {
	$mydb	=	new db();
//	mail('sonny@feburman.co.uk','newtemplate1',"$name,$egtext,$numpages,$pid");
	$query	=	"SELECT `t_id` FROM `templates` WHERE `name` = '$name';";
	if ($mydb->query($query)&&!$mydb->numRows()) {
		$query	=	"INSERT INTO `templates` (`name`, `example_text`) VALUES('$name', '$egtext')";
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			return $tid	=	 $mydb->getInsID();
		}		
	}
	else 
	{
		$tidArray	=	$mydb->getArray();
		return $tidArray['t_id'];
	}
	
	
	
}

function showtemplates() {
	$mydb	=	new db();
	$query	=	"SELECT * FROM `templates` ORDER BY `t_id` DESC;";
	
	if ($mydb->query($query)&&$mydb->numRows()) {
		$templatesArray	=	$mydb->getFullArray();
		
		foreach ($templatesArray as $eachtemplate) {
			$templatebreakdown	.=	($templatebreakdown)?"&&{$eachtemplate['t_id']},{$eachtemplate['name']},{$eachtemplate['example_text']}":"{$eachtemplate['t_id']},{$eachtemplate['name']},{$eachtemplate['example_text']}";
		}
		
		return $templatebreakdown;
	}
}

function showproductstemplates($pid) {
	$mydb	=	new db();
	$query	=	"SELECT pt.`template_id`, pt.`pages`, t.`name` FROM `products_templates` pt LEFT JOIN `templates` t ON pt.`template_id` = t.`t_id` WHERE pt.`product_id` = '2'";
	
	if ($mydb->query($query)&&$mydb->numRows()) {
		$templatesArray	=	$mydb->getFullArray();
		
		foreach ($templatesArray as $eachtemplate) {
			$templatebreakdown	.=	($templatebreakdown)?"&&{$eachtemplate['template_id']},{$eachtemplate['name']},{$eachtemplate['pages']}":"{$eachtemplate['template_id']},{$eachtemplate['name']},{$eachtemplate['pages']}";
		}
		
		return $templatebreakdown;
	}
}

function timage($id,$pg) {
	
	$mydb	=	new  db();
	$query	=	"SELECT `name` FROM `templates` WHERE `t_id` = '$id';";
        $time   = time();
	if ($mydb->query($query)&&$mydb->numRows()) {
		$nameArray	=	$mydb->getArray();
		$serverdata	=	$this->serverdata(1);
		$url	=	$serverdata['host_name'].'/templates/'.preg_replace('/ /','',$nameArray['name']).'.'.$pg.'.png?t='.$time;
		list($img_w,$img_h)	= getimagesize($url);
		return	$url.'&&'.$img_w.'&&'.$img_h;
	}
	 
}


function pname($id) {
	$mydb	=	new  db();
	$query	=	"SELECT `description` FROM `products` WHERE `product_id` = '$id';";
	
	if ($mydb->query($query)&&$mydb->numRows()) {
		$nameArray	=	$mydb->getArray();
		return $nameArray['description'];
	}
	
}




function tempintoprod($tid,$pid,$pg) {
	$mydb	=	new db();
	$query	=	"SELECT `number_of_pages` FROM `products` WHERE `product_id` = '$pid'";
	if ($mydb->query($query)&&$mydb->numRows()) {		
	$numberofpagesArray	=	$mydb->getArray();	
	$i	=	1;
	while ($i <= $numberofpagesArray['number_of_pages']) {		
	$query	=	"SELECT `t_id` FROM `template_product` WHERE `t_id` = '$tid' AND `product_id` = '$pid' AND `page` = '$i';";
	
	if ($mydb->query($query)&&!$mydb->numRows()) {
		$query	=	"INSERT INTO `template_product` (`t_id`, `product_id`, `page`) VALUES('$tid', '$pid', '$i')";			
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			//return $mydb->getInsID();
		}		
	}
		
		$i++;
	}
return TRUE; 
	}
}

function showtemplateproduct($pid) {
	$mydb	=	new  db();
	
	$query	=	"SELECT tp.*, t.`name`, t.`example_text` FROM `template_product` tp LEFT JOIN `templates` t ON tp.`t_id` = t.`t_id`  WHERE tp.`product_id` = '$pid' ORDER BY tp.`t_id` DESC";
	
	if ($mydb->query($query)&&$mydb->numRows()) {
		$temprodarray	=	$mydb->getFullArray();	
		foreach ($temprodarray as $eachtemplate) {
			$templatebreakdown	.=	($templatebreakdown)?"&&{$eachtemplate['t_id']},{$eachtemplate['name']},{$eachtemplate['example_text']},{$eachtemplate['page']},{$eachtemplate['id']}":"{$eachtemplate['t_id']},{$eachtemplate['name']},{$eachtemplate['example_text']},{$eachtemplate['page']},{$eachtemplate['id']}";
		}
		
		return $templatebreakdown;		
	}
	
}

function lockobj($id) {
	$mydb	=	new db();
	$query	=	"SELECT `po_id` FROM `page_objects` WHERE `po_id` = '$id' AND `lock` = '0';";
	
	if ($mydb->query($query)&&$mydb->numRows()) {
		$query	=	"UPDATE `page_objects` SET `lock` = '1' WHERE `po_id` = '$id' LIMIT 1;";
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			return '#ff0000';
		}
		
	}
	else 
	{
	$query	=	"UPDATE `page_objects` SET `lock` = '0' WHERE `po_id` = '$id' LIMIT 1;";
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			return '#eee';
		}
	}
	
	
	
}

function rtp($id)
{
	$mydb	=	new db();
	$query	=	"DELETE FROM `template_product` WHERE `id` = '$id'";
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		return true;
	}
	
	
}

function sonoftimage($pid,$pg) {
	$mydb	=	new db();
	$query	=	"SELECT `t_id` FROM `template_product` WHERE `page` = '$pg' AND `product_id` = '$pid';";
	
	if ($mydb->query($query)&&$mydb->numRows()) {
		$tidArray	=	$mydb->getArray();
		return $tidArray['t_id'];
	}
	
}

function deletetemp($tid) {
	$mydb	=	new  db();
	$query	=	"SELECT `name` FROM `templates` WHERE `t_id` = '$tid';";
	if ($mydb->query($query)&&$mydb->numRows()) {
		$tname	=	$mydb->getArray();
		$tname	=	preg_replace('/ /','',$tname['name']);
		unlink('templates/'.$tname.'.png');
		unlink('templates/'.$tname.'_thmb.png');
	
	
	$query	=	"DELETE FROM `templates` WHERE `t_id` = '$tid';";
	if ($mydb->query($query)&&$mydb->getAffRows()) {
		$query	=	"DELETE FROM `template_product` WHERE `t_id` = '$tid'";
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			
		}
		
		return true;
	}
	}
}

function fontcsv() {
		$mydb	=	new db();
		$query	=	"SELECT `font_id`, `name` FROM `font_family_objects`;";
		
		
		if ($mydb->query($query)&&$mydb->numRows()) {
			$fcvArray	=	$mydb->getFullArray();
			foreach ($fcvArray as $each) {
				$csvbreakdown	.=	($csvbreakdown)?','.$each['name'].'&'.$each['font_id']:$each['name'].'&'.$each['font_id'];
			}
			return $csvbreakdown;
		}
		
	}
	
	
function objproperties($poid,$tid) {
	$mydb	=	new db();
	$query	=	"SELECT `font_id`, `font_size`, `font_color` FROM `page_objects` WHERE `po_id` = '$poid' AND `t_id` = '$tid'";
	if ($mydb->query($query)&&$mydb->numRows()) {
		$fontpropertiesArray	=	$mydb->getArray();
		return $fontpropertiesArray['font_id'].','.$fontpropertiesArray['font_size'].','.$fontpropertiesArray['font_color'];
	}
		
}

function checkproduct($name,$id) {
	$mydb	=	new  db();
	$query	=	"SELECT `description` FROM `products` WHERE `description` = '$name'";
	if ($mydb->query($query)&&!$mydb->numRows()) {
		//insert product here
		$name_short	=	explode(' ',$name);
		$query	=	"INSERT INTO `products` (`description`, `description_sh`, `user_id`) VALUES('$name', '{$name_short[0]}', $id)";
		if ($mydb->query($query)&&$mydb->getAffRows()) {
			//mail('sonny@feburman.co.uk','New product request',$name);
			return $mydb->getInsID();
		}
		
	}
	else 
	{
		return 'sorry the product already exists';
	}
	
}

function getproductpagecount($pid) {
	$mydb	=	new  db();
	$query	=	"SELECT `number_of_pages` FROM `products` WHERE `product_id` = '$pid' AND `number_of_pages` IS NOT NULL;";
	mail('sonnyfraikue@gmail.com', 'getproductpagecount', $query);
        if ($mydb->query($query)&&$mydb->numRows()) {
		$pagecountArray	=	$mydb->getArray();
		return $pagecountArray['number_of_pages'];
	}
	else
	{
		return 'fail';
	}
	
	
}



function sizecheck($tid) {
	$mydb	=	new  db();
        $query  =       "SELECT `file_name`, `width`, `height`, `upload_id` FROM `uploads` WHERE `template_id` = '$tid' ORDER BY `upload_id` DESC LIMIT 1;";
        $time   = time();    
//mail('sonnyfraikue@gmail.com', 'sizecheck call', $query);
        if($mydb->query($query)&&$mydb->numRows())
        { 
            //ensuring file ratio is correct
            $sizearray  =   $mydb->getArray();
            $ratio      = ($sizearray['width'] < $sizearray['height'])?round($sizearray['width']/$sizearray['height'],1):round($sizearray['height']/$sizearray['width'],1);
            if($ratio <> 0.7){
            return $sizearray['width'].','.$sizearray['height'].','.$ratio.','.$sizearray['file_name'].'?t='.$time;
            }
        }
}


function checktemplate($value)
{
	$mydb	=	new  db();
	$query	=	"SELECT `name` FROM `templates` WHERE `name` = '$value'";
	if ($mydb->query($query)&&$mydb->numRows()) {
		return 1;
	}
	
}



	
}

?>