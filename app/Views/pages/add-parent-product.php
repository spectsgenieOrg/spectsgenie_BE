<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add parent product</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add parent product</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <form id="addParentProductForm" class="sgForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Product Name</label>
                                <input type="text" id="inputName" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputDescription">Product Description</label>
                                <textarea id="inputDescription" class="form-control" name="description" rows="4"></textarea>
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
                    <input type="submit" value="Create new Product" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </section>
</div>

<script>
    function convertFormToJSON(formData) {
        let obj = {};
        formData.forEach((value) => {
            obj[value[0]] = value[1]
        });

        return JSON.stringify(obj);
    }

    $("#addParentProductForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            name: "required",
            description: "required"
        },
        submitHandler: function(form) {
            const data = [...new FormData(form)];

            $.ajax({
                url: '<?php echo base_url(); ?>products/addparentdetails',
                type: 'POST',
                data: convertFormToJSON(data),
                dataType: 'json',
                success: function(as) {
                    if (as.status == true) {
                        alert(as.message);
                        location.reload();
                    } else if (as.status == false) {
                        alert(as.message);
                    }
                }
            });
        }
    });
</script>