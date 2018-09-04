<?php
//print_r($_REQUEST);exit;
set_time_limit(-1);
ini_set("memory_limit", "-1");
ini_set('max_execution_time', 0);
ini_set('post_max_size', '-1');
$site_url_link = "https://www.undergroundsafety.com/";
$dir_path = '/var/chroot/home/content/a2pewpnaspod04_data05/36/41243936/html/';
require ($dir_path.'mpdf60/mpdf.php');  
$dispReport = "";

//$mpdf = new mPDF('', 'A4', '', '', 2, 2, 53, 53, 3, 3);
$mpdf = new mPDF('', 'A4', '', '', 8, 8, 8, 8, 8, 8);
//$mpdf=new mPDF('c','A4','','',2,2,58,118,3,3); 
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Underground pdf");
$mpdf->SetAuthor("Underground Safety");
$mpdf->SetDisplayMode('fullpage');
$mpdf->cacheTables = true;
$mpdf->simpleTables = true;
$mpdf->packTableData = true;

$soil = $_REQUEST['soil'];
$depth = $_REQUEST['depth'];
$width = $_REQUEST['width'];
$length = $_REQUEST['length'];
$angle1 = $_REQUEST['angle1'];
$angle2 = $_REQUEST['angle2'];
$angle3 = $_REQUEST['angle3'];
$volume1 = $_REQUEST['volume1'];
$volume2 = $_REQUEST['volume2'];
$width1 = $_REQUEST['width1'];
$width2 = $_REQUEST['width2'];
$protective = $_REQUEST['protective'];
$reduction = $_REQUEST['reduction'];
$devType = $_REQUEST['devType'];
$devId = $_REQUEST['devId'];



function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

// now try it
$ua=getBrowser();
//$yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
$platform = $ua['platform'];
$device_type = $ua['name'];

require_once 'wp-config.php';
global $wpdb;
$file_name = "ugs_".date('Ymdhis').".pdf";
$fullname = $site_url_link."pdf_files/".$file_name;
$sql = "INSERT INTO wp_2c9wxhyvkr_pdf (device_type, device_id, pdf, created_date) 
VALUES ('$device_type', '$platform','$fullname' , CURRENT_TIMESTAMP)";
$wpdb->query($sql);



$drawing_link = $site_url_link."Resource/SlopeCalculator/fetchImage.php?obtuse=true&typeofsoil=$soil&depth=$depth&width=$width&length=$length";

