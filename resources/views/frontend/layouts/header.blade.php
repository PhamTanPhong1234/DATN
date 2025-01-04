<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>CDSyncs - Khơi Gợi Đam mê</title>
        <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon/favicon.png" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="assets/css/vendor/vendor.min.css">
        <link rel="stylesheet" href="assets/css/plugins/plugins.min.css">
        <link rel="stylesheet" href="assets/css/style.min.css">
        <link rel="stylesheet" href="assets/css/responsive.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Include Popper.js and Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <style>
        .toast.toast-success {
            background-color: #28a745; 
            color: white;
        }
        .toast.toast-error {
            background-color: #dc3545; 
            color: white;
        }
        .toast.toast-warning {
            background-color: #ffc107;
            color: black;
        }
        .toast.toast-info { 
            background-color: #17a2b8;
            color: white;
        }
    </style>
    <body>
        <div id="main"></div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            $(document).ready(function() {
                @if (session('success'))
                    toastr.success("{{ session('success') }}", "Thành công", {
                        timeOut: 3000, 
                        hideDuration: 1000,
                        extendedTimeOut: 1000,
                    });
                @endif
                @if (session('error'))
                    toastr.error("{{ session('error') }}", "Lỗi", {
                        timeOut: 3000, 
                        hideDuration: 1000,
                        extendedTimeOut: 1000,
                    });
                @endif
                @if (session('warning'))
                    toastr.warning("{{ session('warning') }}", "Cảnh báo", {
                        timeOut: 3000, 
                        hideDuration: 1000,
                        extendedTimeOut: 1000,
                    });
                @endif
                @if (session('info'))
                    toastr.info("{{ session('info') }}", "Thông tin", {
                        timeOut: 3000, 
                        hideDuration: 1000,
                        extendedTimeOut: 1000,
                    });
                @endif
            });
        </script>
    </body>
</html>
