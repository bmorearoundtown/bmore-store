<?php
	
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/_includes/config.php' );

	/* Set active menu item */
	$GLOBALS['config']->setParam('activeMenuItem', 'photos' );

	/*--- head and header includes ---*/
	
	require_once( "head.php" ); // includes body tag
	
	require_once( "header.php" );
	
?>

<div class="container">

	<div id="photoGallery" class="highlight-layer" style="min-height: 60em; background-color: #000; padding: 20px; margin-top: 10px;">
		
		<h1>
			Bmore Around Town Photo Gallery
			
			<div id="returnURLLoader" class="pull-right"></div>
		</h1>
		
		<hr>
		
		<div class="row">
			
			<div class="col-md-12">
				
				<div class="galleryContentLoader row"></div>
			
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

<script>


	$.urlParam = function( name ){
	    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	    if (results==null){
	       return null;
	    }
	    else{
	       return results[1] || 0;
	    }
	};

	$.addJSFileDynamically = function( src ){

		// create js file
		var fileref = document.createElement('script');
		
		fileref.setAttribute("type","text/javascript");
		
		fileref.setAttribute("src", src );

		if ( typeof fileref !== "undefined" ){
	 		document.getElementsByTagName( "head" )[0].appendChild( fileref )
		}
		
		return true;
	};
	
	function viewAlbumList( albumListing ){

		var imagePadding = 10;
		var imagesPerRow = PHOTO_GALLERY.getImagesPerRow();
		var width = ( $( '.galleryContentLoader' ).width() / imagesPerRow ) - ( ( imagesPerRow - 1 ) * imagePadding ); // last param is padding which is 10px per side by default
		var height = 300;
	
		$( albumListing.feed.entry ).each( function( index, item ){

			var title	= item.title.$t;
			var thumb	= item.media$group.media$thumbnail[0].url;
			var id_begin	= item.id.$t.indexOf('albumid/') + 8;
			var id_end		= item.id.$t.indexOf('?');
			var id		= item.id.$t.slice(id_begin, id_end);
			var href	= window.location.href + '?albumid=' + id;
			var d			= item.published.$t;
			var date	= d.substr(8,2) + '-' + d.substr(5,2) + '-' + d.substr(0,4); 

			var $photos = $( "#photoGallery .galleryContentLoader" )
							.append( "<div class='image-container col-xs-12 col-sm-6 col-md-4'>"+
										"<a title=" + title + " href=" + href + ">" +
											"<img src=" + thumb + " alt=" + title + " />" +
											"<h4>" + title + "</h4>" +
											"<h5>" + date + "</h5>" +
										"</a>" + 
										"</div>" );
		});
		
		return;
	}

	function viewPhotoList( photoListing ){

		var album = photoListing.feed.title.$t;
		var href  = window.location.href + '?albumid=';

		$( "#returnURLLoader" ).html( "<a href='/photos/index.php' title='return to album listing'><small><i class='fa fa-arrow-left cushion-right'></i>Return to album listing</small></a>");
		
		$( photoListing.feed.entry ).each( function( index, item ) {

			var title	= item.title.$t;
			var link	= item.media$group.media$content[0].url;
			var size	= item.media$group.media$content[0].width;
			var thumb	= item.media$group.media$thumbnail[0].url;
			
			var id_begin = item.id.$t.indexOf('albumid/')+8;
			var id_end	= item.id.$t.indexOf('?');
			var id		= item.id.$t.slice(id_begin, id_end);

			var $photos = $( "#photoGallery .galleryContentLoader" )
				.append( "<div class='image-container col-xs-12 col-sm-6 col-md-3'>"+
							"<a class='blow-it-up' title=" + title + " href=" + link + ">" +
								"<img src=" + thumb + " alt=" + title + " />" +
								"<h4>" + title + "</h4>" +
							"</a>" + 
							"</div>" );

		});

		$( ".blow-it-up" ).colorbox({rel:'blow-it-up',transition:"fade"});
		
		return;
	}
	
	var PHOTO_GALLERY = (function(){

		var imagesPerRow = 3;		
		var username = "bmorearoundtown";
		var thumbsize = parseInt( $( '.galleryContentLoader' ).width() / imagesPerRow );
		var imgmax = 800;
		var albumId = $.urlParam( 'albumid' );
		var url = ( albumId ) ? 'http://picasaweb.google.com/data/feed/base/user/'
				+ username + '/albumid/' + albumId
				+'?category=photo&alt=json&callback=viewPhotoList&thumbsize=' + thumbsize 
				+'&imgmax=' + imgmax : 'http://picasaweb.google.com/data/feed/base/user/'
					+ username + '?category=album&alt=json&callback=viewAlbumList&access=public&thumbsize=' 
					+ thumbsize;
		
		function init(){

			var colorBoxSourceURL = "/_assets/_js/global/plugins/jquery.colorbox-min.js";

			// add color box to dom
			$.addJSFileDynamically( colorBoxSourceURL );
			
			$.addJSFileDynamically( url );
			
			return;
		}

		function getImagesPerRow(){
			return imagesPerRow;
		}
		
		return {

			"init": init,
			"getImagesPerRow": getImagesPerRow
		}
		
	}());

	$( function(){

		PHOTO_GALLERY.init();
		
	});
	
</script>

</body>

</html>