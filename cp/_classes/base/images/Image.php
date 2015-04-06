<?
class Image {

	const COMBINE_BELOW = 1;
	const COMBINE_ABOVE = 2;
	
	const PALLETED = 10;
	const TRUE_COLOR = 11;

	protected $filepath = "";
	protected $type = 0;
	protected $image = null;
	
	protected $width = 0;
	protected $height = 0;
	
	protected $valid = false;
	
	function __construct($filepath = "", $type = 0) {
	
		$this->filepath = $filepath;
		$this->type = $type;
		
		if (strlen(trim($filepath))) {
		
			if (!$this->type) {
		
				$temp = getimagesize($this->filepath);
				$this->type = $temp[2];
				
			}
		
			switch ($this->type) {
			
				case IMAGETYPE_GIF:
					$this->image = imagecreatefromgif($this->filepath);
					break;
					
				case IMAGETYPE_JPEG:
					$this->image = imagecreatefromjpeg($this->filepath);
					break;
					
				case IMAGETYPE_PNG:
					$this->image = imagecreatefrompng($this->filepath);	
					break;
					
				case IMAGETYPE_BMP:
					$this->image = imagecreatefromwbmp($this->filepath);
					break;
					
			}
					
		}
		
	}
	
	function __destruct() {
		$this->destroy();
	}
	
	function setType($type = 0) {
	
		if ($type) {
			$this->type = $type;
		} else {
			$temp = getimagesize($this->filepath);
			$this->type = $temp[2];
		}
			
		switch ($this->type) {
			
			case IMAGETYPE_GIF:
				$this->image = imagecreatefromgif($this->filepath);
				break;
				
			case IMAGETYPE_JPEG:
				$this->image = imagecreatefromjpeg($this->filepath);
				break;
				
			case IMAGETYPE_PNG:
				$this->image = imagecreatefrompng($this->filepath);	
				break;
				
			case IMAGETYPE_BMP:
				$this->image = imagecreatefromwbmp($this->filepath);
				break;
		
		}
	
	}
		
	function create($width, $height, $type = Image::PALLETED) {
	
		$this->width = $width;
		$this->height = $height;

		if ($type == Image::PALLETED) {
			$this->image = imagecreate($this->width, $this->height);
		} else {
			$this->image = imagecreatetruecolor($this->width, $this->height);
		}
	
	}
		
	function resizeToWidth($tgtX) {
	
		$srcX = imagesx($this->image);
		$srcY = imagesy($this->image);
	
		$srcAspect = $srcX / $srcY;
	
		$tgtY = $tgtX * (1 / $srcAspect);
	
		$tmpImg = imagecreatetruecolor($tgtX, $tgtY);
		imagealphablending($tmpImg, 1);
	
		imagecopyresampled($tmpImg, $this->image, 0, 0, 0, 0, $tgtX, $tgtY, $srcX, $srcY);
	
		$this->image = $tmpImg;
	
	}
		
	function resizeToHeight($tgtY) {
	
		$srcX = imagesx($this->image);
		$srcY = imagesy($this->image);
	
		$srcAspect = $srcY / $srcX;
	
		$tgtX = $tgtY * (1 / $srcAspect);
	
		$tmpImg = imagecreatetruecolor($tgtX, $tgtY);
		imagealphablending($tmpImg, 1);
	
		imagecopyresampled($tmpImg, $this->image, 0, 0, 0, 0, $tgtX, $tgtY, $srcX, $srcY);
	
		$this->image = $tmpImg;
	
	}
	
	function resize($width, $height) {
	
		$srcX = imagesx($this->image);
		$srcY = imagesy($this->image);
	
		$tmpImg = imagecreatetruecolor($width, $height);
		
		$this->setAlphaBlending(true);
		imagealphablending($tmpImg, true);
		imagecolortransparent($tmpImg, imagecolortransparent($this->image));
	
		imagecopyresampled($tmpImg, $this->image, 0, 0, 0, 0, $width, $height, $srcX, $srcY);
		
		$this->image = $tmpImg;
	
	}
	
	function resizeToMax($intWidth, $intHeight) {
	
		$intOriginWidth = imagesx($this->image);
		$intOriginHeight = imagesy($this->image);
	
		$dblOriginRatio = $intOriginWidth / $intOriginHeight;
		$dblTargetRatio = $intWidth / $intHeight;
		
		if ($dblOriginRatio < $intTargetRatio)
			$this->resizeToHeight($intHeight);
		else
			$this->resizeToWidth($intWidth);
	
	}
	
