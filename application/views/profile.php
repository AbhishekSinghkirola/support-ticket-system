   <h4 class="fw-bold py-3 mb-4">Manage Profile</h4>

   <div class="row">
       <div class="col-xl">
           <div class="card mb-4">
               <div class="card-body">
                   <form id="profile_form">
                       <div class="mb-3">
                           <label class="form-label" for="user_name">Name</label>
                           <input type="text" class="form-control" id="user_name" placeholder="Name" value="<?= $user['name'] ?>" />
                       </div>
                       <div class="mb-3">
                           <label class="form-label" for="basic-default-email">Email</label>
                           <div class="input-group input-group-merge">
                               <input
                                   type="text"
                                   id="basic-default-email"
                                   class="form-control"
                                   placeholder="john.doe"
                                   aria-label="john.doe"
                                   aria-describedby="basic-default-email2" value="<?= $user['email'] ?>" disabled />
                           </div>
                       </div>
                       <div class="mb-3">
                           <label class="form-label" for="basic-default-phone">Mobile</label>
                           <input
                               type="text"
                               id="basic-default-phone"
                               class="form-control phone-mask"
                               placeholder="Enter Mobile" value="<?= $user['mobile'] ?>" disabled />
                       </div>
                       <button type="button" id="update_profile" class="btn btn-primary">Update</button>
                   </form>
               </div>
           </div>
       </div>

   </div>

   <script>
       $(document).ready(function() {
           /* ----------------------------- Update Profile ---------------------------- */
           $('#update_profile').click(function() {
               const params = {
                   valid: true,
                   user_name: $('#user_name').val(),
               }

               if (params.user_name === '') {
                   toastr.error('Enter User Name');
                   params.valid = false;
                   return false;
               }


               if (params.valid) {
                   $.ajax({
                       url: '<?= base_url() ?>Dashboard/update_profile',
                       method: 'POST',
                       dataType: 'JSON',
                       data: params,
                       success: function(res) {
                           if (res.Resp_code === 'RCS') {
                               toastr.info(res.Resp_desc)
                               window.location.reload();

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