<!DOCTYPE html>
<html lang="en">

<head>
    {{-- @vite('resources/js/jquery.datatables.min.js')
    @vite('resources/css/jquery.datatables.min.css') --}}
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}

</head>

<body>
    <div class="mx-40 p-4 my-10">
        <table id="myTable" class="display w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-200 uppercase bg-orange-700 dark:text-orange-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Column 1</th>
                    <th scope="col" class="px-6 py-3">Column 2</th>
                </tr>
            </thead>
            <tbody>
                <tr  class="border-b bg-gray-200 dark:border-gray-300 hover:bg-orange-300 hover:text-black">
                    <td>Row 1 Data 1</td>
                    <td>Row 1 Data 2</td>
                </tr>
                <tr  class="border-b bg-gray-200 dark:border-gray-300 hover:bg-orange-300 hover:text-black">
                    <td>Row 2 Data 1</td>
                    <td>Row 2 Data 2</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>

</html>
