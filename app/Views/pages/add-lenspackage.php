<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>

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
    function convertFormToJSON(form) {
        return $(form)
            .serializeArray()
            .reduce(function(json, {
                name,
                value
            }) {
                json[name] = value;
                return json;
            }, {});
    }

    $("#addLensPackageForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            name: "required",
            price: "required",
            description: "required",
            show_membership: "required",
        },
        submitHandler: function(form) {
            const data = [...new FormData(form)];
            let obj = {};
            data.forEach((value) => {
                obj[value[0]] = value[1]
            });
            $.ajax({
                url: '<?php echo base_url(); ?>lenspackage/addlenspackage',
                type: 'POST',
                data: JSON.stringify(obj),
                dataType: 'json',
                success: function(as) {
                    if (as.status == true) {
                        //location.href = "products/all";
                    } else if (as.status == false) {
                        alert("Wrong Email or Password");
                    }
                }
            });
        }
    });
</script>