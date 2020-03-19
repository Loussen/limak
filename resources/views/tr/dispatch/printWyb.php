<?php
include_once "../config.php";

$date1 = @$_GET['date1'];
$date2 = @$_GET['date2'];

//Functions

function currencyChanger($valute1="USD",$valute2="AZN")
{
    $url = "http://valyuta.com/api/get_rate_current_all/$valute1/$valute2";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
    $result = json_decode(curl_exec($ch), true);

    return $result[0]['result'];
}

function cmtoinch($val) {

    if ($val == "1") {
        return 1;
    } else {
        return 2.54;
    }
}

function volumetricweight($width = null, $length = null, $height = null, $weight = null) {

    if ($width && $length && $height) {

        $v_w = ($width * $length * $height) / 6000;
        if ($v_w >= $weight) {
            return $v_w;
        } else {
            return $weight;
        }
    } else {
        return $weight;
    }
}


function GetPrice($w = null, $l = null, $h = null, $weight = null, $unit = '1', $codes, $tarif = true)
{
    $ceki = volumetricweight($w * cmtoinch($unit), $l * cmtoinch($unit), $h * cmtoinch($unit), $weight);

    $p1 = 3.8;
    $p2 = 5;

    if($tarif){
        $p1 = 4.1;
        $p2 = 6.5;
    }

    if ($codes['type'] == 'size'){

        if ($ceki > 0 and $ceki <= 0.5) {
            return $p1;
        } elseif ($ceki > 0.5 && $ceki<=1) {
            return $p2;
        } else {
            return round($ceki * $p2,2);
        }
    }else{

        $ceki = 	$weight;

        if ($ceki > 0 and $ceki <= 0.5) {
            return $p1;
        } elseif ($ceki > 0.5 && $ceki<=1) {
            return $p2;
        } else {
            return round($ceki * $p2,2);
        }

    }

}

if(isset($_GET['numRow'])){
    $where_ad = ' AND cms_orders.id='.$_GET['numRow'].' ';
    $where_ad2 = ' and id='.$_GET['numRow'].' ';
}else{
    $where_ad = '';
    $where_ad2 = '';
}
//Functions

$ar = array('1'=>'Geyim','2'=>'Ayaqqabı','3'=>'Digər','4'=>'Kitab');

$where = "delivery IN(2,7,8) and cms_orders.status='1' and ";

if($date1!='' and $date2!='')
    $where .= "date2>='$date1' and date2<='$date2'";
elseif($date1!='')
    $where .= "date2>='$date1'";
elseif($date2!='')
    $where .= "date2<='$date2'";
else
    $where .= "1=1";

/*$get_orders = mysqli_query($database,"SELECT *,cms_orders.id as o_id,cms_users.id as u_id,cms_users.name as u_name, sum(cms_orders.price) as price, sum(cms_orders.weight) as total_weight FROM cms_orders
                            INNER JOIN cms_users on cms_users.id=cms_orders.users_id
                            INNER JOIN cms_valute on cms_valute.id=cms_orders.valute_id
                            where $where {$where_ad} group by cms_orders.users_id  order by o_id asc
                            ");*/

