<?php $user = get_logged_in_user(); ?>
<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Manage Users</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="users_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {
        const account_status = <?= json_encode(common_status_array('account_status')); ?>;

        const users_table = $('#users_table').DataTable({
            ordering: false,
            processing: true,
            order: [],
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                "loadingRecords": "",
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': '&rarr;',
                    'previous': '&larr;'
                }
            },
            "ajax": {
                url: "<?= base_url() ?>User/get_users",
                type: "POST",
                dataSrc: function(json) {
                    if (json.Resp_code == 'RLD') {
                        window.location.reload(true);
                    } else if (json.Resp_code != 'RCS') {
                        toastr.error(json.Resp_desc)
                    }
                    return json.data ? json.data : [];
                },
            },
            columns: [{
                    title: 'Username',
                    data: 'name',
                    class: 'compact all',
                },
                {
                    title: 'Email',
                    data: 'email',
                    class: 'compact all',
                },
                {
                    title: 'Mobile',
                    data: 'mobile',
                    class: 'compact all',
                },
                {
                    title: 'Account Status',
                    data: 'account_status',
                    class: 'compact all',
                },
                {
                    title: 'Action',
                    data: null,
                    class: 'compact all',
                    render: function(data, type, full, meta) {
                        return `
                            <div class="d-flex">
                                <a class="dropdown-item edit_user" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <?php if ($user['role'] === 'ADMIN') : ?><a class="dropdown-item delete_user" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a><?php endif; ?>
                            </div>
                        `;
                    }
                }
            ],
            buttons: [{
                    extend: 'csv',
                    className: 'btn btn-info ml-2',
                    title: 'Users',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Users',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }

                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Users',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL'

                },

            ]
        })


        /* -------------------------------- Edit User ------------------------------- */
        users_table.on('click', '.edit_user', function() {
            const row = $(this).closest('tr');
            const showtd = users_table.row(row).data();

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit User</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-6">
                            <label class="form-label" for="student_name">User Name</label>
                            <input type="text" class="form-control" id="user_name" placeholder="Enter User Name" value="${showtd.name}" autofocus>
                        </div>
                        <div class="col-6 mb-6">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter Email Address" value="${showtd.email}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6 mb-6">
                            <label class="form-label" for="mobile">Mobile</label>
                            <input type="number" class="form-control" id="mobile" placeholder="Enter Mobile Number" value="${showtd.mobile}" autofocus>
                        </div>
                        <div class="col-6 mb-6">
                            <label class="form-label" for="account_status">Account Status</label>
                            <select id="account_status" class="form-select">
                                <option value="" disabled>Select Account Status</option>
                                ${Object.keys(account_status).map((status) => `<option value="${status}" ${showtd.account_status === status ? 'selected' : ''}>${account_status[status]}</option>`).join('')}
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="edit_user">Save</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            $('#edit_user').click(function() {
                const params = {
                    valid: true,
                    user_id: showtd.id,
                    user_name: $('#user_name').val(),
                    email: $('#email').val(),
                    mobile: $('#mobile').val(),
                    account_status: $('#account_status').val()
                }

                if (params.user_name === '') {
                    toastr.error('Enter User Name');
                    params.valid = false;
                    return false;
                }

                if (params.email === '') {
                    toastr.error('Enter Email Address');
                    params.valid = false;
                    return false;
                }

                if (params.mobile === '') {
                    toastr.error('Enter Your Mobile');
                    params.valid = false;
                    return false;
                }

                if (!(params.account_status in account_status)) {
                    toastr.error('Select Account Status');
                    params.valid = false;
                    return false;
                }

                $.ajax({
                    url: '<?= base_url() ?>User/edit_user',
                    method: 'POST',
                    dataType: 'JSON',
                    data: params,
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            $('#back_to_first_screen').click()
                            users_table.ajax.reload()
                        } else if (res.Resp_code === 'RLD') {
                            window.location.reload();
                        } else {
                            toastr.error(res.Resp_desc)
                        }
                    }
                })
            })

        })

        <?php if ($user['role'] === 'ADMIN'): ?>
            /* ------------------------------- Delete Agent ------------------------------ */
            users_table.on('click', '.delete_user', function() {
                const row = $(this).closest('tr');
                const showtd = users_table.row(row).data();


                $.ajax({
                    url: '<?= base_url() ?>User/delete_user',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        user_id: showtd.id
                    },
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            users_table.ajax.reload()
                        } else if (res.Resp_code === 'RLD') {
                            window.location.reload();
                        } else {
                            toastr.error(res.Resp_desc)
                        }
                    }
                })

            })
        <?php endif; ?>
    })
</script>