	function clipTo($width, $height) {
	
		$tmpImg = imagecreatetruecolor($width, $height);
		imagealphablending($tmpImg, true);
		
		imagecopy($tmpImg, $this->image, 0, 0, (imagesx($this->image) - $width) / 2, (imagesy($this->image) - $height) / 2, imagesx($this->image), imagesy($this->image));
		
		$this->image = $tmpImg;
	
	}
	
	function combine($combineWhere, $image, $x = 0, $y = 0, $widthDiff = 0, $heightDiff = 0) {
	
		$image->setAlphaBlending(true);
		$this->setAlphaBlending(true);
		
		switch ($combineWhere) {
		
			case Image::COMBINE_BELOW:
			
				$newImg = imagecreatetruecolor($image->getWidth(), $image->getHeight());
				imagealphablending($newImg, true);
				
				imagecopy($newImg, $image->getImage(), 0, 0, 0, 0, $image->getWidth(), $image->getHeight());
				imagecopyresampled($newImg, $this->image, $x, $y, 0, 0, $this->getWidth(), $this->getHeight(), $this->getWidth() - $widthDiff, $this->getHeight() - $heightDiff);
				$this->image = $newImg;
				
				break;
		
			case Image::COMBINE_ABOVE:
			
				$newImg = imagecreatetruecolor($this->getWidth(), $this->getHeight());
				imagealphablending($newImg, true);
				
				imagecopy($newImg, $this->image, 0, 0, 0, 0, $this->getWidth(), $this->getHeight());
				imagecopyresampled($newImg, $image->getImage(), $x, $y, 0, 0, $image->getWidth() - $widthDiff, $image->getHeight() - $heightDiff, $image->getWidth(), $image->getHeight());
				$this->image = $newImg;
				
				break;
		
		}
	
	}
	
	function setAlphaBlending($blending) {
	
		imagealphablending($this->image, $blending);
	
	}
	
	function getWidth() {
	
		return imagesx($this->image);
	
	}
	
	function getHeight() {
	
		return imagesy($this->image);
		
	}
	
	function fill($hexColor) {
	
		$color = $this->getColor($hexColor);
		
		imagefill($this->image, 0, 0, $color);
	
	}
	
	function fillBehind($color) {
			
		$fillImg = new Image();
		$fillImg->create($this->getWidth(), $this->getHeight(), TRUE_COLOR);
		$fillImg->setAlphaBlending(true);
		$fillImg->fill("#ffffff");
		
		$this->combine(Image::COMBINE_BELOW, $fillImg);
	
	}
	
	function drawRectangle($x, $y, $width, $height, $hexColor) {
	
		$color = $this->getColor($hexColor);
		
		imagefilledrectangle($this->image, $x, $y, $x + $width, $y + $height, $color);
	
	}
	
	function drawLine($startX, $startY, $endX, $endY, $hexColor) {
	
		$color = $this->getColor($hexColor);
	
		imageline($this->image, $startX, $startY, $endX, $endY, $color);
	
	}
	
	function drawBorder($width, $hexColor) {
	
		$maxX = imagesx($this->image) - 1;
		$maxY = imagesy($this->image) - 1;
		
		$color = $this->getColor($hexColor);
	
		// Draw top just line
		imageline($this->image, 0, 0, $maxX, 0, $color);
		
		// Draw left line
		imageline($this->image, 0, 0, 0, $maxY, $color);
		
		// Draw right line
		imageline($this->image, $maxX, 0, $maxX, $maxY, $color);
		
		// Draw bottom line
		imageline($this->image, 0, $maxY, $maxX, $maxY, $color);
	
	}
	
	function exportToBrowser($type = IMAGETYPE_JPEG, $quality = 80) {
	
		switch ($type) {
		
			case IMAGETYPE_JPEG:
				header('Content-Type: image/jpeg');
				imagejpeg($this->image, "", $quality);
				break;
				
			case IMAGETYPE_GIF:
				header('Content-Type: image/gif');
				imagegif($this->image);
				break;
				
			case IMAGETYPE_PNG:
				header('Content-Type: image/png');
				imagepng($this->image);
				break;
				
			case IMAGETYPE_BMP:
				header('Content-Type: image/bmp');
				imagewbmp($this->image);
				break;
				
		}
		
	}
	