$dispReport .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PDF</title>
</head>
<body style="font-size:8pt">
<div class="container" style="font-size:8pt">
  <div class="header">
  <table width="100%" border="1" bgcolor="#000000">
	<tr>
		<td align="left"><img width="200" src="'.$site_url_link.'Images/logo01.jpg" alt="" /></td>
		<td align="center"><img width="100" src="'.$site_url_link.'Images/logo02.jpg" alt="" /></td>
		<td align="right"><img width="200" src="'.$site_url_link.'Images/logo03.jpg" alt="" /></td>
	</tr>
  </table>
    
    <div class="clear"></div>
    <div class="tagline"></div>
    <table class="address" width="100%" border="0" style="font-size:8pt" cellpadding="5" cellspacing="0">
      <tr>
        <td width="33%" valign="top"><strong>Memphis, TN (901) 346-5800<strong></td>
        <td width="33%" valign="top"><strong>North Little Rock, AR (501) 955-3800<strong></td>
        <td width="34%" align="right"><strong>Nashville, TN (615) 291-9980<br>www.undergroundsafety.com<strong></td>
      </tr>
    </table>
  </div>
  <div class="content">
    <div class="heading">
      <h2 style="font-family:MyriadProBoldIt regular; font-size:10pt; margin:0; padding:0;">The Slope Calculator Results</h2>
    </div>
    <div class="desc">
      <h3 style="font-family:MyriadProBoldIt regular; font-size:8pt; margin:0; padding:0; line-height:2;">Should I slope, or should I use trench shoring & shielding equipment?</h3>
      <p style="font-size:8pt; margin:0; padding:0; line-height:1;">Below are the results from the excavation dimensions that you entered into <span style="font-family:MyriadProBoldIt regular"><b>The Slope Calculator.</b></span> You can create as many variations as you like, simply by changing any of the dimensions in the Calculator, then generating a new PDF. As always, the safety of your employees is the most important consideration.</p>
    </div>
    <div class="clear"></div>
    <div class="excavationdesc">
      <div class="leftdiv">
	  <br />
        <div class="margintop"> <b style="font-family:MyriadProBoldIt regular">Excavation Description:</b><br />
          1. Type of soil: <b style="font-family: MyriadProBoldIt regular">'.ucfirst($soil).'</b><br />
          2. Depth of trench (See Note 1 below): <b style="font-family:MyriadProBoldIt regular">'.$depth.'</b> feet<br />
          3. Width of trench, at the bottom: <b style="font-family:MyriadProBoldIt regular">'.$width.'</b> feet<br />
          4. Length of trench: <b style="font-family:MyriadProBoldIt regular">'.$length.'</b> feet </div>
        <div class="clear"></div>
          <table width="100%" border="0" cellpadding="5" cellspacing="0">
		  	<tr>
			<td align="center">
	        <div class="graph"><b style="font-family:MyriadProBoldIt regular">Typical Drawing:</b><br />
            <img height="380px" src="'.$drawing_link.'" style="border:groove;" alt="Drawing will display here" />
            <br />
          	For illustration purposes only. Not to scale.
		  </div>
		  </td>
		  </tr>
          </table>
      </div>
    </div>
    <div class="clear"></div>
    <div class="comparisondata"> <span style="font-family:MyriadProBoldIt regular"><strong>Comparison Data:</strong></span>
      <table class="comparisiondatatbl" width="100%" border="1" cellspacing="0" cellpadding="5">
        <tr>
          <td style="border-right:none;" width="115">&nbsp;</td>
          <td class="bg" style="border-right:solid 1px #ffffff;"><strong>Sloping Results</strong><br />
            (See Note 2 below)</td>
          <td class="bg" style="border-right:none;"><strong>Shielding Results</strong></td>
        </tr>
        <tr>
          <td><b style="font-family:MyriadProBoldIt regular">Slope Angle</b></td>
          <td><b style="font-family:MyriadProBoldIt regular">'.$angle2.'  &#176</b></td>
          <td style="border-right:none;"><b style="font-family:MyriadProBoldIt regular">'.$angle3.'  &#176</b></td>
        </tr>
        <tr>
          <td><b style="font-family:MyriadProBoldIt regular">Volume to be removed, stored, and replaced</b></td>
          <td><b style="font-family:MyriadProBoldIt regular">'.$volume1.' cubic yards</b><br />
            (the volume inside the dotted lines in the drawing)</td>
          <td style="border-right:none;"><b style="font-family:MyriadProBoldIt regular">'.$volume2.' cubic yards</b><br />
            (the volume inside the dark orange area the middle of the drawing)</td>
        </tr>
        <tr>
          <td><b style="font-family:MyriadProBoldIt regular">Width of trench,at top</b></td>
          <td><b style="font-family:MyriadProBoldIt regular">'.$width1.' feet</b><br />
            (including the OSHA-required 2-foot setback on each side for spoil and equipment)</td>
          <td style="border-right:none;"><b style="font-family: MyriadProBoldIt regular">'.$width2.' feet</b><br />
            (including the OSHA-required 2-foot setback on each side for spoil and equipment);</td>
        </tr>
      </table>
      <p class="margintop" style="font-size:8pt;">If you choose to use a manufactured protective system (for example, a trench shield, hydraulic shoring, or a slide rail system), only <b style="font-family: "MyriadProBoldIt regular""> '.$protective.' cubic yards</b> of material will have to be excavated, temporarily stored, and replaced. <b style="font-family: "MyriadProBoldIt regular"">That\'s a '.$reduction.'% reduction from sloping.</b> Also, surface restoration costs will be lower, and the overall job will take less time.</p>
    </div>
    <div class="clear"></div>
	<hr/>
    <div class="noteslist">
    <b style="font-family:MyriadProBoldIt regular">Note 1</b>  When sloping at depths greater than 20 feet, OSHA says that a Registered Professional Engineer, licensed in the state where the work is to be performed, must determine the proper angle of slope. See OSHA\'s Subpart P, Excavations, for  requirements.<br />  
    <b style="font-family:MyriadProBoldIt regular">Note 2</b> - These are the MINIMUM SLOPING REQUIREMENTS, including the OSHA-required spoil and equipment set-backs. Additional material may need to be excavated, as determined by the onsite Competent Person, to provide a safe workplace.<br /> 
    <b style="font-family:MyriadProBoldIt regular">Note 3</b> - Construction techniques and equipment usage must be in accordance with all governmental regulations and  manufacturer\'s instructions. All orders placed with Trench Safety, including training, are subject to the terms, conditions, and  warranty limitations in Trench Safety\'s rental and sales agreements. </div>
    <div class="copyrights">COPYRIGHT &copy; 2015 UNDERGROUNDSAFETY AND SUPPLY, INC.</div>
  </div>
</div>
</body>
</html>';

//echo $dispReport;exit;

$mpdf->WriteHTML($dispReport);
$mpdf->Output($dir_path."pdf_files/".$file_name, "F");
echo $site_url_link."pdf_files/".$file_name;
//$mpdf->Output();
?>
