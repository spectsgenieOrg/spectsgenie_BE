<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.full.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit lens type</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit a lens type</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form id="addLenstypeForm" class="sgForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Lens type name</label>
                                <input type="text" id="inputName" name="name" class="form-control" value="<?php echo $lensType->name; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inputDescription">Description</label>
                                <textarea class="form-control" id="inputDescription" name="description" rows="4"><?php echo $lensType->description; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="inputDescription">Icon</label>
                                <input class="form-control" name="icon" type="file" />
                            </div>
                            <div class="form-group">
                                <label for="inputLensPackage">Choose lens packages under this lens type</label>
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select lens package type(s)" data-dropdown-css-class="select2-purple" name="lens_package_ids[]" id="inputLensPackage">
                                    <?php foreach ($lensPackages as $lensPackage) : ?>
                                        <option value="<?php echo $lensPackage->id; ?>"><?php echo $lensPackage->name; ?></option>
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
                    <input type="submit" value="Create new lens type" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </section>
</div>

<script>
    $('.select2').select2();
    $("#addLenstypeForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            name: "required",
            description: "required",
            "lens_package_ids[]": "required",
        },
        submitHandler: function(form) {
            $.ajax({
                url: '<?php echo base_url(); ?>lenstype/update/<?php echo $lensType->id; ?>',
                type: 'POST',
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
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

    let selectedLensPackages = [];

    <?php
    $lensPackageSplit = explode(",", $lensType->lens_package_ids);
    ?>
    <?php for ($i = 0; $i < count($lensPackageSplit); $i++) { ?>
        selectedLensPackages.push("<?php echo $lensPackageSplit[$i]; ?>");
    <?php } ?>

    $('#inputLensPackage').val(selectedLensPackages).trigger('change');
</script>