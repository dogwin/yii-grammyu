<?php

    /**
     * SoundCloud API wrapper with support for authentication using OAuth 2
     *
     * @category  Services
     * @package   Services_Soundcloud
     * @author    Anton Lindqvist <anton@qvister.se>
     * @copyright 2010 Anton Lindqvist <anton@qvister.se>
     * @license   http://www.opensource.org/licenses/mit-license.php MIT
     * @link      http://github.com/mptre/php-soundcloud
     */
class Soundcloud extends CApplicationComponent {

    public static function setup() {
        $soundcloud = new Services_Soundcloud(
            Yii::app()->params["soundcloud"]["clientId"],
            Yii::app()->params["soundcloud"]["clientSecret"],
            "http://" . Yii::app()->params["url"] . "/app/connect/authorize"
        );

        return $soundcloud;
    }

    public static function getTrackFromApi($id) {
        $soundcloud = self::setup();
        try {
            $track = $soundcloud->get("tracks/{$id}");
            if($track != ''){
                $track = json_decode($track);
                if($track->streamable && $track->embeddable_by != "none" && $track->sharing != "private"){
                    return $track;
                } else {
                    self::deleteTrack($id);
                }
            } else {
                self::deleteTrack($id);
                return false;
            }
        } catch(Exception $e){

        }
    }

    public static function refreshTrack($id){
        $track = self::getTrackFromApi($id);
        if($track){
            self::saveTrack($id, $track);
        }
    }

    public static function deleteTrack($id){
        $model = Users::model()->find(
            array(
                "condition" => "track_id = :id",
                "params" => array(
                    ":id" => $id
                )
            )
        );
        if($model){
            $model->track_id = '';
            $model->track_title = '';
            $model->track_genre = '';
            $model->track_tags = '';
            $model->track_data = '';
            $model->save();
        }
    }

    public static function saveTrack($id, $data){
        if($data){
            $model = Users::model()->find(
                array(
                    "condition" => "track_id = :id",
                    "params" => array(
                        ":id" => $id
                    )
                )
            );
            if($model){
                $model->track_title = $data->title;
                //$model->track_genre = $data->genre;
                $model->track_tags = $data->tag_list;
                $model->track_data = json_encode($data);
                $model->dateupdated = new CDbExpression('NOW()');
                if(!$model->save()){
                    throw new CHttpException(500, print_r($model->getErrors(),1));
                }
                self::processWaveform($id, $data);
            }
            return $model;
        } else {
            throw new Exception("No data passed in");
        }
    }

