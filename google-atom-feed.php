<?php
error_reporting(E_ALL);
set_time_limit(0);
date_default_timezone_set("Europe/London");
header('Content-Type: text/xml; charset=utf-8', true); //set document header content type to be XML

$mysitetitle='';

$shippingcountry = 'GB'
$shippingservice = 'Standard';
$shippingprice = '5.95 GBP';


function xsanatise($var)
{
    $var =strip_tags($var);
    $var =htmlspecialchars($var,ENT_XML1, 'UTF-8');
    $var =htmlspecialchars($var,ENT_QUOTES, 'UTF-8');
    $var = preg_replace('/[\x00-\x1f]/','',$var);
    $var =str_ireplace(array('<','>','&','\'','"'),array('&lt;','&gt;','&amp;','&apos;','&quot;'),$var);
    $var =str_replace('&nbsp;',' ',$var);
    $var =str_replace('&ndash;',' ',$var);

    return $var;
}

function xsanatisegoogle($var)
{

    $var =htmlspecialchars($var,ENT_XML1, 'UTF-8');
    $var=str_replace('&amp;gt;', '>',$var);
    $var=str_replace('&amp;gt,','>',$var);
    $var=str_replace('&gt,','>',$var);
    $var =str_replace('&gt;','>',$var);

    $var=str_replace('&amp;','&',$var);


    return $var;
}


function xcheck($var)
{

    $var = trim($var);

    if(strlen($var)>=1 && preg_match('/[A-Z]+[a-z]+[0-9]+/', $var)!==false ){
        return true;
    }else{
        return false;
    }

}


function xcheckgoogle($var)
{

    $var = trim($var);

    if(strlen($var)>=1 && preg_match('/^[1-9][0-9]*$/', $var)!==false ){
        return true;
    }else{
        return false;
    }

}

$rss = new SimpleXMLElement('<feed version="1" xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0"></feed>');


//$channel = $rss->addChild('xmlns:g:channel'); //add channel node
//$atom = $rss->addChild('xmlns:g:atom:atom:link'); //add atom node
//$atom->addAttribute('href', 'http://www.mysite.com/google_atom.php'); //add atom node attribute
//$atom->addAttribute('rel', 'self');
//$atom->addAttribute('type', 'application/rss+xml');
$title = $rss->addChild('title',$mysitetitle); //title of the feed
//$description = $rss->addChild('xmlns:g:description','description line goes here'); //feed description
//$link = $rss->addChild('xmlns:g:link','http://www.mysite.com'); //feed site
//$language = $rss->addChild('xmlns:g:language','en-US'); //language

$updated = $rss->addChild('updated',date("D, d M Y H:i:s T", time())); //language

//Create RFC822 Date format to comply with RFC822
$date_f = date("D, d M Y H:i:s T", time());
$build_date = gmdate(DATE_RFC2822, strtotime($date_f));
$lastBuildDate = $rss->addChild('lastBuildDate',$date_f); //feed last build date



require_once dirname(__FILE__) . '/app/Mage.php';
Mage::app()->getCacheInstance()->flush();

Mage::app('admin')->setUseSessionInUrl(false);
Mage::app('default');
Mage::app ()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$collection = Mage::getModel('catalog/product')
                        ->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('status',array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED)); //STATUS_DISABLED



