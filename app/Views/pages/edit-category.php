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
                    <h1>Edit a category</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit a category</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form id="editCategoryForm" class="sgForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Category name</label>
                                <input type="text" id="inputName" name="ca_name" placeholder="Enter category name" class="form-control" value="<?php echo $category->ca_name; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputDescription">Description</label>
                                <textarea class="form-control" id="inputDescription" name="ca_description" rows="4" placeholder="Enter Sunglasses/Eyeglasses, etc description text"><?php echo $category->ca_description; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="inputStatus">Choose status</label>
                                <select class="form-control" name="ca_status" id="inputStatus">
                                    <option value="1" <?php if ($category->ca_status === "1") {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="0" <?php if ($category->ca_status === "0") {
                                                            echo "selected";
                                                        } ?>>InActive</option>
                                </select>
                            </div>
                            <input type="hidden" name="ca_created_by" value="<?php echo $session->get('user_id'); ?>" />


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

            </div>

            <div class="row">
                <div class="col-12">
                    <a href="#" class="btn btn-secondary">Cancel</a>
                    <input type="submit" value="Update category" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </section>
</div>
<script>
    $("#editCategoryForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            ca_name: "required",
            ca_description: "required",
        },
        submitHandler: function(form) {
            $.ajax({
                url: '<?php echo base_url(); ?>category/update/<?php echo $category->ca_id; ?>',
                type: 'POST',
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(as) {
                    if (as.status == true) {
                        alert("Category updated");
                        location.reload();
                    } else if (as.status == false) {
                        alert("Something wrong");
                    }
                }
            });
        }
    });
</script>