$get_orders = mysqli_query($database,"SELECT *,cms_orders.id as o_id,cms_users.id as u_id,cms_users.name as u_name FROM cms_orders
                            INNER JOIN cms_users on cms_users.id=cms_orders.users_id
                            INNER JOIN cms_valute on cms_valute.id=cms_orders.valute_id
                            where $where {$where_ad} order by o_id asc
                            ");

$try = mysqli_fetch_assoc(mysqli_query($database,"SELECT course FROM cms_valute WHERE id=3"));
$usd = mysqli_fetch_assoc(mysqli_query($database,"SELECT course FROM cms_valute WHERE id=1"));
?>
<?php

define('CODE128A_START_BASE', 103);
define('CODE128B_START_BASE', 104);
define('CODE128C_START_BASE', 105);
define('STOP', 106);

function code128BarCode ( $code , $density = 1 ) {

    //Creates an array for alphanumeric codes
    //Formatted as numerical representations of "B S B S B S", where B is the number of lines and S is the number of spaces

    $code128_bar_codes  	= 	array(
        212222, 222122, 222221, 121223, 121322, 131222, 122213, 122312, 132212, 221213, 221312, 231212, 112232, 122132, 122231, 113222, 123122, 123221, 223211, 221132, 221231,
        213212, 223112, 312131, 311222, 321122, 321221, 312212, 322112, 322211, 212123, 212321, 232121, 111323, 131123, 131321, 112313, 132113, 132311, 211313, 231113, 231311,
        112133, 112331, 132131, 113123, 113321, 133121, 313121, 211331, 231131, 213113, 213311, 213131, 311123, 311321, 331121, 312113, 312311, 332111, 314111, 221411, 431111,
        111224, 111422, 121124, 121421, 141122, 141221, 112214, 112412, 122114, 122411, 142112, 142211, 241211, 221114, 413111, 241112, 134111, 111242, 121142, 121241, 114212,
        124112, 124211, 411212, 421112, 421211, 212141, 214121, 412121, 111143, 111341, 131141, 114113, 114311, 411113, 411311, 113141, 114131, 311141, 411131, 211412, 211214,
        211232, 23311120
    );

    //Get the width and height of the barcode
    //Determine the height of the barcode, which is >= .5 inches

    $width			=	(((11 * strlen($code)) + 35) * ($density/72)); // density/72 determines bar width at image DPI of 72
    $height			=	($width * .15 > .7) ? $width * .15 : .7;

    $px_width		=	round($width * 72);
    $px_height		=	($height * 100);

    //Create a true color image at the specified height and width
    //Allocate white and black colors

    $img		=	imagecreatetruecolor($px_width, $px_height);
    $white     	=	imagecolorallocate($img, 255, 255, 255);
    $black     	=	imagecolorallocate($img, 0, 0, 0);

    //Fill the image white
    //Set the line thickness (based on $density)

    imagefill($img, 0, 0, $white);
    imagesetthickness($img, $density);

    //Create the checksum integer and the encoding array
    //Both will be assembled in the loop

    $checksum	=	CODE128B_START_BASE;
    $encoding	=	array($code128_bar_codes[CODE128B_START_BASE]);

    //Add Code 128 values from ASCII values found in $code

    for($i = 0; $i < strlen($code); $i++) {

        //Add checksum value of character

        $checksum	+=	(ord(substr($code, $i, 1)) - 32) * ($i + 1);

        //Add Code 128 values from ASCII values found in $code
        //Position is array is ASCII - 32

        array_push($encoding, $code128_bar_codes[(ord(substr($code, $i, 1))) - 32]);

    }

    //Insert the checksum character (remainder of $checksum/103) and STOP value

    array_push($encoding, $code128_bar_codes[$checksum%103]);
    array_push($encoding, $code128_bar_codes[STOP]);

    //Implode the array as string

    $enc_str	=	implode($encoding);

    //Assemble the barcode

    for($i = 0, $x = 0, $inc = round(($density/72) * 100); $i < strlen($enc_str); $i++) {

        //Get the integer value of the string element

        $val	=	intval(substr($enc_str, $i, 1));

        //Create lines/spaces
        //Bars are generated on even sequences, spaces on odd

        for($n = 0; $n < $val; $n++, $x+=$inc) { if($i%2 == 0) imageline($img, $x, 0, $x, $px_height, $black); }

    }

    //Return the image

    return $img;

}

?>
<style>
    span{
        position: absolute;
        font-size: 14px;
        letter-spacing: 2px;
    }
</style>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name='viewport' content='width=device-width, user-scalable=no' />
    <meta name='format-detection' content='telephone=yes'>
    <!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"-->
    <link href='/mvrtr/waybill/css/bootstrap.css' rel='stylesheet'>
    <link href='/mvrtr/waybill/css/style.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="/mvrtr/waybill/js/bootstrap.min.js"></script>
    <meta name='description' content='' />
    <title>Book</title>
</head>
<body style="position:relative;">
    <?php
    $i = 1;
    $qaime = 346;
    $decoration = 'none';
    while($value=mysqli_fetch_assoc($get_orders))
    {

        if(
                $value["addedbyuser"] == 1 ||
                $value['delivery'] == 7 ||
                preg_match("/flo/i", $value['shop'])
        )
            $decoration = 'underline';
        
        // Barcode
        $img            =   code128BarCode($value['o_id']."-".$qaime."-mover", 1);

        //Start output buffer to capture the image
        //Output PNG image

        ob_start();
        imagepng($img);

        //Get the image from the output buffer

        $output_img     =   ob_get_clean();
        // Barcode end

        if($value['type']==3)
            $other_type = "(".$value['other_type'].")";
        else
            $other_type = "";

        ?>
        <span style="top:2cm; left:3cm;"><?=date('Y-m-d');?></span>
        <span style="top:2cm; left:11.6cm;"><?=$value['user_key'];?></span>
        <span style="top:2.8cm; left:2.5cm;">Mover Internet Hizmetleri</span>

        <span style="top:3.5cm; left:3cm;"><?=$value['shop'];?></span>
        <span style="top:2.8cm; left:10cm;">5318964270</span>
        <span style="top:4.1cm; left:2cm; width: 480px;">İnkilap mh. Küçüksu cd. Yelkenciler sk. Vardar ap. NO :14/3</span>
        <span style="top:6.3cm; left:1.5cm;">Istanbul</span>
        <span style="top:6.3cm; left:9cm;">34768</span>
        <span style="top:6.3cm; left:14.5cm;">TR</span>



        <span style="top:7.7cm; left:2cm;"><?=$value['u_name'];?> <?=$value['surname'];?></span>
        <span style="top:7.7cm; left:12cm;"><?=$value['mobile'];?></span>
        <span style="top:9cm; left:1cm; width: 450px;"><?=$value['address']?></span>
        <span style="top:11.3cm; left:1cm;">Baku</span>
        <span style="top:11.3cm; left:9cm;">AZ1000</span>
        <span style="top:11.3cm; left:14.2cm;">AZ</span>


        <span style="top:13cm; left:0.3cm;">MOVER INTERNET HIZMETLERI</span>
        <span style="top:13cm; left:9.2cm;"><?=date('Y-m-d');?></span>


        <span style="top:2cm; left: 18.1cm;">X</span>
        <span style="top:4cm; left: 25cm;">X </span>
        <span style="top:5.7cm; left:19cm;">1</span>
        <span style="top:5.7cm; left: 20.3cm;"><?=$value['weight']?></span>
        <span style="top:5.7cm; left: 24cm;"><?=$value['length']?></span>
        <span style="top:5.7cm; left: 26.5cm;"><?=$value['width']?> </span>
        <span style="top:5.7cm; left: 28.3cm;"><?=$value['height']?></span>
        <span style="top:5.5cm; left: 30cm;">X</span>

        <span style="top:7.5cm; left:21.5cm;">X</span>
        <span style="top:7.5cm; left:28.7cm;">X</span>

        <?php
            $codes = getCodes($value['type']);
        ?>
        <span style="top:8.7cm; left:19.8cm;"><?=$codes['productname']?> </span>
        <span style="top:8.7cm; left:23.8cm;"><?=$codes['productnum']?> </span>
        <span style="top:8.7cm; left:28cm;">TR</span>
        <span style="top:8.7cm; left:30.7cm;"><?=$value['price'];?> TL</span>
        <span style="top:9.3cm; left:23cm;">Shipping Price</span>
        <span style="top:9.3cm; left:30.7cm;"><?=3.7*GetPrice($value['width'], $value['length'], $value['height'], $value['weight'], 1, $codes, $value['date1'] > '2018-02-15' );?> TL (<?=GetPrice($value['width'], $value['length'], $value['height'], $value['weight'], 1, $codes, $value['date1'] > '2018-02-15' );?> $)</span>
        <span style="top:9.9cm; left:23cm;">Total Value for Customs</span>
        <span style="top:9.9cm; left:30.7cm;"><?=((float) $value['price'] + 3.7*GetPrice($value['width'], $value['length'], $value['height'], $value['weight'], 1, $codes, $value['date1'] > '2018-02-15' ))?> TL </span>


        <span style="top:10.8cm; left:19cm;"><?=$value['user_key'];?></span>
        <span style="top11cm; left:26cm;">X</span>

<?php } ?>

</body>
</html> 