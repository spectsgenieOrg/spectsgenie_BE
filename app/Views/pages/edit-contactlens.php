<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.full.min.js"></script>
<?php $session = session();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit contact lens</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit contact lens</li>
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
                                <input type="text" id="inputName" value="<?php echo $product->name; ?>" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputName">Product Slug (To be used in the website URL)</label>
                                <input type="text" id="inputSlug" name="slug" value="<?php echo $product->slug; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputDescription">Product Description</label>
                                <textarea id="inputDescription" class="form-control" name="description" rows="4"><?php echo $product->description; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="inputSku">Quantity per box</label>
                                <input type="number" id="inputquantitybox" name="quantity_per_box" value="<?php echo $product->quantity_per_box; ?>" class="form-control">
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
                                <label for="inputImages">Product Images</label>
                                <input type="file" multiple id="inputImages" name="images[]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputSku">Product SKU</label>
                                <input type="text" id="inputSku" name="sku" value="<?php echo $product->sku; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputQuantity">Product Quantity</label>
                                <input id="inputQuantity" type="number" class="form-control" value="<?php echo $product->quantity; ?>" name="quantity" />
                            </div>
                            <div class="form-group">
                                <label for="inputSalePrice">Product Sale Price</label>
                                <input id="inputSalePrice" type="number" class="form-control" value="<?php echo $product->sale_price; ?>" name="sale_price" />
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
                                <label for="inputPlatform">Product Platform (Online/Store/Both)</label>
                                <select class="form-control" data-placeholder="Select a platform" name="platform" id="inputPlatform">
                                    <option disabled selected>Select a platform</option>
                                    <option disabled selected>Select a platform</option>
                                    <option <?php echo $product->platform === 'online' ? 'selected' : ''; ?> value="online">Online</option>
                                    <option <?php echo $product->platform === 'store' ? 'selected' : ''; ?> value="store">Store</option>
                                    <option <?php echo $product->platform === 'both' ? 'selected' : ''; ?> value="both">Both</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputSgPrice">Product SG Price</label>
                                <input type="number" id="inputSgPrice" name="price" value="<?php echo $product->price; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputBrand">Choose Brand</label>
                                <select class="form-control" name="brand_id" id="inputBrand">
                                    <option disabled selected>Select a brand</option>
                                    <?php foreach ($brands as $brand) : ?>
                                        <option value="<?php echo $brand->bd_id; ?>" <?php if ($brand->bd_id === $product->brand_id) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo $brand->bd_name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputGender">Choose Gender</label>
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select a gender" data-dropdown-css-class="select2-purple" name="sg_gender_ids[]" id="inputGender">
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
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputDiameter">Diameter</label>
                                <input type="text" id="inputDiameter" value="<?php echo $product->diameter; ?>" name="diameter" class="form-control">
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
                                <label for="inputMaterial">Material</label>
                                <input type="text" id="inputMaterial" value="<?php echo $product->material; ?>" name="material" class="form-control">
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
                                <label for="waterContent">Water Content</label>
                                <input type="text" id="waterContent" name="water_content" value="<?php echo $product->water_content; ?>" class="form-control">
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
                                <label for="inputStatus">Choose status</label>
                                <select class="form-control" name="status" id="inputStatus">
                                    <option value="1" <?php if ($product->status === "1") {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="0" <?php if ($product->status === "0") {
                                                            echo "selected";
                                                        } ?>>InActive</option>
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
                    <input type="submit" value="Update Product" class="btn btn-success float-right">
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

    let selectedGenders = [];



    $("#addProductForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            name: "required",
            description: "required",
            sku: "required",
            quantity: "required",
            quantity_per_box: "required",
            price: "required",
            sale_price: "required",
            brand_id: "required",
            parent_product_id: "required",
            "sg_gender_ids[]": "required",
            platform: "required",
            diameter: "required",
            material: "required",
            water_content: "required",
            status: "required",
        },
        submitHandler: function(form) {
            const data = [...new FormData(form)];

            $.ajax({
                url: '<?php echo base_url(); ?>products/updatecontactlens/<?php echo $product->id; ?>',
                type: 'POST',
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(as) {
                    if (as.status == true) {
                        alert(as.message);
                    } else if (as.status == false) {
                        alert(as.message);
                    }
                }
            });
        }
    });

    <?php
    $genderSplit = explode(",", $product->sg_gender_ids);
    ?>
    <?php for ($i = 0; $i < count($genderSplit); $i++) { ?>
        selectedGenders.push("<?php echo $genderSplit[$i]; ?>");
    <?php } ?>

    $('.select2').select2();

    $('#inputGender').val(selectedGenders).trigger('change');
</script>