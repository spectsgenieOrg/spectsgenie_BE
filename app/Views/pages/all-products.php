<style>
    span.platform {
        text-transform: capitalize;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Products</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Products</li>
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
                        <div class="card-header">
                            <h3 class="card-title">List of all products</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Sl. No</th>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Product Parent</th>
                                        <th>Category</th>
                                        <th>Product Platform</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0;
                                    $i++; ?>
                                    <?php foreach ($products as $product) : ?>
                                        <tr id="row_<?php echo $product->pr_id; ?>">
                                            <td><?php echo $i++; ?></td>
                                            <td><?php echo $product->pr_name; ?>
                                            </td>
                                            <td><?php echo $product->pr_sku; ?></td>
                                            <td><?php if ($product->parent_product !== NULL) {
                                                    echo $product->parent_product->name;
                                                } else {
                                                    echo "NA";
                                                } ?></td>
                                            <td> <?php echo $product->productCategory->ca_name; ?></td>
                                            <td><span class="platform"><?php echo $product->platform !== 'online' ? 'In-Store' : 'Online'; ?></span></td>
                                            <td>
                                                <div>
                                                    <a href="<?php echo base_url() . 'products/edit/' . $product->pr_id; ?>"><i class="fas fa-pencil-alt"></i></a>
                                                    <button id="product_<?php echo $product->pr_id; ?>" type="button" class="btn btn-del"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
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
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- DataTables  & Plugins -->
<script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script>
    $(function() {
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });

        $('#example2').on("click", ".btn-del", function() {
            var id = $(this).attr('id');
            if (confirm("Do you really want to delete this product?") == true) {
                var obj = {
                    id: id.split("_")[1]
                };
                $.ajax({
                    url: '<?php echo base_url(); ?>products/delete',
                    type: 'POST',
                    data: obj,
                    dataType: 'json',
                    success: function(as) {
                        if (as.status == true) {
                            alert(as.message);
                            $('#row_' + id.split("_")[1]).remove();
                        } else {
                            alert("Error while deleting");
                        }
                    }
                });
            } else {

            }
        });
    });
</script>