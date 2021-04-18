<?php
    if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        echo "Not Allowed";
        die;
    }
    /*Include Database Connection File*/
    include_once "config/db-config.php";
    /*Return Response On Success*/
    /*-Start-Create Database Connection*/
    $conn = new DBController;
    $con = $conn->connect();
    /*-End-Create Database Connection*/
    function exitWithSuccess($data = null)
    {
        echo json_encode(['status' => (bool)true, 'data' => (array)$data, 'message' => (string)'OK']);
        exit;
    }
    
    /*Return Response On Failure*/
    function exitWithDanger($error = null)
    {
        echo json_encode(['status' => (bool)false, 'data' => (array)[], 'message' => (string)(($error != '') ? $error : 'Oh snap! Something went wrong.')]);
        exit;
    }
    
    /*Remove Special Char For Security*/
    function RemoveSpecialChar($str)
    {
        $res = str_ireplace(array('\'', '"', ',', ';', '<', '>'), ' ', $str);
        return $res;
    }
    
    /*Manage Ajax Request*/
    if (isset($_FILES['file']) && $_FILES["file"]["name"] != '') {
        $filename = $_FILES['file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext != 'csv') {
            exitWithDanger("Only csv format allowed");
        } else {
            $fileName = $_FILES["file"]["tmp_name"];
            if (isset($_FILES["file"]) && $_FILES["file"]["size"] > 0) {
                $file = fopen($fileName, "r");
                $data = fgetcsv($file);
                if (is_array($data)) {
                    $column = ['product_name', 'sku', 'product_price', 'product_discount', 'product_url', 'product_image_url', 'price_currency'];
                    if (count(array_intersect($column, $data)) != count($data)) {
                        exitWithDanger("Please follow upload file format");
                    } else {
                        $insertData = [];
                        $otherError = '';
                        $counter = 1;
                        /*-Start-Manage Currency*/
                        $currency = [];
                        $sql = "select * From tb_currency";
                        if ($result = mysqli_query($con, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_array($result)) {
                                    $currency[$row['code']] = $row['symbol'];
                                }
                            }
                        }
                        /*-End-Manage Currency*/
                        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                            $counter++;
                            if ($counter == 1000) {
                                break;
                            }
                            $insertData[$counter] = ['product_name' => RemoveSpecialChar($column[0]),
                                'sku' => RemoveSpecialChar($column[1]),
                                'product_price' => (is_numeric($column[2]) ? $column[2] : 0.00),
                                'product_discount' => (is_numeric($column[3]) ? $column[3] : 0.00),
                                'product_url' => RemoveSpecialChar($column[4]),
                                'product_image' => RemoveSpecialChar($column[5]),
                                'price_currency' => (array_key_exists($column[6], $currency) ? $column[6] : 'USD'),
                                'price_currency_symbol' => (array_key_exists($column[6], $currency) ? $currency[$column[6]] : '$'),
                            ];
                        }
                        /*-Start-Insert Data In Database*/
                        foreach ($insertData as $data) {
                            $sql = "INSERT into tb_product (product_name,sku,product_price,product_discount,product_url,product_image,price_currency,price_currency_symbol)values ('" . $data["product_name"] . "','" . $data["sku"] . "','" . $data["product_price"] . "','" . $data["product_discount"] . "','" . $data["product_url"] . "','" . $data["product_image"] . "','" . $data["price_currency"] . "','" . $data["price_currency_symbol"] . "')";
                            mysqli_query($con, $sql);
                        }
                        $conn->close($con);
                        /*-End-Insert Data In Database*/
                        if ($otherError != '') {
                            exitWithDanger($otherError);
                        }
                        exitWithSuccess($insertData);
                    }
                } else {
                    exitWithDanger();
                }
            } else {
            }
        }
    } else if (isset($_POST['id']) && $_POST['id'] != '') {
        if (count($_POST["id"]) > 0) {
            $all = implode(",", $_POST["id"]);
            $sql = mysqli_query($con, "DELETE FROM tb_product WHERE id in ($all)");
            if ($sql) {
                exitWithSuccess("Data has been deleted successfully");
            } else {
                exitWithDanger("Error while deleting. Please Try again.");
            }
        } else {
            exitWithDanger("You need to select atleast one checkbox to delete!");
        }
    }
    
   