	function export($filename, $type = IMAGETYPE_JPEG, $quality = 80) {
	
		switch ($type) {
		
			case IMAGETYPE_JPEG:
				imagejpeg($this->image, $filename, $quality);
				break;
				
			case IMAGETYPE_GIF:
				imagegif($this->image, $filename);
				break;
				
			case IMAGETYPE_PNG:
				imagepng($this->image, $filename);
				break;
				
			case IMAGETYPE_BMP:
				imagewbmp($this->image, $filename);
				break;
				
		}
	
	}
	
	function saveAsGif($filename) {
	
		$this->export($filename, IMAGETYPE_GIF);
		
	}
	
	function destroy() {
	
		@imagedestroy($this->image);
	
	}
	
	function getImage() {
	
		return $this->image;
		
	}
	
	function getColor($hexColor) {
	
		if (strlen($hexColor) == 7 && substr($hexColor, 0, 1) == "#") {
			$hexColor = substr($hexColor, 1);
		} elseif (strlen($hexColor) != 6) {
			return;
		}
		
		$red = hexdec(substr($hexColor, 0, 2));
		$green = hexdec(substr($hexColor, 2, 2));
		$blue = hexdec(substr($hexColor, 4, 2));
		
		return imagecolorallocate($this->image, $red, $green, $blue);
	
	}
	
	function _rgba2c($r, $g, $b, $a = 0) {
	
		$c = 0;
		$c = $c | (($a & 0x7F) << 24);
		$c = $c | ($r << 16);
		$c = $c | ($g << 8);
		$c = $c | ($b);
		
		return $c;
		
	}
	
	function _c2rgba($c) {
	
		$a = ($c >> 24) & 0x7F;
		$r = ($c >> 16) & 0xFF;
		$g = ($c >> 8) & 0xFF;
		$b = $c & 0xFF;
		
		return array($r, $g, $b, $a);
		
	}
	
	function isValidImage() {
	
		$cmd = "/usr/bin/identify -format \"%m\" $this->filepath";
		
		exec($cmd, $output, $result);
		
		if ($result) {
			return false;
		} else {
			return true;
		}
		
	}
	
	function convertTo($type, $newFilename, $options = '') {
	
		chdir(dirname($this->filepath));
		
		if (file_exists(dirname($this->filepath) . '/' . $newFilename))
			@unlink(dirname($this->filepath) . '/' . $newFilename);
	
		$cmd = "/usr/bin/convert $options '" . basename($this->filepath) . "' '$type:$newFilename'";
		$oldFilepath = $this->filepath;
		$this->filepath = dirname($this->filepath) . "/$newFilename";

		exec($cmd, $output, $result);

		if($result || !is_file($this->filepath)) {
			return false;
		} else {
			$converted = 1;
			$error = 0;
			@unlink($oldFilepath);
		}
		
		return true;
		
	}
	
	function writeText($str, $x, $y, $hexColor, $font = 1) {
		imagestring($this->image, $font, $x, $y, $str, $this->getColor($hexColor));
	}
	
	function writeTTFText($str, $fontFile, $hexColor, $fontSize, $x, $y, $angle = 0) {
	
		imagettftext($this->image, $fontSize, $angle, $x, $y, $this->getColor($hexColor), $fontFile, $str);
	
	}
	
	function getTTFBoundingBox($str, $fontFile, $fontSize, $angle = 0) {
	
		/*
			Returns the following array indexes:
			
			0 lower left corner, X position 
			1 lower left corner, Y position 
			2 lower right corner, X position 
			3 lower right corner, Y position 
			4 upper right corner, X position 
			5 upper right corner, Y position 
			6 upper left corner, X position 
			7 upper left corner, Y position 

		*/
	
		return imagettfbbox($fontSize, $angle, $fontFile, $str);
	
	}
	
	function isAnimatedGIF() {
	
		exec('/usr/local/bin/identify ' . $this->filepath, $output, $result);
		
		if (strstr($output[0], 'GIF')) {
			return count($output) > 1 ? true : false;
		} else {
			return false;
		}
	
	}

}
?>