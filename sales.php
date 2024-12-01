<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    
    <!-- Styles -->
    <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="vendors/uniform.default.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="lib/jquery.js" type="text/javascript"></script>
    <script src="src/facebox.js" type="text/javascript"></script>
    <script src="vendors/jquery-1.7.2.min.js"></script>
    <script src="vendors/bootstrap.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('a[rel*=facebox]').facebox({
                loadingImage: 'src/loading.gif',
                closeImage: 'src/closelabel.png'
            });
        });

        // Clock script
        function showtime() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();
            const timeValue = `${hours % 12 || 12}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} ${(hours >= 12) ? 'PM' : 'AM'}`;
            document.getElementById("clock").value = timeValue;
            setTimeout(showtime, 1000);
        }
        window.onload = showtime;
    </script>
    
    <style>
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
</head>
<body>
    <?php 
    require_once('auth.php'); 
    include('navfixed.php'); 
    ?>

    <?php
    // Generate random invoice code
    function createRandomPassword() {
        return 'RS-' . substr(str_shuffle("003232303232023232023456789"), 0, 7);
    }
    $finalcode = createRandomPassword();
    $position = $_SESSION['SESS_LAST_NAME'];
    ?>

    <div class="container-fluid">
        <div class="row-fluid">
            <!-- Sidebar for Admin -->
            <?php if ($position == 'admin'): ?>
            <div class="span2">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li><a href="index.php"><i class="icon-dashboard icon-2x"></i> Dashboard</a></li>
                        <li class="active"><a href="sales.php?id=cash&invoice=<?php echo $finalcode ?>"><i class="icon-shopping-cart icon-2x"></i> Sales</a></li>
                        <li><a href="products.php"><i class="icon-list-alt icon-2x"></i> Products</a></li>
                        <li><a href="customer.php"><i class="icon-group icon-2x"></i> Customers</a></li>
                        <li><a href="supplier.php"><i class="icon-group icon-2x"></i> Suppliers</a></li>
                        <li><a href="salesreport.php?d1=0&d2=0"><i class="icon-bar-chart icon-2x"></i> Sales Report</a></li>
                        <br><br><br>
                        <li>
                            <div class="hero-unit-clock">
                                <form name="clock">
                                    <font color="white">Time:</font>
                                    <input id="clock" style="width:150px;" type="text" class="trans" disabled>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div><!--/.well -->
            </div><!--/span-->
            <?php endif; ?>

            <!-- Main Content -->
            <div class="span10">
                <div class="contentheader">
                    <i class="icon-money"></i> Sales
                </div>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li class="active">Sales</li>
                </ul>

                <div>
                    <a href="index.php">
                        <button class="btn btn-default btn-large"><i class="icon-circle-arrow-left icon-large"></i> Back</button>
                    </a>
                </div>

                <form action="incoming.php" method="post">
                    <input type="hidden" name="pt" value="<?php echo htmlspecialchars($_GET['id']); ?>" />
                    <input type="hidden" name="invoice" value="<?php echo htmlspecialchars($_GET['invoice']); ?>" />
                    <select name="product" style="width:650px;" class="chzn-select" required>
                        <option value="">Select Product</option>
                        <?php
                        include('../connect.php');
                        $result = $db->query("SELECT * FROM products");
                        while ($row = $result->fetch()) {
                            echo "<option value=\"{$row['product_id']}\">{$row['product_code']} - {$row['gen_name']} - {$row['product_name']} | Expires at: {$row['expiry_date']}</option>";
                        }
                        ?>
                    </select>
                    <input type="number" name="qty" value="1" min="1" placeholder="Qty" required>
                    <input type="hidden" name="date" value="<?php echo date("m/d/Y"); ?>">
                    <button type="submit" class="btn btn-info"><i class="icon-plus-sign icon-large"></i> Add</button>
                </form>
                <!-- Display Sales Table -->
                <!-- Ensure sanitized SQL queries when retrieving and displaying sales orders -->
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>
