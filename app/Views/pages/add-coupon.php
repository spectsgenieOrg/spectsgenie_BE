<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<?php $session = session(); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add a coupon</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add a coupon</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form id="addCouponForm" class="sgForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="couponCode">Coupon Code</label>
                                <input type="text" id="couponCode" name="coupon_code" placeholder="Enter coupon code" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="couponValue">Coupon Value</label>
                                <input type="text" class="form-control" id="couponValue" name="coupon_value" placeholder="Enter coupon value">
                            </div>

                            <div class="form-group">
                                <label for="inputInPercentage">Is coupon value in percentage?</label>
                                <select class="form-control" name="isPercentage" id="inputInPercentage">
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inputStatus">Is coupon active?</label>
                                <select class="form-control" name="status" id="inputStatus">
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="couponCategory">Coupon category?</label>
                                <select class="form-control" name="ca_id" id="couponCategory">
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?php echo $category->ca_id; ?>"><?php echo $category->ca_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <a href="#" class="btn btn-secondary">Cancel</a>
                    <input type="submit" value="Create new coupon" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </section>
</div>
<script>
    $("#addCouponForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            coupon_code: "required",
            coupon_value: "required",
        },
        submitHandler: function(form) {
            $.ajax({
                url: '<?php echo base_url(); ?>coupons/create',
                type: 'POST',
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(as) {
                    if (as.status == true) {
                        alert("Coupon created successfully");
                        location.reload();
                    } else if (as.status == false) {
                        alert("Invalid coupon data");
                    }
                }
            });
        }
    });
</script>