foreach ($collection as $product)
{

$upcmpn=0;

if(xcheck($product->getUpc())&& strlen(trim($product->getUpc())>=12) && strlen(trim($product->getUpc()))<=13){$upcmpn++;}
if(xcheck($product->getMpn())){$upcmpn++;}


if($product->getCollection_only())
{
    $stock = 'out of stock';
}elseif($product->isAvailable()) {
    $stock = 'in stock';
}else{
    $stock = 'out of stock';
}


if( xcheck(Mage::getBaseUrl('media').'catalog/product'.$product->getImage()!==false)
&&  $upcmpn>=1
&& xcheck($product->getBrand()!==false)
&& xcheck($product->getGoogleproducttype()!==false)
&& xcheck($product->getShortDescription()!==false)
&& xcheck($product->getSku()!==false)
&& xcheck($product->getName()!==false)
&& $stock == 'in stock'  )
{


		$prodSku = $product->getSku();

		$productId = $product->getIdBySku($product->getSku());

		$product->load($productId);
		$productId = $product->getItemId();
		$options = $product->getOptions();


        $buildlist_color='';
        $buildlist_color=array();

        $buildlist_size='';
        $buildlist_size=array();

            $item = $rss->addChild('entry'); //add item node



            $item_group_id = $item->addChild('xmlns:g:item_group_id', xsanatise($product->getSku()));


            $item->addChild('xmlns:g:title', xsanatise($product->getName())); //add title node under item



            $urllink='';
            if(xcheck($product->getUrlRewrite())!==false)
            {
              $urllink = $product->getUrlRewrite();
            }else{
              $urllink= $product->getProductUrl();
            }

            //$urllink = urlencode($urllink);

            $urllink = str_replace("/google_atom.php","",$urllink);

            $link = $item->addChild('xmlns:g:link',$urllink); //add link node under item


            if(xcheck($product->getBrand())!==false){
                $item->addChild('xmlns:g:brand',  xsanatise($product->getBrand()));
            }else{
            //    $item->addChild('xmlns:g:brand')->addChild('xmlns:g:identifier_exists','false');
            }

            if(xcheck($product->getMpn())!==false){
                $item->addChild('xmlns:g:mpn', xsanatise($product->getMpn()));
            }else{
                //$item->addChild('xmlns:g:mpn')->addChild('xmlns:g:identifier_exists','false');


                if(xcheck($product->getUpc())!==false && strlen($product->getUpc())>=12 && strlen($product->getUpc())<=13){


                    $item->addChild('xmlns:g:gtin', xsanatise($product->getUpc()));

                }else{
                    //$item->addChild('xmlns:g:gtin')->addChild('xmlns:g:identifier_exists','false');
                }
            }


            $new = $item->addChild('xmlns:g:condition', 'new');



            $image_link = $item->addChild('xmlns:g:image_link', Mage::getBaseUrl('media').'catalog/product'.$product->getImage());


            $item->addChild('xmlns:g:quantity', '1');


            $item->addChild('xmlns:g:id',$product->getId()."tools4thegarden");

            if(xcheckgoogle($product->getGoogleproductcategory())!==false){
                $google = null;
                $google = xsanatisegoogle($product->getGoogleproductcategory());
                $google_product_category = $item->addChild('xmlns:g:google_product_category', $google);
                $google_product_category = $item->addChild('xmlns:g:product_type', $google);
            }else{
                $google_product_category = $item->addChild('xmlns:g:google_product_category')->addChild('xmlns:g:identifier_exists','false');
                $google_product_category = $item->addChild('xmlns:g:product_type')->addChild('xmlns:g:identifier_exists','false');
            }

            if(empty($product->getSpecialPrice())){
                $item->addChild('xmlns:g:price',number_format($product->getFinalPrice(), '2', '.', ','). ' GBP' );
            }else{
                 $item->addChild('xmlns:g:price',number_format($product->getPrice(), '2', '.', ','). ' GBP' );
                $item->addChild('xmlns:g:sale_price', number_format($product->getSpecialPrice(), '2', '.', ','). ' GBP' );
            }

            $shipping = $item->addChild('xmlns:g:shipping');
              $shipping->addChild('xmlns:g:country', $shippingcountry);
              $shipping->addChild('xmlns:g:service', $shippingservice);
              $shipping->addChild('xmlns:g:price', $shippingprice);

             $item->addChild('xmlns:g:availability', $stock);

            $summary = $item->addChild('xmlns:g:summary', xsanatise($product->getShortDescription()) );
}
}
//$conn->close();
echo $rss->asXML();
?>
