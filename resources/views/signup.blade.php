<div class="vh-100 mx-0" >

    <div class="row mx-0 mb-auto">
        <div class="col-sm-10 col-md-5 col-lg-4 position-relative mx-auto">

            <div class="card main-card rounded-3 mb-0 mx-3">
                <div class="card-body px-3 py-3 ">
                    <div class="m-sm-4">
                        <form id="mainForm" class="mt-3">
                            <div>
                                <label class="form-label">First Name</label>
                                <input class="form-control" type="text" name="first_name" id="first_name" placeholder="Enter your First Name" autofocus />
                            </div>
                            <div id="first_name-error" class="pt-1 mb-3 not-valid-text"></div>

                            
                            <div>
                                <label class="form-label">Last Name</label>
                                <input class="form-control" type="text" name="last_name" id="last_name" placeholder="Enter your Last Name" />
                            </div>
                            <div id="last_name-error" class="pt-1 mb-3 not-valid-text"></div>

                            
                            <div>
                                <label class="form-label">Email</label>
                                <input class="form-control" type="text" name="email" id="email" placeholder="Enter your Email" />
                            </div>
                            <div id="email-error" class="pt-1 mb-3 not-valid-text"></div>

                            <div>
                                <label class="form-label">Password</label>
                                <input class="form-control" type="password" name="password" id="password" placeholder="Enter your Password" />
                            </div>
                            <div id="password-error" class="pt-1 mb-3 not-valid-text"></div>

                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-primary py-2" id="submitBtn" type="submit">Sign Up</button>
                                <a href="{{ route('login') }}">Login</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type='text/javascript'>
    $(document).ready(function(event) {
        
        $('#mainForm').submit(function (event) {
            event.preventDefault();
            
            axios.post("{{ route('sign-up-post') }}", {
                first_name: $('#first_name').val(),
                last_name: $('#last_name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
            }).then(function (response) {
                console.log(response.data.user_id)
                alert_msg('success', '', response.data.message);
            }).catch(function (error) {
                console.log(error)
            });
        });

        function alert_msg(type, title, msg) {
            Swal.fire({
                icon: type,
                title: title,
                text: msg,
                showConfirmButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: false,
                showDenyButton: false,
                confirmButtonColor: '#145b8f ',
                confirmButtonText: 'Proceed to Login',
                cancelButtonColor: '#000',
                cancelButtonText: 'Close',
                denyButtonText: 'No',
                customClass: {
                    actions: 'my-actions',
                    confirmButton: 'order-1 right-gap',
                    cancelButton: 'order-2',
                    denyButton: 'order-3',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        }

    });
</script>