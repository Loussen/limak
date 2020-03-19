<?php
/**
 * Created by PhpStorm.
 * User: azerb
 * Date: 18.11.2018
 * Time: 10:43 PM
 */

namespace App\Libraries\Parser;

use Sunra\PhpSimple\HtmlDomParser;

class DomainParser
{
    private $html;
    public function __construct(string $url)
    {
        $this->html = $this->getFileContents($url);
    }

    /**
     * Get Page HTML data
     * @param string $url
     * @return bool|mixed
     */
    private function getFileContents(string $url) {
        $userAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $html = curl_exec($ch);
        if (!$html) {
            return false;
        }
        else{
            return $html;
        }
    }

    /**
     * Parse html
     * @param string $html
     * @return \simplehtmldom_1_5\simple_html_dom
     */
    public function extractDomElements(string $html) {
        return HTMLDomParser::str_get_html($html);
    }

    /**
     * Parse Hepsipurada
     * @return bool|null|object
     */
    public function extractHepsiburada() {
        $phpObject = null;
        if(!empty($this->html)) {
           try {
               $jsObjectStart = explode('var productModel = ', $this->html);
               $jsObject = explode('</script>', $jsObjectStart[1] );
               $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
               $stringObj = str_replace(';', '', $stringObj);
               $phpObject = json_decode($stringObj);
           } catch (\Exception $e) {

           }
        } else {
            return false;
        }
        $product = null;

        if ($phpObject !== null && $phpObject->product) {
            $size = [];
            if($phpObject->product->variants) {
                foreach ($phpObject->product->variants as $variant) {
                    $s = null;
                    if($variant->properties && count($variant->properties) > 0) {
                        foreach ($variant->properties as $property) {
                            if($property->name === 'Beden') {
                                $s = $property->valueObject->actualValue;
                            }
                        }
                    }
                    $size[] = ["text" => $s, "value" => $variant->price->value];
                }
            }

            $product = (object)[
                "file" => $phpObject->product->allImages && $phpObject->product->allImages[0] ? $phpObject->product->allImages[0]->imageUrl : null,
                "name" => $phpObject->product->name ? $phpObject->product->name : null,
                "price"=> $phpObject->product->marketPrice ? $phpObject->product->marketPrice: null,
                "shop" => 'hepsiburada',
                "brand"=> $phpObject->product->brand ? $phpObject->product->brand : null,
                "color"=> null,
                "type" => $phpObject->product->catalogName ? $phpObject->product->catalogName : null,
                "quantity" => 1,
//                "size" => $size,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }
        return $product;
    }

    /**
     * Parse TrendYol
     * @return bool|object
     */
    public function extractTrendYol() {
        if(!empty($this->html)) {
            $this->html = $this->extractDomElements($this->html);
            $result = [];
            try {
                $file = $this->html->find('ul#thumbnailContainer > li > img')[0]->attr['src'];
            }catch (\Exception $e) {
                $file = null;
            }
            try {
                $sizes = $this->html->find('script');
                $sizeCount = count($sizes);
                if($sizeCount > 0) {
                    $sizesArr = [];
                    for($i = 0; $i < $sizeCount; $i++) {
                        if (strpos($sizes[$i]->innertext(), '$.TYSetProductDetail') !== false) {
                            $stringObj = $sizes[$i]->innertext();
                            $stringObj = str_replace('$.TYSetProductDetail({', ' ', $stringObj);
                            $stringObj = str_replace('});            $.TYPageName = "urundetay";', ' ', $stringObj);
                            $stringObj = preg_replace('/\s+/', '', $stringObj);
                            $obj = $this->textToObject($stringObj, ',');
                        }
                    }
                }
            }catch (\Exception $e) {
                $price = null;
            }
            $product = (object)[
                "file" => $file,
                "name" => $obj->ProductName,
                "price"=> $obj->SalePrice,
                "shop" => $obj->BoutiqueName,
                "brand"=> $obj->BrandName,
                "color"=> $obj->ColorName,
                "type" => $obj->ProductGenderType . ' - ' . $obj->CategoryHierarchy .'/'. $obj->ProductCategoryName,
                "quantity" => 1,
//                "size" => $obj->AvaliableSize,
                "size" => null,
                "merchant" => $obj->ProductMerchant,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
            return $product;
        } else {
            return false;
        }
    }
    private function textToObject(string $html, string $char) {
        $properties = explode($char, $html);
        $propertiesCount = count($properties);
        $generateObject = (array)[];
        if( $propertiesCount > 0) {
            for ($i = 0; $i < $propertiesCount; $i++) {
                $propertiesExpld = explode(':', $properties[$i]);
                $generateObject[$propertiesExpld[0]] = $propertiesExpld[1];
            }
        }
        return (object)$generateObject;
    }

    /**
     * Extract FLO
     * @return bool|null|object
     */
    public function extractFLO() {
        $phpObject = null;
        $phpObjectMain = null;
        $phpObjectAnother = null;
        if(!empty($this->html)) {
            try {

                $jsObjectStart = explode('<script type="application/ld+json">', $this->html);
                $jsObject = explode('</script>', $jsObjectStart[1] );
                $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
                $stringObj = str_replace(';', '', $stringObj);
                $phpObjectMain = json_decode($stringObj);
                $jsObjectStart = explode('<script>', $jsObjectStart[1]);
                $jsObjectStart = explode('dataLayer.push(', $jsObjectStart[1]);
                $jsObject = explode(');', $jsObjectStart[1] );
                $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
                $stringObj = str_replace(';', '', $stringObj);
                $stringObj = str_replace('GtmMobileDetect.site_type', '""', $stringObj);
                $phpObjectAnother = json_decode($stringObj);

            } catch (\Exception $e) {

            }
        } else {
            return false;
        }
        $product = null;

        if ($phpObjectMain !== null && $phpObjectMain->name && $phpObjectAnother !== null && $phpObjectAnother->product) {

            $product = (object)[
                "file" => $phpObjectMain->image ? $phpObjectMain->image : null,
                "name" => $phpObjectMain->name ? $phpObjectMain->name : null,
                "price"=> $phpObjectMain->offers->Price ? $phpObjectMain->offers->Price : null,
                "shop" => $phpObjectMain->offers->seller->name,
                "brand"=> $phpObjectMain->brand->name,
                "color"=> $phpObjectAnother->product->color,
                "type" => $phpObjectAnother->product->taxonomy,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }
        return $product;
    }
    /**
     * Extract US POLO Assn
     * @return bool|null|object
     */
    public function extractPOLO() {
        $phpObject = null;
        if(!empty($this->html)) {
            try {
                $jsObjectStart = explode('dataLayer.push(', $this->html);
                $jsObjectStart = $jsObjectStart[count($jsObjectStart) - 1];
                $jsObject = explode(');', $jsObjectStart );
                $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
                $stringObj = str_replace("//NameorIDisrequired.'id':'50200914-VR033',", '"', $stringObj);
                $stringObj = str_replace("//'detail'actionshaveanoptionallistproperty.", '', $stringObj);
                $stringObj = str_replace(';', '', $stringObj);
                $stringObj = str_replace("'", '"', $stringObj);
                $stringObj = str_replace('""', '"', $stringObj);
                $phpObject = json_decode($stringObj);
            } catch (\Exception $e) {

            }
            try {
                $this->html = $this->extractDomElements($this->html);
                $file = $this->html->find('.swiper-wrapper li')[0]->find('a > .swiper-lazy')[0]->attr['data-src'];

            }catch (\Exception $e) {
                $file = null;
            }
        } else {
            return false;
        }
        $product = null;

        if ($phpObject !== null && $phpObject->name) {

            $product = (object)[
                "file" => $file,
                "name" => $phpObject->name ? $phpObject->name : null,
                "price"=> $phpObject->totalvalue,
                "shop" => $phpObject->pbrand,
                "brand"=> $phpObject->pbrand,
                "color"=> null,
                "type" => $phpObject->name ? $phpObject->name : null,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }
        return $product;
    }
    /**
     * Extract LC Waikiki
     * @return bool|null|object
     */
    public function extractWaikiki() {
        $phpObject = null;
        if(!empty($this->html)) {
            try {
                $jsObjectStart = explode('modelItemShowedOnModelPage: "', $this->html);
                $jsObject = explode('</script>', $jsObjectStart[1] );
                $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
                $stringObj = str_replace('&quot;', '"', $stringObj);
                $stringObj = rtrim($stringObj, '"});');
                $phpObject = json_decode(($stringObj.'"}'));
            } catch (\Exception $e) {
                $phpObject = null;
            }
        } else {
            return false;
        }
        $product = null;

        if ($phpObject !== null && $phpObject->PRODUCTNAME) {

            $product = (object)[
                "file" => $phpObject->PRODUCTIMAGEURL1,
                "name" => $phpObject->PRODUCTNAME ? $phpObject->PRODUCTNAME : null,
                "price"=> $phpObject->PRODUCTPRICE,
                "shop" => $phpObject->PRODUCTBRANDNAME,
                "brand"=> $phpObject->PRODUCTBRANDNAME,
                "color"=> $phpObject->PRODUCTCOLOR,
                "type" => $phpObject->PRODUCTCATEGORY ? $phpObject->PRODUCTCATEGORY : null,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }
        return $product;
    }
    /**
     * Extract Damat
     * @return bool|null|object
     */
    public function extractDamat() {
        $phpObject = null;
        if(!empty($this->html)) {
            try {
                $jsObjectStart = explode('ecomm_totalvalue: ', $this->html);
                $jsObject = explode('};', $jsObjectStart[1] );
                $price = $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
            }catch (\Exception $e) {
                $file = null;
            }
            $this->html = $this->extractDomElements($this->html);
            try {
                $file = $this->html->find('.lazyload')[0]->attr['data-original'];
                $file = 'https://www.dsdamat.com/'.$file;
            }catch (\Exception $e) {
                $file = null;
            }
            try {
                $title = $this->html->find('.emos_H1')[0]->innertext();
            }catch (\Exception $e) {
                $file = null;
            }
            try {
                $color = null;
                $type = null;
                $table = $this->html->find('.urunozellikleritablo')[0]->find('tr');
                for ($i = 0; $i < count($table); $i++) {
                    if($table[$i]->find('td')[0]->innertext() === 'Renk' || $table[$i]->find('td')[0]->innertext() === 'RENK') {
                        $color = $table[$i]->find('td.urunozellikleritd3')[0]->innertext();
                    } else if($table[$i]->find('td')[0]->innertext() === 'Kalıp Alt' || $table[$i]->find('td')[0]->innertext() === 'KALIP ALT') {
                        $type = $table[$i]->find('td.urunozellikleritd3')[0]->innertext();
                    }
                }
            }catch (\Exception $e) {
                $color = null;
                $type = null;
            }

        } else {
            return false;
        }
        $product = null;
        $product = (object)[
            "file" => $file,
            "name" => $title,
            "price"=> $price,
            "shop" => "D'S Damat",
            "brand"=> "D'S Damat",
            "color"=> $color,
            "type" => $type,
            "quantity" => 1,
            "size" => null,
            "merchant" => null,
            "link" => null,
                "cargo"=> null,
                "hasCargo" => null
        ];

        return $product;
    }
    /**
     * Extract KOTON
     * @return bool|null|object
     */
    public function extractKoton() {
        $phpObject = null;
        if(!empty($this->html)) {
            $title = null;
            $price = null;
            $type = null;
            $brand = null;
            try {
                $jsObjectStart = explode("google_tag_params['pname'] = '", $this->html);
                $jsObject = explode("';", $jsObjectStart[1] );
                $title = $jsObject[0];
                $jsObjectStart = explode("google_tag_params['ecomm_totalvalue'] = ", $this->html);
                $jsObject = explode("';", $jsObjectStart[1] );
                $price = $jsObject[0];
                $jsObjectStart = explode("google_tag_params['pcat'] = '", $this->html);
                $jsObject = explode("';", $jsObjectStart[1] );
                $type = $jsObject[0];
                $jsObjectStart = explode("google_tag_params['brand'] = '", $this->html);
                $jsObject = explode("';", $jsObjectStart[1] );
                $brand = $jsObject[0];

            }catch (\Exception $e) {
                $file = null;
            }
            $this->html = $this->extractDomElements($this->html);
            try {
                $file = $this->html->find('.fancyboxProduct')[0]->find('img')[0]->attr['src'];
                $color = $this->html->find('.color')[0]->find('span')[0]->innertext();
                $color = explode('</b>', $color)[1];
            }catch (\Exception $e) {
                $file = null;
            }

        } else {
            return false;
        }
        $product = null;
        $product = (object)[
            "file" => $file,
            "name" => $title,
            "price"=> $price,
            "shop" => $brand,
            "brand"=> $brand,
            "color"=> $color,
            "type" => $type,
            "quantity" => 1,
            "size" => null,
            "merchant" => null,
            "link" => null,
                "cargo"=> null,
                "hasCargo" => null
        ];

        return $product;
    }
    /**
     * Extract Colins
     * @return bool|null|object
     */
    public function extractColins() {
        $phpObject = null;
        if(!empty($this->html)) {
            $title = null;
            $price = null;
            $type = null;
            $brand = null;
            try {
                $jsObjectStart = explode("invTagManagerParams = ", $this->html);
                $jsObject = explode("</script>", $jsObjectStart[2] );
                $obj = $jsObject[0];
                $obj = preg_split("/(\r?\n)/", $obj);
                $count = count($obj);
                $objString = '';
                for ($i = 0; $i < $count; $i++) {
                    if(!strpos($obj[$i], '//') && !strpos($obj[$i], 'CustomerCreatedDate')) {
                        if(strpos($obj[$i], ':')) {
                            $str = explode(':', $obj[$i]);
                            $obj[$i] ='"'.$str[0].'":'.$str[1];
                        }
                        $objString .= $obj[$i];
                    }
                }
                $objString = preg_replace('/\s+/', '', $objString);
                $object = json_decode($objString);
            }catch (\Exception $e) {
                $file = null;
            }
            $this->html = $this->extractDomElements($this->html);
            try {
                $file = $this->html->find('.module-frameproduct-picture-wrapper')[0]->find('.img-responsive')[0]->attr['src'];
            }catch (\Exception $e) {
                $file = null;
            }
            $color = null;
            $table = $this->html->find('.table-condensed')[1]->find('tr');
            for ($i = 0; $i < count($table); $i++) {
                if($table[$i]->find('th')[0]->find('strong')[0]->innertext() === 'Renk') {
                    $color = $table[$i]->find('td')[0]->find('.p-detail-spec-link')[0]->innertext();
                } else if($table[$i]->find('th')[0]->find('strong')[0]->innertext() === 'Kategori') {
                    $type = $table[$i]->find('td')[0]->find('.p-detail-spec-link')[0]->innertext();
                }
            }

        } else {
            return false;
        }
        $product = null;
        $product = (object)[
            "file" => $file,
            "name" => $object->ProductName,
            "price"=> $object->ProductPrice,
            "shop" => 'Colins',
            "brand"=> 'Colins',
            "color"=> $color,
            "type" => $type,
            "quantity" => 1,
            "size" => null,
            "merchant" => null,
            "link" => null,
                "cargo"=> null,
                "hasCargo" => null
        ];

        return $product;
    }
    /**
     * Extract Patirti
     * @return bool|null|object
     */
    public function extractPatirti() {
        $phpObject = null;
        if(!empty($this->html)) {
            $title = null;
            $price = null;
            $type = null;
            $brand = null;
            try {
                $jsObjectStart = explode("dataLayer.push(", $this->html);
                $jsObject = explode(");", $jsObjectStart[1] );
                $stringObj = $jsObject[0];
                $stringObj = preg_replace('/\s+/', '', $stringObj);
                $stringObj = str_replace("'", '"', $stringObj);
                $stringObj = str_replace('",}]', '"}]', $stringObj);

                $object = json_decode($stringObj);
            }catch (\Exception $e) {
                $file = null;
            }
            $this->html = $this->extractDomElements($this->html);
            try {
                $file = $this->html->find('.thumbitem')[0]->find('[data-zoom-image]')[0]->attr['src'];
            }catch (\Exception $e) {
                $file = null;
            }
        } else {
            return false;
        }
        $product = null;
        if ($object && $object->ecommerce && $object->ecommerce->detail->products && $object->ecommerce->detail->products[0]) {
            $product = (object)[
                "file" => $file,
                "name" => $object->ecommerce->detail->products[0]->name,
                "price"=> $object->ecommerce->detail->products[0]->price,
                "shop" => $object->ecommerce->detail->products[0]->brand,
                "brand"=> $object->ecommerce->detail->products[0]->brand,
                "color"=> null,
                "type" => $object->ecommerce->detail->products[0]->category,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }

        return $product;
    }
    /**
     * Extract Morhipo
     * @return bool|null|object
     */
    public function extractMorhipo() {
        $phpObject = null;
        if(!empty($this->html)) {
            $title = null;
            $price = null;
            $type = null;
            $brand = null;
            try {
                $jsObjectStart = explode('<script type="application/ld+json">', $this->html);
                $jsObject = explode('</script>', $jsObjectStart[2] );
                $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
                $stringObj = str_replace(';', '', $stringObj);
                $object = json_decode($stringObj);
            }catch (\Exception $e) {
                $object = null;
            }
            $this->html = $this->extractDomElements($this->html);
            try {
                $file = $this->html->find('.slides')[0]->find('li')[0]->find('img')[0]->attr['src'];
            }catch (\Exception $e) {
                $file = null;
            }
        } else {
            return false;
        }
        $product = null;
        if ($object && $object->name) {
            $product = (object)[
                "file" => $file,
                "name" => $object->name,
                "price"=> $object->offers->price,
                "shop" => 'Morhipo',
                "brand"=> $object->brand->name,
                "color"=> null,
                "type" => null,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }

        return $product;
    }
    /**
     * Extract Network
     * @return bool|null|object
     */
    public function extractNetwork() {
        $mainobject = null;
        $extendedobject = null;
        if(!empty($this->html)) {
            try {
                $jsObjectStart = explode("dataLayer.push(", $this->html);
                $jsObject = explode(");", $jsObjectStart[3] );
                $stringObj = $jsObject[0];
                $stringObj = preg_replace('/\s+/', '', $stringObj);
                $stringObj = str_replace( "'product_status':1>0?\"in_stock\":\"no_stock\",", '', $stringObj);
                $arr = explode(',', $stringObj);
                $arrCount = count($arr);
                $stringObj = '';
                for($i = 0; $i < $arrCount; $i++) {
                    $key = explode(':', $arr[$i]);
                    $key[0] = str_replace("'", '"', $key[0]);
                    if ($key[0] !== '"category_path"') {
                        $key[1] = str_replace("'", '"', $key[1]);
                    }
                    $stringObj .= $i < $arrCount - 1 ? $key[0].':'.$key[1].',' : $key[0].':'.$key[1];
                }
                $mainobject = json_decode($stringObj);

                $jsObjectStart = explode("var spApiProductDetail = {", $this->html);
                $jsObject = explode("};", $jsObjectStart[1] );
                $stringObj = $jsObject[0];
                $stringObj = preg_replace('/\s+/', '', $stringObj);
                $arr = explode(',', $stringObj);
                $arrCount = count($arr);
                $stringObj = '';
                for($i = 0; $i < $arrCount; $i++) {
                    $key = explode(':', $arr[$i]);
                    $key[0] = '"'.$key[0].'"';
                    if (!empty($key[2])) {
                        $stringObj .= $i < $arrCount - 1 ? $key[0].':'.$key[1].':'.$key[2].',' : $key[0].':'.$key[1].':'.$key[2];
                    } else {
                        $stringObj .= $i < $arrCount - 1 ? $key[0].':'.$key[1].',' : $key[0].':'.$key[1];
                    }
                }
                $extendedobject = json_decode('{'.$stringObj.'}');
            }catch (\Exception $e) {
                $object = null;
            }

        } else {
            return false;
        }
        $product = null;
        if ($mainobject && $mainobject->product_name) {
            $product = (object)[
                "file" => !empty($extendedobject) ? $extendedobject->img : null,
                "name" => !empty($extendedobject) ? $extendedobject->name : null,
                "price"=> !empty($extendedobject) ? $extendedobject->price : null,
                "shop" => 'network',
                "brand"=> 'network',
                "color"=> $mainobject->product_color,
                "type" => $mainobject->product_main_category,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }

        return $product;
    }
    /**
     * Extract Network
     * @return bool|null|object
     */
    public function extractCacharel() {
        $object = null;
        if(!empty($this->html)) {
            try {
                $jsObjectStart = explode('dataLayer.push(', $this->html);
                $jsObjectStart = $jsObjectStart[count($jsObjectStart) - 1];
                $jsObject = explode(');', $jsObjectStart );
                $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
                $stringObj = str_replace("'", '"', $stringObj);
                $stringObj = str_replace('//"detail"actionshaveanoptionallistproperty.', '', $stringObj);
                $stringObj = str_replace('//NameorIDisrequired.', '', $stringObj);
                $object = json_decode($stringObj);
            }catch (\Exception $e) {
                $object = null;
            }
            $this->html = $this->extractDomElements($this->html);
            try {
                $file = $this->html->find('.sliderWrapper')[0]->find('.mask')[0]->find('img')[0]->attr['src'];
            }catch (\Exception $e) {
                $file = null;
            }
        } else {
            return false;
        }
        $product = null;
        if ($object && $object->pname) {
            $product = (object)[
                "file" => !empty($file) ? $file : null,
                "name" => $object->pname,
                "price"=> $object->totalvalue,
                "shop" => $object->pbrand,
                "brand"=> $object->pbrand,
                "color"=> null,
                "type" => $object->pname,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }

        return $product;
    }
    /**
     * Extract Network
     * @return bool|null|object
     */
    public function extractKigili() {
        $object = null;
        if(!empty($this->html)) {
            try {
                $jsObjectStart = explode('<script type="application/ld+json">', $this->html);
                $jsObject = explode('</script>', $jsObjectStart[1] );
                $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
                $stringObj = str_replace("'", '"', $stringObj);

                $object = json_decode($stringObj);
            }catch (\Exception $e) {
                $object = null;
            }
        } else {
            return false;
        }
        $product = null;
        if ($object && $object->name) {
            $product = (object)[
                "file" => $object->image[0],
                "name" => $object->name,
                "price"=> $object->offers->price,
                "shop" => $object->offers->seller->name,
                "brand"=> $object->brand->name,
                "color"=> null,
                "type" => $object->name,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }

        return $product;
    }
    /**
     * Extract Network
     * @return bool|null|object
     */
    public function extractGidiyor() {
        $file = null;
        $title = null;
        $price = null;
        $brand = null;
        $type = null;
        if(!empty($this->html)) {
            $this->html = $this->extractDomElements($this->html);
            try {
                $file = $this->html->find('.product-img')[0]->find('img')[0]->attr['src'];
            }catch (\Exception $e) {
                $file = null;
            }
            try {
                $title= $this->html->find('.h1-container')[0]->find('.title')[0]->innertext();
            }catch (\Exception $e) {
                $title = null;
            }
            try {
                $price = $this->html->find('.product-price-info-con')[0]->find('.price-css')[0]->innertext();
            }catch (\Exception $e) {
                $price = null;
            }
            try {
                $brand = $this->html->find('.brand-url')[0]->find('strong')[0]->innertext();
            }catch (\Exception $e) {
                $brand = null;
            }
            try {
                $typeVar = $this->html->find('.robot-productPage-breadcrumb-hiddenBreadCrumb')[0]->find('li');
                $typeVarCount = count($typeVar);
                $type = $typeVar[$typeVarCount - 2]->find('a')[0]->innertext();
            }catch (\Exception $e) {
                $type = null;
            }
        } else {
            return false;
        }
        $product = null;
        if (!empty($title)) {
            $product = (object)[
                "file" => $file,
                "name" => $title,
                "price"=> $price,
                "shop" => 'gittigidiyor',
                "brand"=> $brand,
                "color"=> null,
                "type" => $type,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }

        return $product;
    }
    /**
     * Extract Urun N11
     * @return bool|null|object
     */
    public function extractn11() {
        $object = null;
        if(!empty($this->html)) {
            try {
                $jsObjectStart = explode('dataLayer.push(', $this->html);
                $jsObjectStart = $jsObjectStart[1];
                $jsObject = explode(');', $jsObjectStart );
                $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
                $object = json_decode($stringObj);
            }catch (\Exception $e) {
                $object = null;
            }
        } else {
            return false;
        }
        $product = null;
        if ($object && !empty($object->pName)) {
            $product = (object)[
                "file" => !empty($object->pImageUrl) ? $object->pImageUrl : null,
                "name" => $object->pName,
                "price"=> !empty($object->pDiscountedPrice) ? $object->pDiscountedPrice : $object->pOriginalPrice,
                "shop" => 'n11',
                "brand"=> 'n11',
                "color"=> null,
                "type" => $object->pCatMain,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }

        return $product;
    }
    /**
     * Extract Urun N11
     * @return bool|null|object
     */
    public function extractDefacto() {
        $file = null;
        $title = null;
        $price = null;
        $brand = 'DeFacto';
        $color = null;
        $type = null;
        if(!empty($this->html)) {
            $this->html = $this->extractDomElements($this->html);
            try {
                $file = 'https:'.$this->html->find('.productZoomImage')[0]->attr['src'];
            }catch (\Exception $e) {
                $file = null;
            }
            try {
                $title = $this->html->find('.product-title')[0]->find('h1')[0]->innertext();
            }catch (\Exception $e) {
                $title = null;
            }
            try {
                $color = $this->html->find('.product-code')[0]->find('a')[0]->innertext();
            }catch (\Exception $e) {
                $color = null;
            }
            try {
                $price = $this->html->find('.sale')[0]->find('span')[0]->attr['content'];
            }catch (\Exception $e) {
                $price = null;
            }
        } else {
            return false;
        }
        $product = null;
        if (!empty($title)) {
            $product = (object)[
                "file" => $file,
                "name" => $title,
                "price"=> $price,
                "shop" => 'DeFacto',
                "brand"=> $brand,
                "color"=> $color,
                "type" => $type,
                "quantity" => 1,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }

        return $product;
    }
    /**
     * Extract Urun N11
     * @return bool|null|object
     */
    public function extractMango() {
       $object = null;
        if(!empty($this->html)) {
            $jsObjectStart = explode('var dataLayerV2Json = ', $this->html);
            $jsObjectStart = $jsObjectStart[1];
            $jsObject = explode(';', $jsObjectStart );
//            $stringObj = preg_replace('/\s+/', '', $jsObject[0]);
            $stringObj = $jsObject[0];
            $object = json_decode($stringObj);
            $object = $object->ecommerce->detail;
        } else {
            return false;
        }
        $product = null;

        if (!empty($object) && !empty($object->products)) {
            $object = $object->products;
            $product = (object)[
                "file" => $object->photos->frontal,
                "name" => $object->simpleName,
                "price"=> $object->salePrice,
                "shop" => 'Mango',
                "brand"=> 'Mango',
                "color"=> $object->colorId,
                "type" => null,
                "quantity" => 1,
//                "size" => $object->sizeAvailability,
                "size" => null,
                "merchant" => null,
                "link" => null,
                "cargo"=> null,
                "hasCargo" => null
            ];
        }

        return $product;
    }
}
