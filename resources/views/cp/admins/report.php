<h1><?= $user->uniqid. " ".$user->name." ".$user->surname  ?> </h1>
<h2><?= $begin_date. " və ".$end_date ?> tarixləri arasında istifadəçinin bağlamaları</h2>
<table border="1">
    <tr style="font-weight: bold">
        <td>Bağlama nömrəsi</td>
        <td>Çatdırılma qiyməti</td>
        <td>Bağlama qiyməti</td>
        <td>Bağlamanın Çəkisi</td>
        <td>Mağaza</td>
        <td>Bağlamanın içindəki</td>
    </tr>
    <?php
        $shipping_price = 0;
        $price = 0;
        $weight = 0;
        foreach ($invoices as $key=>$invoice){
    ?>
            <tr>
                <td><?= $invoice["purchase_no"]?></td>
                <td><?= $invoice["shipping_price"]?>$</td>
                <td><?= $invoice["price"]?>$</td>
                <td><?= $invoice["weight"]?>kq</td>
                <td><?= $invoice["shop_name"]?></td>
                <td><?= $invoice["product_type_name"]?></td>
            </tr>
    <?php
            $shipping_price +=  $invoice["shipping_price"];
            $price +=  $invoice["price"];
            $weight +=  $invoice["weight"];
        } ?>

    <tr style="font-weight: bold">
        <td>Cəmi: </td>
        <td><?= $shipping_price?> $</td>
        <td><?= $price?> $</td>
        <td><?= $weight?> kq</td>
        <td></td>
        <td></td>
    </tr>
</table>

<h1>Cəmi xərcləmə: <?= ($shipping_price+$price)?>$</h1>

