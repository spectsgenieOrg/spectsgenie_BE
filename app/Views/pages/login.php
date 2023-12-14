<?php

if (session()->has('type')) {
    if (session()->get('type') == "superadmin") {
        //header('Location:/all-products');
    }
}

?>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/additional-methods.min.js"></script>
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a class="h1"><b>Spects</b>Genie</a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form id="adminLoginForm" class="sgForm">
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control login-input" placeholder="Email">
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control login-input" placeholder="Password">
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                <a href="forgot-password.html">I forgot my password</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

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

    $("#adminLoginForm").submit(function(event) {
        event.preventDefault();
    }).validate({
        rules: {
            email: "required",
            password: "required"
        },
        submitHandler: function(form) {
            const data = [...new FormData(form)];
            let obj = {};
            data.forEach((value) => {
                obj[value[0]] = value[1]
            });
            $.ajax({
                url: '<?php echo base_url(); ?>admin/login',
                type: 'POST',
                data: JSON.stringify(obj),
                dataType: 'json',
                success: function(as) {
                    if (as.status == true) {
                        location.href = "products/all";
                    } else if (as.status == false) {
                        alert("Wrong Email or Password");
                    }
                }
            });
        }
    });
</script>