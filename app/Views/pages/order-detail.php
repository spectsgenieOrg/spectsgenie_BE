<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order #<?php echo $orders['order']->{'order_id'}; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/orders/all">Orders / <?php echo $orders['order']->{'order_id'}; ?></a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3>Customer Detail</h3>
                            <p><b>Name</b> - <?php echo $orders['order']->{'customer_name'}; ?></p>
                            <p><b>Address</b> - <?php echo $orders['order']->{'address_name'} . ', ' . $orders['order']->{'address_line_1'} . ' ,' . $orders['order']->{'address_line_2'} . ', ' . $orders['order']->{'pincode'} . ', ' . $orders['order']->{'city'} . ', ' . $orders['order']->{'state'} . ', ' . $orders['order']->{'country'}; ?></p>
                            <p><b>Order placed on</b> - <?php echo $orders['order']->{'order_placed_on'}; ?></p>
                            <p><b>Order Status</b> - <?php echo $orders['order']->{'order_status'}; ?></p>
                            <p><b>Order Value</b> - ₹<?php echo $orders['order']->{'total_amount'}; ?></p>
                            <p><b>Discount</b> - ₹<?php echo $orders['order']->{'discount'}; ?></p>
                        </div>
                    </div>
                </div>

                
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
</div>