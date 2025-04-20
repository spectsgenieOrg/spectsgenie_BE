<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order #<?php echo $orders['order']->{'order_number'}; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Orders / <?php echo $orders['order']->{'order_number'}; ?></li>
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

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Product List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Sl. No</th>
                                        <th>Order Detail ID</th>
                                        <th>Product Name</th>
                                        <th>Product SKU</th>
                                        <th>Product Price</th>
                                        <th>Parent Product Name</th>
                                        <th>Lens Type Name</th>
                                        <th>Lens Package Name</th>
                                        <th>Lens Package Price</th>
                                        <th>Lens Package Material</th>
                                        <th>Category Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $i++; ?>
                                    <?php foreach ($orders['ordered_items'] as $order) : ?>
                                        <tr id="row_<?php echo $order->order_detail_id; ?>">
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $order->order_detail_id; ?>
                                            </td>
                                            <td><?php echo $order->product_name; ?></td>

                                            <td><?php echo $order->product_sku; ?></td>
                                            <td>₹<?php echo $order->product_price; ?></td>
                                            <td><?php echo $order->parent_product_name; ?></td>
                                            <td><?php echo $order->lens_type_name; ?></td>
                                            <td><?php echo $order->lens_package_name; ?></td>
                                            <td>₹<?php echo $order->lens_package_price; ?></td>
                                            <td><?php echo $order->lens_package_material; ?></td>
                                            <td><?php echo $order->category_name; ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
</div>