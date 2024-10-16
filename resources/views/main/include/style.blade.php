<!--favicon-->
<link rel="icon" href="{{ url('admin/assets/images/logo-profile.png') }}" type="image/png" />

<!--plugins-->
<link href="{{ url('admin/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
<link href="{{ url('admin/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
<link href="{{ url('admin/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
<link href="{{ url('admin/assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- loader-->
<link href="{{ url('admin/assets/css/pace.min.css') }}" rel="stylesheet" />
<!-- Bootstrap CSS -->
<link href="{{ url('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ url('admin/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
<link href="{{ url('admin/assets/css/app.css') }}" rel="stylesheet">
<link href="{{ url('admin/assets/css/icons.css') }}" rel="stylesheet">
<!-- Datepicker -->
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<!-- Theme Style CSS -->
<link rel="stylesheet" href="{{ url('admin/assets/css/header-colors.css') }}" />
<!-- Select2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
<style>
    /* Fixed sidenav, full height */
    .sidenav {
        height: 100%;
        width: 200px;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #ffffff;
        overflow-x: hidden;
        padding-top: 20px;
    }

    /* Style the sidenav links and the dropdown button */
    .sidenav a,
    .dropdown-btn {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 15px;
        color: #000000;
        display: block;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        outline: none;
    }

    /* On mouse-over */
    .sidenav a:hover,
    .dropdown-btn:hover {
        color: #000000;
    }

    /* Dropdown container (hidden by default). Optional: add a lighter background color and some left padding to change the design of the dropdown content */
    .dropdown-container {
        display: none;
        background-color: #ffffff;
        padding-left: 30px;
    }

</style>
@stack('style')
