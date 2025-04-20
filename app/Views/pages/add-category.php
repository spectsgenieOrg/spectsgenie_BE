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
                    <h1>Add a category</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add a category</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form id="addCategoryForm" class="sgForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Category name</label>
                                <input type="text" id="inputName" name="ca_name" placeholder="Enter category name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputDescription">Description</label>
                                <textarea class="form-control" id="inputDescription" name="ca_description" rows="4" placeholder="Enter Sunglasses/Eyeglasses, etc description text"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="inputStatus">Choose status</label>
                                <select class="form-control" name="ca_status" id="inputStatus">
                                    <option value="1" selected>Active</option>
                                    <option class="0">InActive</option>
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
                    <input type="submit" value="Create new category" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </section>
</div>
<script>
    $("#addCategoryForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            ca_name: "required",
            ca_description: "required",
        },
        submitHandler: function(form) {
            $.ajax({
                url: '<?php echo base_url(); ?>category/addcategory',
                type: 'POST',
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(as) {
                    if (as.status == true) {
                        alert("Category added");
                        location.reload();
                    } else if (as.status == false) {
                        alert("Something wrong");
                    }
                }
            });
        }
    });
</script>