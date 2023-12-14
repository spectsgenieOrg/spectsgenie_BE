<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add product</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add product</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form id="addProductForm" class="sgForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Product Name</label>
                                <input type="text" id="inputName" name="pr_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputDescription">Product Description</label>
                                <textarea id="inputDescription" class="form-control" name="pr_description" rows="4"></textarea>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputSku">Product SKU</label>
                                <input type="text" id="inputSku" name="pr_sku" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputQuantity">Product Quantity</label>
                                <input id="inputQuantity" type="number" class="form-control" name="pr_qty" />
                            </div>
                            <div class="form-group">
                                <label for="inputSalePrice">Product Sale Price</label>
                                <input id="inputSalePrice" type="number" class="form-control" name="pr_sprice" />
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputSgPrice">Product SG Price</label>
                                <input type="number" id="inputSgPrice" name="pr_price" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputBrand">Choose Brand</label>
                                <select class="form-control" name="bd_id" id="inputBrand">
                                    <option disabled selected>Select a brand</option>
                                    <?php foreach ($brands as $brand) : ?>
                                        <option value="<?php echo $brand->bd_id; ?>"><?php echo $brand->bd_name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputCategory">Choose Category</label>
                                <select class="form-control" name="ca_id" id="inputCategory">
                                    <option disabled selected>Select a category</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?php echo $category->ca_id; ?>"><?php echo $category->ca_name; ?></option>
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
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputParent">Choose product parent</label>
                                <select class="form-control" name="parent_product_id" id="inputParent">
                                    <option disabled selected>Select a parent product</option>
                                    <?php foreach ($parents as $parent) : ?>
                                        <option value="<?php echo $parent->id; ?>"><?php echo $parent->name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputGender">Choose Gender</label>
                                <select class="form-control" name="sg_gender_ids" id="inputGender">
                                    <option disabled selected>Select Gender(s)</option>
                                    <?php foreach ($genders as $gender) : ?>
                                        <option value="<?php echo $gender->id; ?>"><?php echo $gender->name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputStatus">Choose status</label>
                                <select class="form-control" name="pr_status" id="inputStatus">
                                    <option value="1">Active</option>
                                    <option class="0">InActive</option>
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

    $("#addProductForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            pr_name: "required",
            pr_description: "required",
            pr_sku: "required",
            pr_qty: "required",
            pr_sprice: "required",
            bd_id: "required",
            ca_id: "required",
            parent_product_id: "required",
            sg_gender_ids: "required"
        },
        submitHandler: function(form) {
            const data = [...new FormData(form)];

            $.ajax({
                url: '<?php echo base_url(); ?>products/addproduct',
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