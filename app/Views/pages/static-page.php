<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/summernote/summernote-bs4.min.css">
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/summernote/summernote-bs4.min.js"></script>
<?php $session = session();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add <?php echo $metadata->title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><?php echo $metadata->title; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form id="addStaticForm" class="sgForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputDescription"><?php echo $metadata->title; ?> Content</label>
                                <textarea id="inputDescription" class="form-control" name="paragraph" rows="4"><?php if ($pagecontent) {
                                                                                                                    echo $pagecontent->paragraph;
                                                                                                                } ?></textarea>
                                <input type="hidden" value="<?php echo $metadata->type; ?>" name="type" />
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
                    <input type="submit" value="Submit Content" class="btn btn-success float-right">
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
    $('#inputDescription').summernote({
        height: 300,
        onpaste: function(e) {
            console.log('workign');
        }
    });

    $("#addStaticForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            paragraph: "required"
        },
        submitHandler: function(form) {
            const data = [...new FormData(form)];

            $.ajax({
                url: '<?php echo base_url(); ?>pages/<?php if($pagecontent) {echo 'updatecontent';}else{echo 'addcontent';} ?>',
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