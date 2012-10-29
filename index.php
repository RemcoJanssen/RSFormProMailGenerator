<?php
require_once('../configuration.php');
$config = new JConfig();

// Define constants to connect to database
$dbname = $config->db;
$dbhost = $config->host;
$dbuser = $config->user;
$dbpass = $config->password;
$dbprefix = $config->dbprefix;
$task = $_POST["task"];
$formid = $_POST["formid"];
$tabletag = $_POST["tabletag"];
$tablewidth = $_POST["tablewidth"];
$tdcssc = $_POST["tdcssc"];
if ($tdcssc == 1) {
	$tdstylec = ' style="'.$_POST["tdstylec"].'"';
} else {
	$tdstylec = '';	
}
$tdcssv = $_POST["tdcssv"];
if ($tdcssv == 1) {
	$tdstylev = ' style="'.$_POST["tdstylev"].'"';
} else {
	$tdstylev = '';	
}
$tablewidth = $_POST["tablewidth"];
$restart = $_SERVER['PHP_SELF'];

mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname);
$query = "select straat,woonplaats from postcode where postcode = '$postcode'";
// Run the Query
$result = mysql_query($query);
while($row = mysql_fetch_array($result)){
$table_data[]= array("straat"=>$row['straat'],"woonplaats"=>$row['woonplaats']);
}		
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>RSForm!Pro Mail Generator</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<!-- Le styles -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/prettify.css" rel="stylesheet">
		<style type="text/css">
		.hero-unit {padding:20px 30px;margin-bottom: 20px;}
		.hero-unit h1 {font-size:42px;}
		body {padding-top: 20px;}
		</style>
		
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<!-- Le javascript -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/prettify.js"></script>
	</head>
	<body onload="prettyPrint()">
		<div class="container">
			<div class="hero-unit">
				<h1>RSForm!Pro E-Mail Generator</h1>
				<p>This simple webapplication allows you to generate E-Mail templates for your RSForm!Pro forms.<br/>Very handy for creating Admin Mails and User Mails.</p>
				<p>This tool was built by <a target=_blank href="http://about.me/renekreijveld">René Kreijveld</a> using Twitter Bootstrap and Google Prettify.</p>
			</div>
			<?php
			switch ($task) {
				case "process":
					$query = "SELECT ".$dbprefix."rsform_properties.PropertyValue FROM ".$dbprefix."rsform_properties INNER JOIN ".$dbprefix."rsform_components ON ".$dbprefix."rsform_properties.ComponentId = ".$dbprefix."rsform_components.ComponentId INNER JOIN ".$dbprefix."rsform_forms ON ".$dbprefix."rsform_components.FormId = ".$dbprefix."rsform_forms.FormId WHERE ".$dbprefix."rsform_properties.PropertyName = 'NAME' AND ".$dbprefix."rsform_components.FormId = '".$formid."' ORDER BY ".$dbprefix."rsform_components.Order";
					$result = mysql_query($query);
					echo '<h2>Here is your e-mail HTML code:</h2>';
					if ($formid == "-1") {
						echo '<div class="alert alert-error"><strong>Whoops</strong> you forgot to choose a form ... Please retry!</div>';
					} else {
						echo '<pre class="prettyprint  linenums">';
						if ($tabletag == 1) {
							echo htmlspecialchars('<table style="width:'.$tablewidth.'px;">').'<br/>'.htmlspecialchars('  <tbody>').'<br/>';
						}
						while($row = mysql_fetch_array($result)) {
							if ($tabletag == 1) {
								echo htmlspecialchars('    ');
							}
							echo htmlspecialchars('<tr>').'<br/>';
							if ($tabletag == 1) {
								echo htmlspecialchars('      ');
							} else {
								echo htmlspecialchars('  ');
							}
							echo htmlspecialchars('<td'.$tdstylec.'>{'.$row['PropertyValue'].':caption}</td>').'<br/>';
							if ($tabletag == 1) {
								echo htmlspecialchars('      ');
							} else {
								echo htmlspecialchars('  ');
							}
							echo htmlspecialchars('<td'.$tdstylev.'>{'.$row['PropertyValue'].':value}</td>').'<br/>';
							if ($tabletag == 1) {
								echo htmlspecialchars('    ');
							}
							echo htmlspecialchars('</tr>').'<br/>';
						}
						if ($tabletag == 1) {
							echo htmlspecialchars('  </tbody>').'<br/>'.htmlspecialchars('</table>').'<br/>';
						}
						echo '</pre>';
					}
					echo '<a class="btn btn-success" href="'.$restart.'">Restart</a>';
					break;
				default:?>
						<form class="form-horizontal" method="post">
							<div class="row">
								<div class="span12">
									<h2>Choose your form and settings:</h2>
									<div class="control-group">
										<label class="control-label">Form</label>
										<div class="controls">
											<select name="formid" id="formid">
												<option value="-1">--- Choose Form ---</option>
												<?php
												$query = 'select FormId,FormName from '.$dbprefix.'rsform_forms order by FormName';
												$result = mysql_query($query);
												while($row = mysql_fetch_array($result)) {
													echo '<option value="'.$row['FormId'].'">'.$row['FormName'].'</option>';
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span5">
									<div class="control-group">
										<label class="control-label">
											Add td css caption<br/>
											<a class="btn btn-mini btn-danger" data-placement="top" data-content="Add CSS code to the td that displays the form field <em>caption</em>?" rel="popover" href="#" data-original-title="Add td css <em>caption</em>">Help</a>
										</label>
										<div class="controls">
											<label class="radio">
												<input type="radio" name="tdcssc" id="tdcssc1" value="1">Yes
											</label>
											<label class="radio">
												<input type="radio" name="tdcssc" id="tdcss2c" value="0">No
											</label>
										</div>
									</div>
								</div>
								<div class="span7">
									<div class="control-group">
										<label class="control-label">
											TD css caption<br/>
											<a class="btn btn-mini btn-danger" data-placement="top" data-content="Add some CSS code to be applied to the td that displays the form field <em>caption</em>." rel="popover" href="#" data-original-title="TD css <em>caption</em>">Help</a>
										</label>
										<div class="controls">
											<input name="tdstylec" type="text" id="tdstylec" value=""><br/>
											<em>Example:</em> <strong>width:200px;font-weight:bold;</strong>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span5">
									<div class="control-group">
										<label class="control-label">
											Add td css value<br/>
											<a class="btn btn-mini btn-danger" data-placement="top" data-content="Add CSS code to the td that displays the form field <em>value</em>?" rel="popover" href="#" data-original-title="Add td css <em>value</em>">Help</a>
										</label>
										<div class="controls">
											<label class="radio">
												<input type="radio" name="tdcssv" id="tdcssv1" value="1">Yes
											</label>
											<label class="radio">
												<input type="radio" name="tdcssv" id="tdcssvc" value="0">No
											</label>
										</div>
									</div>
								</div>
								<div class="span7">
									<div class="control-group">
										<label class="control-label">
											TD css value<br/>
											<a class="btn btn-mini btn-danger" data-placement="top" data-content="Add some CSS code to be applied to the td that displays the form field <em>value</em>." rel="popover" href="#" data-original-title="TD css <em>value</em>">Help</a>
										</label>
										<div class="controls">
											<input name="tdstylev" type="text" id="tdstylev" value=""><br/>
											<em>Example:</em> <strong>width:400px;font-size:12px;</strong>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span5">
									<div class="control-group">
										<label class="control-label">
											Add table tag<br/>
											<a class="btn btn-mini btn-danger" data-placement="top" data-content="Add the table and tbody tags to the code or just display the table rows?" rel="popover" href="#" data-original-title="Add table tag">Help</a>
										</label>
										<div class="controls">
											<label class="radio">
												<input type="radio" name="tabletag" id="tabletag1" value="1">Yes
											</label>
											<label class="radio">
												<input type="radio" name="tabletag" id="tabletag2" value="0">No
											</label>
										</div>
									</div>
								</div>
								<div class="span7">
									<div class="control-group">
										<label class="control-label">
											Form width<br/>
											<a class="btn btn-mini btn-danger" data-placement="top" data-content="The width of the form to use. Only relevant if <em>add table tag</em> is set to <em>yes</em>." rel="popover" href="#" data-original-title="Form width">Help</a>
										</label>
										<div class="controls">
											<input class="input-mini" name="tablewidth" type="text" id="tablewidth" value="600"> px
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="span12">
									<div class="control-group">
										<div class="controls">
											<button type="submit" class="btn btn-primary">Submit</button>
										</div>
									</div>
								</div>
							</div>
							<input name="task" type="hidden" value="process">
						</form>
			<?php
			}
			?>
			<hr>
			<footer>
				<p>&copy; René Kreijveld, <?php echo date('Y');?></p>
			</footer>
		</div>	
		<script type="text/javascript">
			$("[rel=popover]").popover();
		</script>
	</body>
</html>
