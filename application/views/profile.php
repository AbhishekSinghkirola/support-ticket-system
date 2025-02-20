   <h4 class="fw-bold py-3 mb-4">Manage Profile</h4>

   <div class="row">
       <div class="col-xl">
           <div class="card mb-4">
               <div class="card-body">
                   <form>
                       <div class="mb-3">
                           <label class="form-label" for="basic-default-fullname">Name</label>
                           <input type="text" class="form-control" id="basic-default-fullname" placeholder="Name" value="<?= $user['name'] ?>" />
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
                       <button type="submit" class="btn btn-primary">Update</button>
                   </form>
               </div>
           </div>
       </div>

   </div>