   <h4 class="fw-bold py-3 mb-4">Change Password</h4>

   <div class="row">
       <div class="col-xl">
           <div class="card mb-4">
               <div class="card-body">
                   <form id="password_form">
                       <div class="mb-3">
                           <label class="form-label" for="old_password">Old Password</label>
                           <input type="password" class="form-control" id="old_password" placeholder="Enter Old Password" value="" />
                       </div>
                       <div class="mb-3">
                           <label class="form-label" for="new_password">New Password</label>
                           <input type="password" class="form-control" id="new_password" placeholder="Enter New Password" value="" />
                       </div>
                       <div class="mb-3">
                           <label class="form-label" for="confirm_password">Confirm New Password</label>
                           <input type="password" class="form-control" id="confirm_password" placeholder="Enter Confirm New Password" value="" />
                       </div>

                       <button type="button" id="update_password" class="btn btn-primary">Update</button>
                   </form>
               </div>
           </div>
       </div>

   </div>

   <script>
       $(document).ready(function() {
           /* ----------------------------- Update Password ---------------------------- */
           $('#update_password').click(function() {
               const params = {
                   valid: true,
                   old_password: $('#old_password').val(),
                   new_password: $('#new_password').val(),
                   confirm_password: $('#confirm_password').val(),
               }

               if (params.old_password === '') {
                   toastr.error('Enter Old Password');
                   params.valid = false;
                   return false;
               }

               if (params.new_password === '') {
                   toastr.error('Enter New Password');
                   params.valid = false;
                   return false;
               }

               if (params.confirm_password === '') {
                   toastr.error('Enter Confirm New Password');
                   params.valid = false;
                   return false;
               }

               if (params.new_password !== params.confirm_password) {
                   toastr.error('New Password and Confirm New Password does not match');
                   params.valid = false;
                   return false;
               }

               if (params.valid) {
                   $.ajax({
                       url: '<?= base_url() ?>Auth/update_password',
                       method: 'POST',
                       dataType: 'JSON',
                       data: params,
                       success: function(res) {
                           if (res.Resp_code === 'RCS') {
                               toastr.info(res.Resp_desc)
                               $('#password_form')[0].reset();

                           } else if (res.Resp_code === 'RLD') {
                               window.location.reload();
                           } else {
                               toastr.error(res.Resp_desc)
                           }
                       }
                   })
               }


           })
       });
   </script>