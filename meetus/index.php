<?php

	require_once( $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php' );

	/* Set active menu item */
	$GLOBALS['config']->setParam('activeMenuItem', 'meetus' );

	require_once( "head.php" ); // includes body tag

	require_once( "header.php" );

?>

<div class="container">

	<div class="row">

		<div id="meetUsWrapper" class="wrapper highlight-layer" style="background-color: #000; padding: 30px 30px; min-height: 40em;">

			<h1>Meet The Team</h1>

			<hr>

			<div class="row">

				<div class="col-md-3">

					<img src="" />

				</div>

				<div class="col-md-9">

					<h2>Brian Snyder - Owner</h2>

					<p class="help-text text-muted"></p>

				</div>

			</div>

			<div class="row">

				<div class="col-md-3">

					<img src="" />

				</div>

				<div class="col-md-9">

					<h2>Chris Caldwell - Director of Marketing</h2>

					<p class="help-text text-muted"></p>

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
