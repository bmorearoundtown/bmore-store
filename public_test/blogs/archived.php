<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BMORE Around Town | Dedicated to Bringing you the Best social Event Activities in Baltimore | Calendar</title>
<?php include("../_includes/functions.php"); ?>


<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
			google.load("jquery", "1.3");
		</script>

<script src="../_assets/_js/validate.js" type="text/javascript" charset="utf-8"></script>


<script type="text/javascript">
		$(document).ready(function() {
			$("#private_event").validate({
				submitHandler:function(form) {
					SubmittingForm();
				},
				rules: {
					firstname: "required",		// simple rule, converted to {required:true}
					lastname: "required",
					phone: "required",
					email: {				// compound rule
						required: true,
						email: true
					},
					message: {
						required: false
					}
				},
				messages: {
					comment: "Please enter your message."
				}
			});
		});

		jQuery.validator.addMethod(
			"selectNone",
			function(value, element) {
				if (element.value == "none")
				{
					return false;
				}
				else return true;
			},
			"Please select an option."
		);

		$(document).ready(function() {
			$("#fvujq-form2").validate({
				submitHandler:function(form) {
					SubmittingForm();
				},
				rules: {
					sport: {
						selectNone: true
					}
				}
			});
		});
	</script>
</head>

<body>

<div class="min-width">
	
    <div id="container">
    	
        <?php include("../_includes/navigation.php"); ?>
        
        <div id="contentcontainer">
        	
            <?php include("../_includes/sidebar.php"); ?>
            
            <div id="content">
                
                	<h1>Private Events</h1>
    <p>We at BMORE Around Town not only take pride in hosting our own social events, but offer our expertise in event planning to you as well. We can help you plan any occasion! Events include, but are not limited to: Bachelor/Bachelorette Parties, Birthday Parties, Reunions, Holiday Parties, Happy Hours, and Office Parties.</p>
    
    
                        
                        <form method="post" name="private_event" id="private_event" action="http://www.ipower.com/scripts/formemail.bml">
                           <input type="hidden" name="my_email" value="register@b-morearoundtown.com">
                           <input type="hidden" name="thankyou_url" value="http://www.bmorearoundtown.com/thankyou"> 
                            <input type="hidden" name="eventtype" value="private event">
                           
                            <input name="firstname" type="text" id="firstname" value="First Name *" />
                        <br />
                            <input name="lastname" type="text" id="lastname" value="Last Name *" />
                        <br />
                        <input name="email" type="text" id="email" value="Email *" />
                        <br />
                        <input name="address" type="text" id="address" value="Address" />

                      <br />
                      <input name="city" type="text" id="city" value="City" />
                      <br />
                      <input name="state" type="text" id="state" value="State" />
                      <br />
                      <input name="zip" type="text" id="zip" value="Zip" />
                      <br />
                            
                            <input name="phone" type="text" id="phone" value="Phone *" />
                      <br />

                      <textarea></textarea>
                      <br />
                            <button type="submit">Submit</button>
                        </form>

                
            </div>
            
            <div class="clear"></div>
            
        </div>
        
        <?php include("../_includes/footer.php"); ?>
        
    </div>

</div>


</body>
</html>