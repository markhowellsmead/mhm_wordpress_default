<?php
	
namespace Frp\WordPress;

class Exif {
	
	var $app = null;

	//////////////////////////////////////////////////

	public function __construct(App &$app){
		$this->app = $app;
	}

	//////////////////////////////////////////////////

	function DMStoDEC($deg,$min,$sec){
		// Converts DMS ( Degrees / minutes / seconds )
		// to decimal format longitude / latitude
		return $deg+((($min*60)+($sec))/3600);
	}//DMStoDEC

	//////////////////////////////////////////////////

	function DECtoDMS($dec){
		// Converts decimal longitude / latitude to DMS
		// ( Degrees / minutes / seconds )
	
		// This is the piece of code which may appear to
		// be inefficient, but to avoid issues with floating
		// point math we extract the integer part and the float
		// part by using a string function.

		$vars = explode(".",$dec);
		$deg = $vars[0];
		$tempma = "0.".$vars[1];

		$tempma = $tempma * 3600;
		$min = floor($tempma / 60);
		$sec = $tempma - ($min*60);

		return array("deg"=>$deg,"min"=>$min,"sec"=>$sec);
	}//DECtoDMS

	//////////////////////////////////////////////////

	function getEXIFData($source_path,$onlyWithGPSData=false){

		$exif = @exif_read_data($source_path, 'ANY_TAG');

		if(!$exif || ($onlyWithGPSData && (!isset($exif['GPSLongitude']) || !isset($exif['GPSLongitude'])) )){
			return false;
		}

		/*
		[GPSLatitudeRef] => N
		[GPSLatitude] => Array
		(
			[0] => 57/1
			[1] => 31/1
			[2] => 21334/521
		)

		[GPSLongitudeRef] => W
		[GPSLongitude] => Array
		(
			[0] => 4/1
			[1] => 16/1
			[2] => 27387/1352
		)
		*/

		$GPS = array();

		$GPS['lat']['deg'] = explode('/',$exif['GPSLatitude'][0]);
		$GPS['lat']['deg'] = $GPS['lat']['deg'][0] / $GPS['lat']['deg'][1];
		$GPS['lat']['min'] = explode('/',$exif['GPSLatitude'][1]);
		$GPS['lat']['min'] = $GPS['lat']['min'][0] / $GPS['lat']['min'][1];
		$GPS['lat']['sec'] = explode('/',$exif['GPSLatitude'][2]);
		$GPS['lat']['sec'] = $GPS['lat']['sec'][1]!=0?floatval($GPS['lat']['sec'][0]) / floatval($GPS['lat']['sec'][1]):0;

		$exif['GPSLatitudeDecimal'] = $this->DMStoDEC($GPS['lat']['deg'],$GPS['lat']['min'],$GPS['lat']['sec']);
		if($exif['GPSLatitudeRef']=='S'):
			$exif['GPSLatitudeDecimal'] = 0-$exif['GPSLatitudeDecimal'];
		endif;

		$GPS['lon']['deg'] = explode('/',$exif['GPSLongitude'][0]);
		$GPS['lon']['deg'] = $GPS['lon']['deg'][0] / $GPS['lon']['deg'][1];
		$GPS['lon']['min'] = explode('/',$exif['GPSLongitude'][1]);
		$GPS['lon']['min'] = $GPS['lon']['min'][0] / $GPS['lon']['min'][1];
		$GPS['lon']['sec'] = explode('/',$exif['GPSLongitude'][2]);
		$GPS['lon']['sec'] = $GPS['lon']['sec'][1]!=0?floatval($GPS['lon']['sec'][0]) / floatval($GPS['lon']['sec'][1]):0;

		$exif['GPSLongitudeDecimal'] = $this->DMStoDEC($GPS['lon']['deg'],$GPS['lon']['min'],$GPS['lon']['sec']);
		if($exif['GPSLongitudeRef']=='W'):
			$exif['GPSLongitudeDecimal'] = 0-$exif['GPSLongitudeDecimal'];
		endif;

		$exif['GPSCalculatedDecimal'] = $exif['GPSLatitudeDecimal'].','.$exif['GPSLongitudeDecimal'];
		//$exif['googlemaps_image'] = '<img src="http://maps.googleapis.com/maps/api/staticmap?center='.$exif['GPSCalculatedDecimal'].'&zoom=14&size=980x540&maptype=hybrid&markers=color:red|'.$exif['GPSCalculatedDecimal'].'&sensor=false" alt="google map">';

		$size=getimagesize($source_path,$info);
		if(isset($info['APP13'])){
			$iptc = iptcparse($info['APP13']);
	
			if (is_array($iptc)) {
				$exif['iptc']['caption'] = isset($iptc["2#120"])?$iptc["2#120"][0]:'';
				$exif['iptc']['graphic_name'] = isset($iptc["2#005"])?$iptc["2#005"][0]:'';
				$exif['iptc']['urgency'] = isset($iptc["2#010"])?$iptc["2#010"][0]:'';
				$exif['iptc']['category'] = @$iptc["2#015"][0];
	
				 // note that sometimes supp_categories contans multiple entries
				$exif['iptc']['supp_categories'] = @$iptc["2#020"][0];
				$exif['iptc']['spec_instr'] = @$iptc["2#040"][0];
				$exif['iptc']['creation_date'] = @$iptc["2#055"][0];
				$exif['iptc']['photog'] = @$iptc["2#080"][0];
				$exif['iptc']['credit_byline_title'] = @$iptc["2#085"][0];
				$exif['iptc']['city'] = @$iptc["2#090"][0];
				$exif['iptc']['state'] = @$iptc["2#095"][0];
				$exif['iptc']['country'] = @$iptc["2#101"][0];
				$exif['iptc']['otr'] = @$iptc["2#103"][0];
				$exif['iptc']['headline'] = @$iptc["2#105"][0];
				$exif['iptc']['source'] = @$iptc["2#110"][0];
				$exif['iptc']['photo_source'] = @$iptc["2#115"][0];
				$exif['iptc']['caption'] = @$iptc["2#120"][0];
				
				$exif['iptc']['keywords'] = @$iptc["2#025"];
			}
		}
		return $exif;
	}
}