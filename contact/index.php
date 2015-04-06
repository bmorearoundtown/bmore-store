<?php

	require_once( $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php' );

	/* Set active menu item */
	$GLOBALS['config']->setParam('activeMenuItem', 'contact' );

	require_once( "head.php" ); // includes body tag

	require_once( "header.php" );

?>

<div class="container">

	<div class="row">

		<div id="contactUsWrapper" class="wrapper highlight-layer" style="background-color: #000; padding: 30px 30px; min-height: 40em; margin-top: 10px;">

			<h1>Contact Us</h1>

			<hr>

			<div class="row">

				<div class="col-md-12">

					<h2>General Information</h2>

					<p><label>Email:</label> <a href="mailto:Info@bmorearoundtown.com" style="font-size: 1.2em;">Info@bmorearoundtown.com</a></p>

					<p><label>Tel:</label> 443-865-5935</p>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<h2>Brian Snyder - Owner</h2>

					<p><label>Email:</label> <a href="mailto:Brian@BMOREAroundTown.com" style="font-size: 1.2em;">Brian@bmorearoundtown.com</a></p>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<h2>Chris Caldwell - Director of Marketing</h2>

					<p><label>Email:</label> <a href="mailto:Chris@BMOREAroundTown.com" style="font-size: 1.2em;">Chris@bmorearoundtown.com</a></p>

				</div>

			</div>

			<div class="row">

				<div class="col-md-12">

					<h2>Scott Mitchell - Site Administrator</h2>

					<p><label>Email:</label> <a href="scottymitch88@gmail.com" style="font-size: 1.2em;">scottymitch88@gmail.com</a></p>

				</div>

			</div>

		</div>

	</div>

</div>

<div id="newsletterWrapper" class="wrapper">

	<div class="container-fluid">

		<?php include("newsletter.php"); ?>

	</div>

</div>

<?php require_once("footer.php"); ?>

</body>

</html>
