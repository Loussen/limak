<?php
include("header.php");
$database=mysqli_connect('localhost','mover_us','r3ni@io3o!','mover') or die(mysqli_error() );
mysqli_set_charset($database,"utf8");

function pagenation($sehife_goster,$sehife_sayi,$sehife,$url)
{
    $pgn .='<ul class="pagination justify-content-center">';
    $en_az_orta = ceil($sehife_goster/2);
    $en_cox_orta = ($sehife_sayi +1) - $en_az_orta;

    $sehife_orta = $sehife;
    if($sehife_orta < $en_az_orta) $sehife_orta = $en_az_orta;
    if($sehife_orta > $en_cox_orta) $sehife_orta = $en_cox_orta;

    $sol_sehifeler = round($sehife_orta - (($sehife_goster-1) / 2));
    $saq_sehifeler = round((($sehife_goster-1) / 2) + $sehife_orta);

    if($sol_sehifeler < 1) $sol_sehifeler = 1;
    if($saq_sehifeler > $sehife_sayi) $saq_sehifeler =$sehife_sayi ;

    if($sehife != 1){ $pgn .='<li class="page-item"><a class="page-link" href="'.$url.'?p=1">Ilk səhifə</a></li>';}

    if($sehife != 1){ $pgn .='<li class="page-item"><a class="page-link" href="'.$url.'?p='.($sehife-1).'">Əvvəlki</a></li>';}

    for($s = $sol_sehifeler; $s <= $saq_sehifeler; $s++) {
        if($sehife == $s) {
            $pgn .='<li class="page-item active"><a class="page-link" href="'.$url.'?p='.$s.'">'.$s.'<span class="sr-only">(current)</span></a></li>';
        } else {
            $pgn .='<li class="page-item"><a class="page-link" href="'.$url.'?p='.$s.'">'.$s.'</a></li>';
        }
    }

    if($sehife != $sehife_sayi){ $pgn .='<li class="page-item"><a class="page-link" href="'.$url.'?p='.($sehife+1).'">Sonraki</a></li>';}

    if($sehife != $sehife_sayi){ $pgn .='<li class="page-item"><a class="page-link" href="'.$url.'?p='.$sehife_sayi.'">Son səhifə</a></li>';}

    $pgn .='</ul>';

    return $pgn;

}
?>
    <div class="container">
        <div class="row">
            <h1>Gönderilen çuvallar</h1>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Çuval sayısı</th>
                        <th>Konşimento numarası</th>
                        <th>Gonderim ucreti</th>
                        <th>Gönderim tarihi</th>
                        <th>ETGB numarası</th>
                        <th>ETGB tarihi</th>
                        <th>Toplam ağırlık</th>
                        <th>Toplam fiyat</th>
                        <th>Paket sayi</th>
                        <th>Paketleme fiyati</th>
                        <th>Download</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?PHP
                    $count = mysqli_fetch_array(mysqli_query($database,'select count(*) as count from `mover`.`cms_cuval_send`'));

                    $sehifede		= 10;
                    $rows			= $count['count'];
                    $sehife_sayi	= ceil($rows/$sehifede);
                    $sehife			= isset($_GET['get']) ? $_GET['get'] : 1;

                    if($sehife>$sehife_sayi){$sehife=$sehife_sayi;}
                    if($sehife<1){$sehife=1;}

                    $limit = $sehife*$sehifede-$sehifede;

                    $query = mysqli_query($database,'select * from `cms_cuval_send` order by `id` DESC limit '.$limit.','.$sehifede);
                    $i=1;
                    while($r = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><a href="cuval_send_list.php?id=<?=$r['id']?>"><?=$r['sacks_count']?></a></td>
                            <td><?=$r['waybill_number']?></td>
                            <td><?=$r['shipping_costs']?></td>
                            <td><?=$r['sending_date']?></td>
                            <td><?=$r['etgb_number']?></td>
                            <td><?=$r['etgb_date']?></td>
                            <td><?=$r['total_weight']?></td>
                            <td><?=$r['total_price']?></td>
                            <td><?=$r['pack_total']?></td>
                            <td><?=$r['pack_price']?></td>
                            <td><a class="btn btn-info" href="download.php?id=<?=$r['id']?>">Download</a> </td>
                        </tr>
                        <?php
                        $i++;
                    }
                        ?>
                </tbody>
            </table>
            <ul class="pagination" style="margin-top: 20px;">
            <?php
            if($count['count'] > $limit) :
                $x = 2; // akrif sehifedan önceki/sonraki sehife gösterim sayisi
                $lastP = ceil($count['count']/$limit);
                if($get > 1){
                    $evvel = $get-1;
                    echo "<li><a href='?get=$evvel'>&laquo;</a></li>";
                }
                if($get==1) {
                    echo"<li class='disabled'><a href='#'>&laquo;</a></li>";
                    echo "<li class='active'><a href='#'>1</a></li>";
                }else{
                    echo "<li><a href='?get=1'>1</a></li>";
                }
                // "..." veya direkt 2
                if($get-$x > 2) {
                    // echo "...";
                    $i = $get-$x;
                } else {
                    $i = 2;
                }
                for($i; $i<=$get+$x; $i++) {
                    if($i==$get) echo "<li class='active'><a href='#'>$i</a></li>";
                    else echo "<li><a href='?get=$i'>$i</a></li>";
                    if($i==$lastP) break;
                }

                if($get < $lastP){
                    $sonraki = $get+1;
                    echo "<li><a href='?get=$sonraki'>&raquo;</a></li>";
                }
            endif;
            ?>
        </ul>
        </div>
    </div>

<?php include("footer.php");?>