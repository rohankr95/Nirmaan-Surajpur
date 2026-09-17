<!-- Start Core Plugins
        =====================================================================-->
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jQuery/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
<!-- jquery-ui -->
<script src="{{ asset('assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js') }}" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- lobipanel -->
<script src="{{ asset('assets/plugins/lobipanel/lobipanel.min.js') }}" type="text/javascript"></script>
<!-- Pace js -->
<script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
<!-- SlimScroll -->
<script src="{{ asset('assets/plugins/slimScroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<!-- FastClick -->
<script src="{{ asset('assets/plugins/fastclick/fastclick.min.js') }}" type="text/javascript"></script>
<!-- AdminBD frame -->
<script src="{{ asset('assets/dist/js/frame.js') }}" type="text/javascript"></script>
<!-- Toastr js -->
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}" type="text/javascript"></script>
<!-- dataTables js -->
<script src="{{ asset('assets/plugins/datatables/dataTables.min.js') }}" type="text/javascript"></script>
<!-- Select2 Js -->
<script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>
<!-- End Core Plugins
=====================================================================-->
<!-- Start Theme label Script
=====================================================================-->
@if (request()->route()->getName() == 'dashboard')
    <!-- ChartJS -->
    <script src="{{ asset('assets/vendor/chartjs/Chart.min.js') }}"></script>

    <script>
        new Chart(document.getElementById('myChart'), {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1,
                    backgroundColor: '#009944',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        new Chart(document.getElementById('blockWiseChart'), {
            type: 'bar',
            data: {
                labels: ['दुर्ग', 'धमधा', 'पाटन'],
                datasets: [{
                        label: 'Low',
                        data: [19, 3, 5],
                        borderWidth: 1,
                        backgroundColor: '#D6E9C6' // green
                    },
                    {
                        label: 'Moderate',
                        data: [5, 3, 25],
                        borderWidth: 1,
                        backgroundColor: '#FAEBCC' // yellow
                    },
                    {
                        label: 'High',
                        data: [2, 23, 5],
                        borderWidth: 1,
                        backgroundColor: '#EBCCD1' // red
                    }
                ]
            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
        @php
            $lebels = get_departments()->map(function ($dept) {
                return collect($dept->toArray())
                    ->only(['department_id', 'department_name'])
                    ->all();
            });
            
            //        dd($lebels);
            
        @endphp
        new Chart(document.getElementById('departmentWiseChart'), {
            type: 'bar',
            data: {
                labels: [<?= "'" .
                        implode(
                            "', '",
                            $lebels
                                ->map(function ($dept) {
                                    return $dept['department_name'];
                                })
                                ->toArray(),
                        ) .
                        "'" ?>],
                datasets: [

                    {
                        label: 'Low',
                        data: [<?= implode(
                                ', ',
                                $lebels
                                    ->map(function ($dept) {
                                        return $dept['department_id'];
                                    })
                                    ->toArray(),
                            ) ?>],
                        borderWidth: 1,
                        backgroundColor: '#D6E9C6' // green
                    },
                    {
                        label: 'Moderate',
                        data: [<?= implode(
                                ', ',
                                $lebels
                                    ->map(function ($dept) {
                                        return $dept['department_id'];
                                    })
                                    ->toArray(),
                            ) ?>],
                        borderWidth: 1,
                        backgroundColor: '#FAEBCC' // yellow
                    },
                    {
                        label: 'High',
                        data: [<?= implode(
                                ', ',
                                $lebels
                                    ->map(function ($dept) {
                                        return $dept['department_id'];
                                    })
                                    ->toArray(),
                            ) ?>],
                        borderWidth: 1,
                        backgroundColor: '#EBCCD1' // red
                    },
                ]
            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
    </script>
@endif
<!-- End Theme label Script
=====================================================================-->
<script>
    $(document).ready(function() {
        $(".form-select").select2({
            width: "100%",
        });

        $('.datatable').DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            fixedHeader: true,
            buttons: [{
                    extend: 'excel',
                    title: $('.datatable').data('title'),
                    className: 'btn-xs'
                },
                {
                    extend: 'print',
                    className: 'btn-xs'
                }
            ],
            "drawCallback": function() {
                $('.dataTables_paginate > .pagination a').addClass('btn btn-xs');
            }
        });


        // notification
        setTimeout(function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                showMethod: 'slideDown',
                timeOut: 4000,
                positionClass: "toast-top-right"
            };
            @if (session()->has('success'))
                toastr.success('{{ session()->get('success') }}', 'Success');
            @endif
            @if (session()->has('error'))
                toastr.error('{{ session()->get('error') }}', 'Error');
            @endif

        }, 100);
    });
    $(document).ajaxComplete(function() {
        $(".form-select").select2({
            width: "100%",
        });
    });

    // Bootstrap's own .table-responsive class (already loaded) gives a table
    // horizontal scroll on small screens, but almost none of this app's ~60
    // tables are wrapped in it. Rather than edit every view, wrap any bare
    // <table> at runtime -- both on first load and every time a modal loads
    // one via AJAX, since that content arrives after the page is ready.
    function makeTablesResponsive(scope) {
        $('table', scope || document).each(function () {
            var $table = $(this);
            if (!$table.closest('.table-responsive').length) {
                $table.wrap('<div class="table-responsive"></div>');
            }
        });
    }

    function openAddModal(title, link) {
        $.ajax({
            url: link,
            success: function(result) {
                $('#add-modal #modal-title').text(title);
                $('#add-modal #modal-body').html(result);
                makeTablesResponsive('#add-modal #modal-body');
                $('#add-modal').modal('show');
            },
        });
    }

    function openEditModal(title, link) {
        $.ajax({
            url: link,
            success: function(result) {
                $('#edit-modal #modal-title').text(title);
                $('#edit-modal #modal-body').html(result);
                makeTablesResponsive('#edit-modal #modal-body');
                $('#edit-modal').modal('show');
            },
        });
    }

    function openDeleteModal(title, link) {
        $.ajax({
            url: link,
            success: function(result) {
                $('#delete-modal #modal-title').text(title);
                $('#delete-modal #modal-body').html(result);
                makeTablesResponsive('#delete-modal #modal-body');
                $('#delete-modal').modal('show');
            },
        });
    }

    //used in work-MVC

    $(document).ready(function() {
        $('#location_type').on('change', function() {
            changeLocationTypeLayout(this.value);
        });
    });
    $(document).ready(function() {
        $("#wpClosedForm").hide();
        $("#wpCompleteForm").hide();
        $("#wpRejectForm").hide();
        $('#CWorkStatus').on('change', function() {
            ChangeCurrentWorkStatus(this.value);
        });
        // Sync the visible sub-form with whatever status is already selected
        // (e.g. the work's current status), not just on the next change.
        if ($('#CWorkStatus').length) {
            ChangeCurrentWorkStatus($('#CWorkStatus').val());
        }
    });
    $(document).ready(function() {
        if ($("#NoTender").val() != 0) {
            $('#NoTender').prop('checked', true);
            $('.checkedTender').hide();

        }

        $('#NoTender').on('change', function() {
            jQuery('.checkedTender').toggle('show');

            $value = $('#NoTender').val();
            if ($('#NoTender').val() == 1) {
                $("#NoTender").val(0);
            } else {
                $("#NoTender").val(1);
            }
            $('#tender_startDate').prop('required', false);
            $('#tender_endDate').prop('required', false);

            // $("#tender_startDate").attr("disabled", true);
            // $("#tender_endDate").attr("disabled", true);
            // jQuery('#tender_endDate').toggle('show');

        });
    });

    $(document).ready(function() {
        $('#city').on('change', function() {
            cityToWard(this.value, "#ward");
        });
    });
    $(document).ready(function() {
        $('#block').on('change', function() {
            blockToGramPanchayat(this.value, "#gp");
        });
    });
    $(document).ready(function() {
        $('#gp').on('change', function() {
            grampanchayatToVillages(this.value, "#village");
        });
    });


    $(document).ready(function() {
        $('#work_type').on('change', function() {
            WorkTypeToMBStages(this.value, "#stages");
        });
    });

    $(document).ready(function() {
        $('#office').on('change', function() {
            OfficeToEmployee(this.value, "#employeeAdmin");
        
            // alert($('emoloyeeAdmin').val());
            // OfficeToEmployee($('#office').val(), "#employeeAdmin");
        });
    });

    function changeLocationTypeLayout(locationTypeValue = 1) {
        if (locationTypeValue == 1) {
            $(".urbanType").hide();
            $(".ruralType").show();
        } else {
            $(".ruralType").hide();
            $(".urbanType").show();
        }
    }

    // The dropdown now lists every status in work_statuses, not four fixed
    // options, but only three of them switch to a dedicated form: complete
    // (10), closed (11) and rejected (12). Anything else — every earlier
    // stage of the work — falls through to the ongoing-progress form.
    function ChangeCurrentWorkStatus(value) {
        value = parseInt(value, 10);

        if (value === 10) {
            $("#wpStagesForm").hide();
            $("#wpClosedForm").hide();
            $("#wpCompleteForm").show();
            $("#wpRejectForm").hide();
        } else if (value === 11) {
            $("#wpStagesForm").hide();
            $("#wpClosedForm").show();
            $("#wpCompleteForm").hide();
            $("#wpRejectForm").hide();
        } else if (value === 12) {
            $("#wpStagesForm").hide();
            $("#wpClosedForm").hide();
            $("#wpCompleteForm").hide();
            $("#wpRejectForm").show();
        } else {
            $("#wpStagesForm").show();
            $("#wpClosedForm").hide();
            $("#wpCompleteForm").hide();
            $("#wpRejectForm").hide();
        }
    }


    function cityToWard(cityID, wardLayoutID, selectedValue = '') {
        var city_id = cityID;
        $(wardLayoutID).html('');
        $.ajax({
            url: "{{ url('api/fetch-CityToWard') }}",
            type: "POST",
            data: {
                city_id: city_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $(wardLayoutID).html('<option value="">-- Select ward --</option>');
                $.each(result.ward, function(key, value) {
                    var isSelected = (value.ward_id === selectedValue) ? 'selected' : '';
                    $(wardLayoutID).append('<option value="' + value.ward_id + '" ' + isSelected +
                        '>' + value.ward_name + '</option>');
                });
            }
        });
    }

    function blockToGramPanchayat(blockID, gramPanchanyalLayoutID, selectedValue = '') {
        var block_id = blockID;
        $(gramPanchanyalLayoutID).html('');
        $.ajax({
            url: "{{ url('api/fetch-BlockToGrampanchayat') }}",
            type: "POST",
            data: {
                block_id: block_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {

                $(gramPanchanyalLayoutID).html('<option value="">-- Select Grampanchayat --</option>');
                $.each(result.gp, function(key, value) {
                    var isSelected = (value.grampanchayat_id === selectedValue) ? 'selected' : '';
                    $(gramPanchanyalLayoutID).append('<option value="' + value
                        .grampanchayat_id + '" ' + isSelected + '>' + value
                        .grampanchayat_name + '</option>');
                });
            }
        });
    }



    function grampanchayatToVillages(gramPanchayatID, villegeLayoutID, selectedValue = '') {
        var gp_id = gramPanchayatID;
        $(villegeLayoutID).html('');
        $.ajax({
            url: "{{ url('api/fetch-GpToVillage') }}",
            type: "POST",
            data: {
                gp_id: gp_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $(villegeLayoutID).html('<option value="">-- Select Village --</option>');
                $.each(result.village, function(key, value) {
                    var isSelected = (value.village_id === selectedValue) ? 'selected' : '';
                    $(villegeLayoutID).append('<option value="' + value
                        .village_id + '" ' + isSelected + '>' + value
                        .village_name + '</option>');
                });
            }
        });

    }

    function WorkTypeToMBStages(id, stages, selectedValue = '') {
        var typeId = id;
        $(stages).html('');
        $.ajax({
            url: "{{ url('api/fetch-WorkTypeToMBStages') }}",
            type: "POST",
            data: {
                typeId: typeId,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $(stages).html('<option value="">-- Select MB Stage --</option>');
                $.each(result.MBstages, function(key, value) {
                    var isSelected = (value.work_type_stage_id === selectedValue) ? 'selected' : '';
                    $(stages).append('<option value="' + value
                        .work_type_stage_id + '" ' + isSelected + '>' + value
                        .work_type_stage_name + '</option>');
                });
            }
        });

    }

    $(document).ready(function() {

        "use strict"; // Start of use strict

        $('#dataTableExample1').DataTable({
            "dom": "<'row'<'col-sm-6'l><'col-sm-6'f>>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            "lengthMenu": [
                [6, 25, 50, -1],
                [6, 25, 50, "All"]
            ],
            "iDisplayLength": 6
        });

        $("#dataTableExample2").DataTable({
            dom: "<'row'<'col-sm-4'l><'col-sm-4 text-center'B><'col-sm-4'f>>tp",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            buttons: [{
                    extend: 'copy',
                    className: 'btn-sm'
                },
                {
                    extend: 'csv',
                    title: 'ExampleFile',
                    className: 'btn-sm'
                },
                {
                    extend: 'excel',
                    title: 'ExampleFile',
                    className: 'btn-sm'
                },
                {
                    extend: 'pdf',
                    title: 'ExampleFile',
                    className: 'btn-sm'
                },
                {
                    extend: 'print',
                    className: 'btn-sm'
                }
            ]
        });

    });

    function OfficeToEmployee(officeID, empLayoutID, selectedValue = '') {
        var office_id = officeID;
        $(employeeAdmin).html('');
        $.ajax({
            url: "{{ url('api/fetch-OfficeToEmployee') }}",
            type: "POST",
            data: {
                office_id: office_id,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $(employeeAdmin).html('<option value="">-- कर्मचारी चुनें --</option>');
                $.each(result.employee, function(key, value) {
                    var isSelected = (value.emp_id === selectedValue) ? 'selected' : '';
                    $(employeeAdmin).append('<option value="' + value
                        .emp_id + '" ' + isSelected + '>' + value
                        .emp_name + '</option>');
                });
            }
        });

    }

    function saveWork() {
        console.log('Save Task button clicked');

        // Show loader button
        $('#loader').show();

        // Serialize the form data and include the CSRF token
        // var formData = $('#createTaskForm').serialize() + '&_token={{ csrf_token() }}';

        // Disable the form elements to prevent double submission
        // $('#createTaskForm :input').prop('disabled', true);

        // Send an AJAX request
        $.ajax({
            type: 'POST',
            url: '{{ route('work.store') }}', // Adjust the route as needed
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                // This is called before the request is sent
            },
            success: function (data) {
                // Handle success (e.g., show a success message)
                // console.log('Record saved successfully:', data);

                // Redirect to the task list page
                window.location.href = '{{ route("work.index") }}';
            },
            error: function (xhr, status, error) {
                // Handle errors (e.g., show an error message)
                console.error('Error saving record:', error);

                // Log the response for further inspection
                console.log(xhr.responseText);
            },
            complete: function () {
                // Re-enable the form elements
                // $('#createTaskForm :input').prop('disabled', false);

                // Hide loader button after the request is complete
                $('#loader').hide();
            }
        });
    }

    // Runs last (registered after every other ready() block above, including
    // the DataTables initializations), so it wraps tables in their final,
    // fully-rendered form.
    $(document).ready(function () {
        makeTablesResponsive();
    });

</script>
