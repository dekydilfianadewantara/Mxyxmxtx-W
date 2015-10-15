<?php
class ImageManipulation{
	// public static function login($user)
	public static function uploadimage($nama,$size,$tmp_name,$type,$path)
	{
		$file = $nama;
		if(isset($file))
		{
			############ Edit settings ##############
			$ThumbSquareSize 		= 240; //Thumbnail will be 200x200
			$BigImageMaxSize 		= 200; //Image Maximum height or width
			// $ThumbPrefix			= "thumb_"; //Normal thumb Prefix
			$DestinationDirectory	= 'assets/store/'.$path.'/'; //specify upload directory ends with / (slash)
			$Quality 				= 100; //jpeg quality
			##########################################
			
			//check if this is an ajax request
			// if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
			// 	die();
			// }
			
			// check $_FILES['ImageFile'] not empty
			if(!isset($file) || !is_uploaded_file($tmp_name))
			{
					die('Something wrong with uploaded file, something missing!'); // output error when above checks fail.
			}
			
			// Random number will be added after image name
			$RandomNumber 	= rand(0, 9999999999); 

			$ImageName 		= $file; #str_replace(' ','-',strtolower($_FILES['ImageFile']['name'])); //get image name
			$ImageSize 		= $size; // get original image size
			$TempSrc	 	= $tmp_name; // Temp name of image file stored in PHP tmp folder
			$ImageType	 	= $type; //get file type, returns "image/png", image/jpeg, text/plain etc.

			//Let's check allowed $ImageType, we use PHP SWITCH statement here
			switch(strtolower($ImageType))
			{
				case 'image/png':
					//Create a new image from file 
					$CreatedImage =  imagecreatefrompng($tmp_name);
					break;
				case 'image/gif':
					$CreatedImage =  imagecreatefromgif($tmp_name);
					break;			
				case 'image/jpeg':
				case 'image/pjpeg':
					$CreatedImage = imagecreatefromjpeg($tmp_name);
					break;
				default:
					die('Unsupported File!'); //output error and exit
			}
			
			//PHP getimagesize() function returns height/width from image file stored in PHP tmp folder.
			//Get first two values from image, width and height. 
			//list assign svalues to $CurWidth,$CurHeight
			list($CurWidth,$CurHeight)=getimagesize($TempSrc);

			$NewImageName = $ImageName;
			//set the Destination Image
			// $thumb_DestRandImageName 	= $DestinationDirectory.'/'.$ThumbPrefix.$NewImageName; //Thumbnail name with destination directory
			$DestRandImageName 			= $DestinationDirectory.'/'.$NewImageName; // Image with destination directory
			
			//Resize image to Specified Size by calling resizeImage function.
			if(self::resizeImage($CurWidth,$CurHeight,$BigImageMaxSize,$DestRandImageName,$CreatedImage,$Quality,$ImageType))
			{
				//Create a square Thumbnail right after, this time we are using cropImage() function
				// if(!self::cropImage($CurWidth,$CurHeight,$ThumbSquareSize,$thumb_DestRandImageName,$CreatedImage,$Quality,$ImageType))
				// 	{
				// 		echo 'Error Creating thumbnail';
				// 	}

			}else{
				die('Resize Error'); //output error
			}
		}
	}

	public static function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$ImageType)
	{
		//Check Image size is not 0
		if($CurWidth <= 0 || $CurHeight <= 0) 
		{
			return false;
		}
		
		//Construct a proportional size of new image
		$ImageScale      	= min($MaxSize/$CurWidth, $MaxSize/$CurHeight); 
		$NewWidth  			= ceil($ImageScale*$CurWidth);
		$NewHeight 			= ceil($ImageScale*$CurHeight);
		$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
		
		// Resize Image
		if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
		{
			switch(strtolower($ImageType))
			{
				case 'image/png':
					imagepng($NewCanves,$DestFolder);
					break;
				case 'image/gif':
					imagegif($NewCanves,$DestFolder);
					break;			
				case 'image/jpeg':
				case 'image/pjpeg':
					imagejpeg($NewCanves,$DestFolder,$Quality);
					break;
				default:
					return false;
			}
		//Destroy image, frees memory	
		if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
		return true;
		}

	}

	//This function corps image to create exact square images, no matter what its original size!
	public static function cropImage($CurWidth,$CurHeight,$iSize,$DestFolder,$SrcImage,$Quality,$ImageType)
	{	 
		//Check Image size is not 0
		if($CurWidth <= 0 || $CurHeight <= 0) 
		{
			return false;
		}
		
		//abeautifulsite.net has excellent article about "Cropping an Image to Make Square bit.ly/1gTwXW9
		if($CurWidth>$CurHeight)
		{
			$y_offset = 0;
			$x_offset = ($CurWidth - $CurHeight) / 2;
			$square_size 	= $CurWidth - ($x_offset * 2);
		}else{
			$x_offset = 0;
			$y_offset = ($CurHeight - $CurWidth) / 2;
			$square_size = $CurHeight - ($y_offset * 2);
		}
		
		$NewCanves 	= imagecreatetruecolor($iSize, $iSize);	
		if(imagecopyresampled($NewCanves, $SrcImage,0, 0, $x_offset, $y_offset, $iSize, $iSize, $square_size, $square_size))
		{
			switch(strtolower($ImageType))
			{
				case 'image/png':
					imagepng($NewCanves,$DestFolder);
					break;
				case 'image/gif':
					imagegif($NewCanves,$DestFolder);
					break;			
				case 'image/jpeg':
				case 'image/pjpeg':
					imagejpeg($NewCanves,$DestFolder,$Quality);
					break;
				default:
					return false;
			}
		//Destroy image, frees memory	
		if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
		return true;

		}
		  
	}
}