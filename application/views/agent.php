<div class="card" id="first_screen">
    <div class="d-flex justify-content-between align-items-center pe-4">
        <h5 class="card-header">Manage Support Agents</h5>
        <button type="button" class="btn btn-primary" id="add_agent">Add Agent</button>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered" id="agents_table"></table>
        </div>
    </div>
</div>

<div class="card" id="second_screen" style="display: none;"></div>

<script>
    $(document).ready(function() {
        const account_status = <?= json_encode(common_status_array('account_status')); ?>;

        const agents_table = $('#agents_table').DataTable({
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
                url: "<?= base_url() ?>Agent/get_agents",
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
                                <a class="dropdown-item edit_agent" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                <a class="dropdown-item delete_agent" style="width:max-content;" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                        `;
                    }
                }
            ],
            buttons: [{
                    extend: 'csv',
                    className: 'btn btn-info ml-2',
                    title: 'Support Agents',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }
                },
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Support Agents',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    }

                },
                {
                    extend: 'pdfHtml5',
                    className: 'btn btn-info ml-2',
                    title: 'Support Agents',
                    exportOptions: {
                        columns: ":not(.ignoreexport)"
                    },
                    orientation: 'landscape',
                    pageSize: 'LEGAL'

                },

            ]
        })

        /* ------------------------------ Add new agent ------------------------------ */
        $('#add_agent').click(function(e) {

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Add Agent</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-6">
                            <label class="form-label" for="student_name">User Name</label>
                            <input type="text" class="form-control" id="user_name" placeholder="Enter User Name" autofocus>
                        </div>
                        <div class="col-6 mb-6">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter Email Address">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-6 mb-6">
                            <label class="form-label" for="mobile">Mobile</label>
                            <input type="number" class="form-control" id="mobile" placeholder="Enter Mobile Number">
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger mt-5" id="back_to_first_screen">Back</button>
                    <button type="button" class="btn btn-primary mt-5" id="save_agent">Save</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            /* ------------------------------ Save Student Data------------------------------ */
            $('#save_agent').click(function(e) {
                const params = {
                    valid: true,
                    user_name: $('#user_name').val(),
                    email: $('#email').val(),
                    mobile: $('#mobile').val(),
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

                if (params.valid) {
                    $.ajax({
                        url: '<?= base_url() ?>Agent/add_agent',
                        method: 'POST',
                        dataType: 'JSON',
                        data: params,
                        success: function(res) {
                            if (res.Resp_code === 'RCS') {
                                toastr.info(res.Resp_desc)
                                $('#back_to_first_screen').click()
                                agents_table.ajax.reload()
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

        /* -------------------------------- Edit Agent ------------------------------- */
        agents_table.on('click', '.edit_agent', function() {
            const row = $(this).closest('tr');
            const showtd = agents_table.row(row).data();

            let html = `
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Agent</h5>
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
                    <button type="button" class="btn btn-primary mt-5" id="edit_agent">Save</button>
                </div>
            `;
            $('#first_screen').hide();
            $('#second_screen').html(html).show();

            $('#back_to_first_screen').click(function(e) {
                $('#first_screen').show();
                $('#second_screen').html('').hide();
            });

            $('#edit_agent').click(function() {
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
                    url: '<?= base_url() ?>Agent/edit_agent',
                    method: 'POST',
                    dataType: 'JSON',
                    data: params,
                    success: function(res) {
                        if (res.Resp_code === 'RCS') {
                            toastr.info(res.Resp_desc)
                            $('#back_to_first_screen').click()
                            agents_table.ajax.reload()
                        } else if (res.Resp_code === 'RLD') {
                            window.location.reload();
                        } else {
                            toastr.error(res.Resp_desc)
                        }
                    }
                })
            })

        })

        /* ------------------------------- Delete Agent ------------------------------ */
        agents_table.on('click', '.delete_agent', function() {
            const row = $(this).closest('tr');
            const showtd = agents_table.row(row).data();


            $.ajax({
                url: '<?= base_url() ?>Agent/delete_agent',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    user_id: showtd.id
                },
                success: function(res) {
                    if (res.Resp_code === 'RCS') {
                        toastr.info(res.Resp_desc)
                        agents_table.ajax.reload()
                    } else if (res.Resp_code === 'RLD') {
                        window.location.reload();
                    } else {
                        toastr.error(res.Resp_desc)
                    }
                }
            })

        })
    })
</script>