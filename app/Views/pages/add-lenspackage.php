<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add lens package</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add a lens package</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form id="addLensPackageForm" class="sgForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Lens package name</label>
                                <input type="text" id="inputName" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputDescription">Description (Detail list)</label>
                                <textarea class="form-control" id="inputDescription" name="description" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="inputPrice">Price</label>
                                <input class="form-control" name="price" type="number" />
                            </div>

                            <div class="form-group">
                                <label for="inputLabel">Label(Eg: Maximum Eye Protection)</label>
                                <input class="form-control" name="label" type="text" />
                            </div>

                            <div class="form-group">
                                <label for="inputLabel">Do you want to show membership at the bottom?</label>
                                <br />
                                <input type="radio" id="yesMembership" name="show_gold_membership" value="yes" />
                                <label for="yesMembership">Yes</label>

                                <input type="radio" id="noMembership" name="show_gold_membership" value="no" />
                                <label for="noMembership">No</label>
                            </div>

                            <div class="form-group">
                                <label for="inputLensTypes">Choose lens types this lens package would be applicable for:</label>
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select lens types" data-dropdown-css-class="select2-purple" name="lens_type_ids[]" id="inputLensTypes">
                                    <?php foreach ($lensTypes as $lensType) : ?>
                                        <option value="<?php echo $lensType->uid; ?>"><?php echo $lensType->name; ?></option>
                                    <?php endforeach ?>
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
                    <input type="submit" value="Create new lens package" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </section>
</div>
<script>
    $('.select2').select2();


    $("#addLensPackageForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            name: "required",
            price: "required",
            description: "required",
            show_membership: "required",
            "lens_type_ids[]": "required",
        },
        submitHandler: function(form) {
            $.ajax({
                url: '<?php echo base_url(); ?>lenspackage/addlenspackage',
                type: 'POST',
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(as) {
                    if (as.status == true) {
                        location.reload();
                    } else if (as.status == false) {
                        alert("Something wrong");
                    }
                }
            });
        }
    });
</script>