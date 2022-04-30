<?php
error_reporting(E_ALL);
set_time_limit(0);
header('Content-type: text/plain; charset=utf-8');
include '../PHPExcel-1.8/classes/PHPExcel/IOFactory.php';
$folder = '6 november 2019';
$filename = 'product-sheet.xls';
@mkdir("generated-csv/".$folder);
@mkdir("generated-csv/".$folder."/product-images");

$objReader = new PHPExcel_Reader_Excel5();
$objPHPExcel = $objReader->load($folder."/".$filename);
$activeData = $objPHPExcel->getActiveSheet();
$rowcounter = 2; $csv = array(); $csv_row = array();

$csv_row[] = array('UID', 'image', 'product sku', 'product title', 'description', 'category', 'sub category', 'price', 'width', 'length', 'height', 'permalink', 'meta title');
foreach($activeData->getRowIterator() as $row) 
{
	$image          = $activeData->getCell('A'.$rowcounter);
	$product_sku    = $activeData->getCell('B'.$rowcounter);
	$product_title  = $activeData->getCell('C'.$rowcounter);
	$description    = $activeData->getCell('D'.$rowcounter);
	$bullets  	    = $activeData->getCell('E'.$rowcounter);
	$category       = $activeData->getCell('F'.$rowcounter);
	$sub_category   = $activeData->getCell('G'.$rowcounter);
	$price          = $activeData->getCell('H'.$rowcounter);
	$width          = $activeData->getCell('I'.$rowcounter);
	$length         = $activeData->getCell('J'.$rowcounter);
	$height         = $activeData->getCell('K'.$rowcounter);
	$permalink      = $activeData->getCell('L'.$rowcounter);
	$meta_title     = $activeData->getCell('M'.$rowcounter);

	$new_image_name = fn_image_name($image);
	$description    = fn_clean_desc($description);
	$bullets        = fn_clean_desc($bullets);

	$bullet_points = ''; $exp = array(); 
	if($product_title!='')
	{
		if($bullets!='')
		{
			$exp = explode("•",$bullets);
			if(count($exp)>1)
			{
				$bullet_points.='<ul>';
				foreach($exp as $li)
				{
					if($li!='' && strlen($li)>1) {
						$bullet_points.='<li>'.trim($li).'</li>';
					}
				}
				$bullet_points.='</ul>';
			}
		}

		$imgfolder = $folder."/images/".$product_sku;
		$img_gallery = '';
		if(is_dir($imgfolder))
		{
			$root = scandir($imgfolder);
			$i=1;
			foreach($root as $key=>$value)
			{
				if($value!='.' && $value!='..')
				{
					$image = $imgfolder."/".$value;
					$new_image_name = fn_image_name($product_title)."_".$i.'.jpg';
					$dest_image = "generated-csv/".$folder."/product-images/".$new_image_name;
					@copy($image,$dest_image);
					$i++;
					$img_gallery.= $new_image_name.',';
				}
			}
		}

		$width = fn_clean_desc($width);
		$length = fn_clean_desc($length);
		$height = fn_clean_desc($height);
		$csv_row[] = array(time() + $rowcounter, $img_gallery, $product_sku, $product_title, $description.$bullet_points, $category, $sub_category, $price, $width, $length, $height, $permalink, $meta_title);
	}
	$rowcounter++;
}
$fp = fopen('generated-csv/'.$folder.'/products.csv', 'w');
foreach ($csv_row as $fields) {
    fputcsv($fp, $fields);
}
fclose($fp);

function fn_image_name($title)
{
	$title = trim(strtolower($title));
	$title = str_replace(" x ","x",$title);
	$title = str_replace(" ","-",$title);
	return $title;
}

function fn_clean_desc($description)
{
	$description = str_replace("’","'",$description);
	$description = str_replace("”","'",$description);
	$description = str_replace(",",",",$description);
	$description = str_replace("×","x",$description);
	$description = str_replace("–","-",$description);
	$description = str_replace("½","1/2",$description);
	$description = str_replace("&nbsp;","",$description);
	$description = str_replace(".","",$description);
	$description = str_replace("'","",$description);
	$description = str_replace("(","",$description);
	$description = str_replace(")","",$description);
	$description = str_replace("â","a",$description);
	$description = str_replace("Â€‹","a",$description);
	return $description;
}
?>