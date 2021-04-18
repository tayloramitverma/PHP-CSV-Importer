<?php
    /*Include Database Connection File*/
    include_once "config/db-config.php";
    /*-Start-Create Database Connection*/
    $conn = new DBController;
    $con = $conn->connect();
    /*-Start-Pagination*/
    if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
        $page_no = $_GET['page_no'];
    } else {
        $page_no = 1;
    }
    $total_records_per_page = 15;
    $offset = ($page_no - 1) * $total_records_per_page;
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    $result_count = mysqli_query($con,"SELECT COUNT(*) As total_records FROM `tb_product`");
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1;
    /*-End-Pagination*/
?>

<!doctype html>
<html>
<head>
    <title>CSV Uploader</title>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.6/sweetalert2.min.css" rel="stylesheet"></link>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.6/sweetalert2.min.js"></script>
    <style><?= include_once "assets/css/style.css"?> </style>
</head>

<body>
<div id="ajaxProcessing" class="loader-overlay" style="display:none">
    <div>
        <i class="fa fa-spinner fa-pulse fa-5x"></i>
        <br>
        <h2>Processing...</h2>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <input type="file" name="file" id="file">
            <div class="upload-area" id="uploadfile">
                <h1>Drag and Drop file here<br/>Or<br/>Click to select file</h1>
            </div>
           <div class="error"></div>
        </div>
        <div class="col-md-12 text-center" style="margin-top: 10px;">
            <button id="uploadProductCsv" class="btn btn-warning">Upload</button>
            <button class="btn" style="margin-left: 20px;">Cancel</button>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 20px;">
    <div class="row">
        <div class="col-md-12 text-center" style="margin-bottom: 20px; height: 35px;">
            <a href="javascript:void(0)" style="display:none" id="jsDataTableActionDelete" class="btn  btn-danger" data-toggle="tooltip" title="" data-original-title="Delete Selected"> <i class="fa fa-trash"></i> </a>
         </div>
        <div class="col-md-12">
            <table class="table table-striped table-dark  table-bordered">
                <?php
                   $sql = "SELECT * FROM `tb_product` LIMIT $offset, $total_records_per_page";
                    if ($result = mysqli_query($con, $sql)) {
                        if (mysqli_num_rows($result) > 0) { ?>
                            <thead>
                            <tr>
                               <th class="table-action-td">
                                    <div class="btn-group">
                                        <a class="btn btn-primary"><i datatable-check="unChecked" id="jsDataTableSelectAllOrNone" class="fa fa-square-o fa-f-20 select-icon" aria-hidden="true"></i> </a>
                                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown"aria-haspopup="true" aria-expanded="false"><span class="fa fa-caret-down fa-f-20 select-icon"></span></a>
                                        <ul class="dropdown-menu select-dropdown">
                                            <li><a  class="select-li" href="javascript:void(0);" id="jsDataTableSelectAll">All</a></li>
                                            <li><a class="select-li" href="javascript:void(0);" id="jsDataTableSelectNone">None</a></li>
                                         </ul>
                                    </div>
                                </th>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">SKU</th>
                                <th scope="col">Price</th>
                                <th scope="col">Discount</th>
                                <th scope="col">Currency</th>
                                <th scope="col">Currency Symbol</th>
                                <th scope="col" class="text-center">Buy</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($row = mysqli_fetch_array($result)) { ?>
                                <tr>
                                    <th scope="row"> <i datatable-check="false" datatable-status="" datatable-id="<?= $row['id'] ?>" class="fa fa-square-o fa-f-24 jsDataTableSelect ajax-link select-icon ml-15" aria-hidden="true"></i></td>
                                    <td><img src="<?= $row['product_image']; ?>" width="50px" height="50px"/></td>
                                    <td><?= $row['product_name']; ?></td>
                                    <td><?= $row['sku']; ?></td>
                                    <td><?= $row['product_price']; ?></td>
                                    <td><?= $row['product_discount']; ?></td>
                                    <td><?= $row['price_currency']; ?></td>
                                    <td><?= $row['price_currency_symbol']; ?></td>
                                    <td class="text-center"><a href="<?= $row['product_url']; ?>" target="_blank"> <button class="btn btn-sm btn-success">BUY</button> </a></td>
                                </tr>
                            <?php }
                            mysqli_free_result($result);
                            ?>
                            <?php
                                if($total_records_per_page < $total_records){?>
                                 <tr>
                                     <th colspan="9" class="text-center">
                                         <ul class="pagination">
                                            <li style='padding: 10px 20px 0px;'><strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong></li>
                                             <?php if($page_no > 1){
                                                 echo "<li><a href='?page_no=1'>First Page</a></li>";
                                             } ?>
                                             <li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
                                                 <a <?php if($page_no > 1){
                                                     echo "href='?page_no=$previous_page'";
                                                 } ?>>Previous</a>
                                             </li>
                                             <li <?php if($page_no >= $total_no_of_pages){
                                                 echo "class='disabled'";
                                             } ?>>
                                                 <a <?php if($page_no < $total_no_of_pages) {
                                                     echo "href='?page_no=$next_page'";
                                                 } ?>>Next</a>
                                             </li>
                                             <?php if($page_no < $total_no_of_pages){
                                                 echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
                                             } ?>
                                         </ul>
                                     </th>
                                 </tr>
                              <?php } ?>
                            </tbody>
                        <?php }
                    } else { ?>
                        <tr>
                            <td class="text-danger">ERROR: Could not able to execute <?= $sql; ?> <?= mysqli_error($con); ?></td>
                        </tr>
                        </tfoot>
                    <?php }
                    $conn->close($con);
                ?>
                
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script><?= include_once "assets/js/custom-datatable.js"?></script>
<script><?= include_once "assets/js/main.js"?></script>


</body>
</html>