<!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.full.min.js"></script>
<?php $session = session();
$baseURL = 'https://newpos.spectsgenie.com/';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit product</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit product</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form id="editProductForm" class="sgForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Product Name</label>
                                <input type="text" id="inputName" value="<?php echo $product->pr_name; ?>" name="pr_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputName">Product Slug (To be used in the website URL)</label>
                                <input type="text" id="inputSlug" value="<?php echo $product->slug; ?>" name="slug" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputDescription">Product Description</label>
                                <textarea id="inputDescription" class="form-control" name="pr_description" rows="4"><?php echo $product->pr_description; ?></textarea>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputImages">Product Images</label> <button type="button" data-toggle="modal" data-target="#imageModal" class="btn btn-link float-right">View All Images</button>
                                <input type="file" multiple id="inputImages" name="images[]" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputSku">Product SKU</label>
                                <input type="text" id="inputSku" name="pr_sku" value="<?php echo $product->pr_sku; ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputQuantity">Product Quantity</label>
                                <input id="inputQuantity" type="number" class="form-control" value="<?php echo $product->pr_qty; ?>" name="pr_qty" />
                            </div>
                            <div class="form-group">
                                <label for="inputSalePrice">Product Sale Price</label>
                                <input id="inputSalePrice" type="number" class="form-control" value="<?php echo $product->pr_sprice; ?>" name="pr_sprice" />
                            </div>
                            <div class="form-group">
                                <label for="inputPrMaterial">Product Material</label>
                                <input id="inputPrMaterial" type="text" class="form-control" value="<?php echo $product->pr_material; ?>" name="pr_material" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputImages">Product Platform (Online/Store/Both)</label>
                                <select class="form-control" data-placeholder="Select a platform" name="platform" id="inputPlatform">
                                    <option disabled selected>Select a platform</option>
                                    <option <?php echo $product->platform === 'online' ? 'selected' : ''; ?> value="online">Online</option>
                                    <option <?php echo $product->platform === 'store' ? 'selected' : ''; ?> value="store">Store</option>
                                    <option <?php echo $product->platform === 'both' ? 'selected' : ''; ?> value="both">Both</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputSgPrice">Product SG Price</label>
                                <input type="number" id="inputSgPrice" value="<?php echo $product->pr_price; ?>" name="pr_price" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="inputBrand">Choose Brand</label>
                                <select class="form-control" name="bd_id" id="inputBrand">
                                    <option disabled>Select a brand</option>
                                    <?php foreach ($brands as $brand) : ?>
                                        <option value="<?php echo $brand->bd_id; ?>" <?php if ($brand->bd_id === $product->bd_id) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo $brand->bd_name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputCategory">Choose Category</label>
                                <select class="form-control" name="ca_id" id="inputCategory">
                                    <option disabled>Select a category</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?php echo $category->ca_id; ?>" <?php if ($category->ca_id === $product->ca_id) {
                                                                                            echo "selected";
                                                                                        } ?>><?php echo $category->ca_name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputPrWarranty">Product Warranty</label>
                                <input id="inputPrWarranty" type="text" class="form-control" value="<?php echo $product->pr_warranty; ?>" name="pr_warranty" />
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="row">

                <div class="col">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputPSD">Upload PSD files</label>
                                <input type="file" multiple id="inputPSD" name="psd_files[]" class="form-control">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputASize">A Size</label>
                                <input type="number" id="inputASize" name="pr_a_size" value="<?php echo $product->pr_a_size;
                                                                                                ?>" class="form-control">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputBSize">B Size</label>
                                <input type="number" id="inputBSize" name="pr_b_size" value="<?php echo $product->pr_b_size;
                                                                                                ?>" class="form-control">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputASize">D Size</label>
                                <input type="number" id="inputDSize" name="pr_d_size" value="<?php echo $product->pr_d_size;
                                                                                                ?>" class="form-control">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="shape">Shape</label>
                                <input type="text" id="shape" name="shape" value="<?php echo $product->shape;
                                                                                                ?>" class="form-control">
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <input type="hidden" name="br_id" value="<?php echo $session->get('user_id'); ?>" />
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="lenstype">Choose Lens types</label>
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select lens type(s)" data-dropdown-css-class="select2-purple" name="lens_type_ids[]" id="lenstype">
                                    <?php foreach ($lensTypes as $lensType) : ?>

                                        <option value="<?php echo $lensType->id; ?>"><?php echo $lensType->name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputParent">Choose product parent</label>
                                <select class="form-control" name="parent_product_id" id="inputParent">
                                    <option disabled selected>Select a parent product</option>
                                    <?php foreach ($parents as $parent) : ?>
                                        <option value="<?php echo $parent->id; ?>" <?php if ($parent->id === $product->parent_product_id) {
                                                                                        echo "selected";
                                                                                    } ?>><?php echo $parent->name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputGender">Choose Gender</label>
                                <select class="form-control select2" multiple="multiple" data-placeholder="Select a gender" data-dropdown-css-class="select2-purple" name="sg_gender_ids[]" id="inputGender">
                                    <?php foreach ($genders as $gender) : ?>

                                        <option value="<?php echo $gender->id; ?>"><?php echo $gender->name; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputStatus">Choose status</label>
                                <select class="form-control" name="pr_status" id="inputStatus">
                                    <option value="1" <?php if ($product->pr_status === "1") {
                                                            echo "selected";
                                                        } ?>>Active</option>
                                    <option value="0" <?php if ($product->pr_status === "0") {
                                                            echo "selected";
                                                        } ?>>InActive</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="#" class="btn btn-secondary">Cancel</a>
                    <input type="submit" value="Update product" class="btn btn-success float-right">
                </div>
            </div>

            <div id="imageModal" class="modal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select an image that will be shown on the product listing page</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div class="container">

                                    <?php $images = explode(',', $product->pr_image);
                                    $i = 0; ?>
                                    <?php foreach ($images as $image) {  ?>

                                        <div style="display:flex;padding-left: 20px; align-items: center;">
                                            <input class="form-check-input" type="radio" name="selected_image_to_show" value="<?php echo $i; ?>" <?php if ($product->selected_image_to_show == $i) {
                                                                                                                                                        echo "checked";
                                                                                                                                                    } ?>>
                                            <div>
                                                <img src="<?php echo $baseURL . '' . $image; ?>" style="width:150px;" />
                                            </div>
                                        </div>

                                    <?php $i++;
                                    } ?>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<script>
    function convertFormToJSON(formData) {
        let obj = {},
            genderIds = "";
        formData.forEach((value) => {
            if (value[0] === "sg_gender_ids") {
                genderIds += value[1] + ',';

                obj[value[0]] = genderIds;
            } else {
                obj[value[0]] = value[1];
            }
        });
        obj['sg_gender_ids'] = obj['sg_gender_ids'].slice(0, -1);
        return JSON.stringify(obj);
    }

    $("#editProductForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            pr_name: "required",
            pr_description: "required",
            pr_sku: "required",
            pr_qty: "required",
            pr_sprice: "required",
            bd_id: "required",
            platform: "required",
            ca_id: "required",
            parent_product_id: "required",
            "sg_gender_ids[]": "required",
            images: "required",
            pr_a_size: "required",
            pr_b_size: "required",
            pr_d_size: "required",
            "lens_type_ids[]": "required",
        },
        submitHandler: function(form) {
            const data = [...new FormData(form)];
            $.ajax({
                url: '<?php echo base_url(); ?>products/update/<?php echo $product->pr_id; ?>',
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

    let selectedGenders = [],
        selectedLensTypes = [];

    <?php
    $genderSplit = explode(",", $product->sg_gender_ids);
    ?>
    <?php for ($i = 0; $i < count($genderSplit); $i++) { ?>
        selectedGenders.push("<?php echo $genderSplit[$i]; ?>");
    <?php } ?>

    <?php
    $lensTypeSplit = explode(",", $product->lens_type_ids);
    for ($j = 0; $j < count($lensTypeSplit); $j++) {
    ?>
        selectedLensTypes.push("<?php echo $lensTypeSplit[$j]; ?>");
    <?php } ?>
    $('.select2').select2();
    $('#inputGender').val(selectedGenders).trigger('change');
    $('#lenstype').val(selectedLensTypes).trigger('change');
</script>