<div>
    If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius
</div>




<div class="vh-100 mx-0" >

    <div class="row mx-0 mb-auto">
        <div class="col-sm-10 col-md-5 col-lg-4 position-relative mx-auto">

            <div class="card main-card rounded-3 mb-0 mx-3">
                <div class="card-body px-3 py-3 ">
                    <div class="m-sm-4">
                        <form id="mainForm" class="mt-3">
                            <div>
                                <label class="form-label">Email</label>
                                <input class="form-control" type="text" name="email" id="email" placeholder="Enter your Email" autofocus />
                            </div>
                            <div id="email-error" class="pt-1 mb-3 not-valid-text"></div>

                            <div>
                                <label class="form-label">Password</label>
                                <input class="form-control" type="password" name="password" id="password" placeholder="Enter your Password" />
                            </div>
                            <div id="password-error" class="pt-1 mb-3 not-valid-text"></div>

                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-primary py-2" id="submitBtn" type="submit">LOGIN</button>
                                <a href="{{ route('sign-up') }}">Sign Up</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script type='text/javascript'>
    $(document).ready(function(event) {
        
        $('#mainForm').submit(function (event) {
            event.preventDefault();

            axios.post("{{ route('login-post') }}", {
                email: $('#email').val(),
                password: $('#password').val(),
            }).then(function (response) {
                window.location.href = response.data.url;
            }).catch(function (error) {
                console.log(error)
            });


        });
    });
</script>