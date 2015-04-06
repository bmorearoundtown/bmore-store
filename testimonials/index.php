<?php

	require_once( $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php' );

	/* Set active menu item */
	$GLOBALS['config']->setParam('activeMenuItem', 'testimonials' );

	require_once( "head.php" ); // includes body tag

	require_once( "header.php" );

?>

<div class="container">

	<div class="row">

		<div id="testimonialsWrapper" class="wrapper highlight-layer" style="background-color: #000; padding: 30px 30px; min-height: 40em; margin-top: 10px;">

			<h1>Testimonials</h1>

			<hr>

			<div class="row">

        <div class="col-md-6">
          <div class="well"></div>
        </div>

        <div class="col-md-6">
          <div class="well">asdasdas</div>
        </div>

			</div>

			<div class="row">

        <div class="col-md-6">
          <div class="well"></div>
        </div>

        <div class="col-md-6">
          <div class="well"></div>
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
