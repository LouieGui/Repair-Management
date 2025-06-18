<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <!-- Main Layout: Sidebar + Content -->
    <div class="flex min-h-screen">
        <!-- Side Bar) -->
        <div id="hs-sidebar-collapsible-group">
            <div class="w-64 h-screen">
                <x-nav-bar></x-nav-bar>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-10">
            <div class="container mx-auto">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script src="./assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="./assets/vendor/datatables.net/js/dataTables.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>