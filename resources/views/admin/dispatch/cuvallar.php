<?php include("header.php");?>

<?php

//  $orders_statuses = $orders->getOrderStatuses();

$limit = 20;
$get = @intval($_GET["get"]);
if(strlen($get) > 2){
    $get=1;
}
if(empty($get) or !is_numeric($get)) {
    $get = 1;
}

if(isset($_GET['delete'])){
    mysqli_query($database,"DELETE FROM cms_cuval_o WHERE id='{$_GET['delete']}'");
    echo '<div style="display:inline-block;width: 100%;margin: 10px 0;padding: 0 10px;"><div class="alert alert-success"><h1 style="margin: 0;">Silindi</h1></div></div>';
    echo '<script>
        setTimeout(function() {
            window.location.href = "/mvrtr/cuvallar.php";
        }, 1500);
    </script>';
}

if(isset($_GET['delete_cuval'])){
    $sql_check = mysqli_query($database,"SELECT * FROM cms_cuval_o where cuval_id = ".$_GET['delete_cuval']);
    if($sql_check->num_rows>0){
        echo '<div style="display:inline-block;width: 100%;margin: 10px 0;padding: 0 10px;"><div class="alert alert-danger"><h1 style="margin: 0;">Bu çuvalda sipariş var.Silinemez!</h1></div></div>';
        echo '<script>
        setTimeout(function() {
            window.location.href = "/mvrtr/cuvallar.php";
        }, 1500);
    </script>';
    }else{
        mysqli_query($database,"DELETE FROM cms_cuval_n WHERE id='{$_GET['delete_cuval']}'");
        echo '<div style="display:inline-block;width: 100%;margin: 10px 0;padding: 0 10px;"><div class="alert alert-success"><h1 style="margin: 0;">Silindi</h1></div></div>';
        echo '<script>
        setTimeout(function() {
            window.location.href = "/mvrtr/cuvallar.php";
        }, 1500);
    </script>';
    }

}

$count = mysqli_num_rows(mysqli_query($database,"SELECT id FROM cms_cuval_n"));
$countRows    = ceil($count / $limit);
$start  = ($get-1)*$limit;
$get_result = mysqli_query($database,"SELECT * FROM cms_cuval_n WHERE `status`=1 order by `id` desc LIMIT $start,$limit");

?>

	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">ÇUVALLAR
                <a href="cuval_add.php" class="btn btn-sm btn-success pull-right" style="margin-top: -5px;"><i class="fa fa-plus"></i> Çuval Əlavə Et</a>
                <a class="btn btn-sm btn-warning pull-right cuvalSendBtn" data-toggle="modal" data-target="#cuvalSend" style="margin-top: -5px;margin-right: 10px;">Seçilən çuvalları gönder</a>
            </div>
			<div class="panel-body">
			<div class="container">
        <?php
          $i=1;
          if($get_result->num_rows>0):
          while($row = mysqli_fetch_assoc($get_result))
          {
              $sql = mysqli_query($database,"SELECT * FROM cms_cuval_o where cuval_id = ".$row['id']);
            ?>
            <div class="panel-group" id="accordion" style="width: 95%;">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title" style="display: inline-block;">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$i?>"><?=$row['name']?></a>
                            <form method="post" id="cuvalSForm" style="display: inline-block;text-align: center">
                                <input type="checkbox" name="cuvalRow[]" value="<?=$row['id']?>" style="width: 18px;
    height: 18px;
    margin-left: 10px;
    top: 4px;
    position: relative;
    margin-bottom: 10px;">
                            </form>
                        </h4>
                        <div class="pull-right" style="display: inline-block;margin-top: -7px;">
                            <a class="btn btn-sm btn-info" href="cuval_edit.php?id=<?=$row['id']?>">Çuval düzenle</a>
                            <a class="btn btn-sm btn-success" href="cuval_o_add.php?id=<?=$row['id']?>">Sipariş ilave et</a>
                            <a href="cuval_detail.php?id=<?=$row['id']?>" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                            <?php
                            if($sql->num_rows==0):
                            ?>
                                <a class="btn btn-sm btn-danger" href="?delete_cuval=<?=$row['id']?>">Çuvalı sil</a>
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <div id="collapse<?=$i?>" class="panel-collapse collapse">
                        <table class="table" style="width: 98%; margin: 10px;">
                            <thead>
                                <tr>
                                  <th style="width: 90%">Sipariş</th>
                                  <th>İndir</th>
                                  <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            while($row_cuv_p = mysqli_fetch_assoc($sql)){
                                $get_cuval_order = mysqli_query($database,"select * from cms_orders where id=".$row_cuv_p['order_id']);
                                $row_cuval_order = mysqli_fetch_assoc($get_cuval_order);
                                $trtag = ($row_cuval_order["shop"]!="")?$row_cuval_order["shop"]:"-";
                                $trfile = ($row_cuval_order["file"]!= "")?"<a href='/upload/invoice/".$row_cuval_order["file"]."'><i class='fa fa-file-pdf-o' aria-hidden='true'></i></a>":"Bulunamadı";
                                echo '<tr>
                                    <td>'.$trtag."</td>
                                    <td>".$trfile."</td>
                                    <td>
                                        <a href='?delete=".$row_cuv_p['id']."' class='btn btn-danger rounded' title='Sil'><i class='fa fa-remove'></i></a>
                                    </td>
                                </tr>";

                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
            $i++;
          }
          else:
            echo '<h1 class="text-center">Çuval yoxdur.</h1>';
          endif;
        ?>
		</div>
        <ul class="pagination" style="margin-top: 20px;">
            <?php
            if($count > $limit) :
                $x = 2; // akrif sehifedan önceki/sonraki sehife gösterim sayisi
                $lastP = ceil($count/$limit);
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

    <!-- Modal -->
    <div class="modal fade" id="cuvalSend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Çuvallar hakkında ilave melumatlar</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="" id="cuvalForm">
                        <input type="hidden" name="cuvalIds" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Çuval sayısı</label>
                                    <input class="form-control" type="text" value="" name="sacks_count" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Gönderim tarihi</label>
                                    <input class="form-control" type="date" value="" name="sending_date">
                                </div>
                                <div class="form-group">
                                    <label for="">ETGB numarası</label>
                                    <input class="form-control" type="text" name="etgb_number" placeholder="ETGB numarası">
                                </div>
                                <div class="form-group">
                                    <label for="">Paket sayi</label>
                                    <input class="form-control" type="text" name="pack_total" placeholder="ETGB numarası" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Toplam ağırlık</label>
                                    <input class="form-control" type="text" name="total_weight" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Konşimento numarası</label>
                                    <input class="form-control" type="text" name="waybill_number" placeholder="Konşimento numarası">
                                </div>
                                <div class="form-group">
                                    <label for="">Gonderim ucreti</label>
                                    <input class="form-control" type="text" name="shipping_costs" placeholder="Gonderim ucreti">
                                </div>
                                <div class="form-group">
                                    <label for="">ETGB tarihi</label>
                                    <input class="form-control" type="date" name="etgb_date">
                                </div>
                                <div class="form-group">
                                    <label for="">Paketleme fiyati</label>
                                    <input class="form-control" type="text" name="pack_price" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Toplam fiyat</label>
                                    <input class="form-control" type="text" name="total_price" value="">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Bağla</button>
                    <button type="submit" class="btn btn-primary cuvalFormBTN" form="cuvalForm">Gönder</button>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php");?>