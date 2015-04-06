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
          				<div class="well well-lg" style="background-color: #222222;">
					<blockquote>
  						<p>I will definitely be joining for future events. I was very impressed by the organization, people, and trip as a whole.  I've been telling everyone about you guys...so glad my friend invited me to go!</p>
  						<footer>Courtney M</footer>
					</blockquote>
					</div>
        			</div>

				<div class="col-md-6">
          				<div class="well well-lg" style="background-color: #222222;">
					<blockquote>
  						<p>Brian,
just wanted to again thank you for all your hard work....it truly showed how much time and effort you put into your trips....
Ravens Roost 50 appreciates being able to sit together at the game and I am confident you will be first on our list for future trips.</p>
  						<footer>Karen H</footer>
					</blockquote>
					</div>
        			</div>

			</div>

			<div class="row">

				<div class="col-md-6">
          				<div class="well well-lg" style="background-color: #222222;">
					<blockquote>
  						<p>Thanks for an amazing experience. We rode on bus #2 and we were absolutely thrilled from beginning to rainy end, especially myself being a RAVENS FAN. The people were ultra friendly, the drinks and food were intoxicating along with an excellent DJ. This trip was well organized which is a rarity. And even though my companions team (SHITSBURGH) did not WIN and are now couch potatoes he was extremely ESTATIC about picking up a new Steelers jersey for their collection. Yes! We look forward to traveling with you again.

P.S. We will be referring your services!
</p>
  						<footer>Annie & Derek</footer>
					</blockquote>
					</div>
				</div>
				<div class="col-md-6">
          				<div class="well well-lg" style="background-color: #222222;">
					<blockquote>
  						<p>The only way to celebrate before an NFL playoff game is with Brian from Bmore Around Town! Great food, beverage and company! Thank your for taking such good care of the players' families. You are awesome!
</p>
  						<footer>Judd McPherson (Brandon Williams Father)</footer>
					</blockquote>
					</div>
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