    public static function processWaveform($id, $data){
        $basepath = $_SERVER['DOCUMENT_ROOT'];
        $targetFolder = '/media/tracks/' . $id;
        if(!file_exists($basepath . $targetFolder)){
            mkdir($basepath . $targetFolder);
        }

        try {
            //load source waveform
            $source_data = file_get_contents($data->waveform_url);
            $source_path = $basepath . $targetFolder . "/waveform_source.png";
            file_put_contents($source_path, $source_data);

            //process to match our view
            $waveform_sizes = Yii::app()->params["waveform"];
            foreach($waveform_sizes as $k => $size){
                $size_parts = explode("x",$size);
                $width = $size_parts[0];
                $height = $size_parts[1];
                $inputWave = imagecreatefrompng($source_path);
                $inputBackground = imagecreatefrompng(Yii::app()->basePath . "/.." . Yii::app()->theme->baseUrl . "/img/waveform_lines_background.png");
                $overlayLines = imagecreatefrompng(Yii::app()->basePath . "/.." . Yii::app()->theme->baseUrl . "/img/waveform_lines.png");
				$overlayGold = imagecreatefrompng(Yii::app()->basePath . "/.." . Yii::app()->theme->baseUrl . "/img/waveform_gold_solid.png");
				$overlayLinesDark = imagecreatefrompng(Yii::app()->basePath . "/.." . Yii::app()->theme->baseUrl . "/img/waveform_lines_dark.png");
				$overlayGoldLines = imagecreatefrompng(Yii::app()->basePath . "/.." . Yii::app()->theme->baseUrl . "/img/waveform_gold_lines.png");
				
				$inputWaveResized = imagecreatetruecolor($width,$height);
				$outputWaveGold = imagecreatetruecolor($width,$height);
				$outputWaveBlur = imagecreatetruecolor($width,$height);
				$outputWaveGrey = imagecreatetruecolor($width,$height);
				$outputWaveBlack = imagecreatetruecolor($width,$height);
                $black = imagecolorallocate($inputWave, 0, 0, 0);
                $transparent = imagecolorallocatealpha($outputWaveGrey, 255, 0, 0, 127);
                imagefill($inputWave,10,0,$black);
                imagefill($inputWave,10,279,$black);
				imagecopyresized($outputWaveBlack, $overlayLinesDark, 0, 2, 0, 0, $width, $height *.9, $width, 114);
				imagecopyresized($outputWaveBlack, $inputWave, 0, 2, 0, 0, $width, $height *.9, 1800, 280);
				imagecopyresized($outputWaveGrey, $overlayLines, 0, 2, 0, 0, $width, $height *.9, $width, 114);
				imagecopyresized($outputWaveGrey, $inputWave, 0, 2, 0, 0, $width, $height*.9, 1800, 280);
				imagecopyresized($outputWaveGold, $overlayGoldLines, 0, 2, 0, 0, $width, $height *.9, $width, 114);
				imagecopyresized($outputWaveGold, $inputWave, 0, 2, 0, 0, $width, $height *.9, 1800, 280);
				imagecopyresized($outputWaveBlur, $overlayGold, 0, 2, 0, 0, $width, $height *.9, $width, 114);
				imagecopyresized($outputWaveBlur, $inputWave, 0, 2, 0, 0, $width, $height *.9,1800, 280);
				imagecopyresized($inputBackground, $inputBackground, 0, 0, 0, 0, $width, $height,470, 114);
				
				$outputWaveBlack = self::roundIt($outputWaveBlack, $width, $height);
				$outputWaveGrey = self::roundIt($outputWaveGrey, $width, $height);
				$outputWaveGold = self::roundIt($outputWaveGold, $width, $height);
				$outputWaveBlur = self::roundIt($outputWaveBlur, $width, $height);
				
				imagefill($outputWaveBlack,1,1,$transparent);
				imagefill($outputWaveGrey,1,1,$transparent);
				
				for ($x=0;$x< 1;$x++){
					imagefilter($outputWaveGold, IMG_FILTER_GAUSSIAN_BLUR);
				}
				for ($x=0;$x< 20;$x++){
					imagefilter($outputWaveBlur, IMG_FILTER_GAUSSIAN_BLUR);
				}
				
				imagesavealpha($outputWaveBlack, true);
				imagesavealpha($outputWaveGrey, true);
				imagesavealpha($outputWaveGold, true);
				imagesavealpha($outputWaveBlur, true);
				imagesavealpha($inputWaveResized, true);
				
				imagealphablending($outputWaveGold, true);
				imagealphablending($outputWaveBlur, true);

				$outputWaveGold = self::addImages($outputWaveGold, $outputWaveBlur, $width, $height,.55);
				$outputWaveGold = self::addImages($outputWaveGold, $inputBackground, $width, $height,1);
				
				
				
				// imagepng($outputWaveBlack,"img/outputWaveform_BLACK.png");
				// imagepng($outputWaveGrey,"img/outputWaveform_GREY.png");
				// imagepng($outputWaveGold,"img/outputWaveform_GOLD.png");
				// imagepng($outputWaveBlur,"img/outputWaveform_BLUR.png");

                imagepng($outputWaveGold, $basepath . $targetFolder . "/waveform_gold_$k.png");
                imagepng($outputWaveBlack, $basepath . $targetFolder . "/waveform_black_$k.png");
                imagepng($outputWaveGrey, $basepath . $targetFolder . "/waveform_grey_$k.png");
            }
        } catch(CHttpException $e){
            throw new CHttpException(500, $e->getMessage());
        }
    }



	function roundIt($incoming, $src_w, $src_h){
	
		$output = imagecreatetruecolor($src_w, $src_h);
		imagesavealpha($output, true);
		imagealphablending($output, true);
		$transparent = imagecolorallocatealpha($output, 255, 0, 0, 64); 
	
		for ($x = 0; $x < $src_w; $x+=2){
			for ($y = 0; $y < $src_h; $y++)  {
				$c1 = imagecolorat($incoming, $x, $y);
				 imagesetpixel($output, $x,$y, $c1);
				 imagesetpixel($output, $x+1,$y, $c1);
				 imagesetpixel($output, $x+2,$y, $c1);
			}
		}
		return $output;
	}
	
	function addImages($imageA, $imageB, $src_w, $src_h, $alpha){
	
		for ($y = 0; $y < $src_h; $y++)  {
			for ($x = 0; $x < $src_w; $x++){
			
				 try {
					
					$color1 = imagecolorsforindex($imageA, imagecolorat($imageA, $x, $y));
					$color2 = imagecolorsforindex($imageB, imagecolorat($imageB, $x, $y));
				
					$red   = $color1['red'] * $alpha + $color2['red'] * $alpha;
					$green = $color1['green'] * $alpha + $color2['green'] * $alpha;
					$blue  = $color1['blue'] * $alpha + $color2['blue'] * $alpha;
					
					if ($red > 255){
						$red = 255;
					}
					if ($green > 255){
						$green = 255;
					}
					if ($blue > 255){
						$blue = 255;
					}
						
					if ($red < 0){
						$red = 0;
					}
					if ($green < 0){
						$green = 0;
					}
					if ($blue < 0){
						$blue = 0;
					}
						
					
					
						
					$color = imagecolorallocate($imageA, $red, $green, $blue);
					imagesetpixel($imageA, $x,$y, $color);
				
				} catch(Exception $e){

        		}
				 
			}
		}
	
		return $imageA;
	
	}



}
