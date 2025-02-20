<div class="shadow-xl p-2 rounded-lg bg-white">
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th style="text-align: center">No.</th>
                <th style="text-align: center">Course Code</th>
                <th style="text-align: center">Course Title</th>
                <th style="text-align: center">Program Code</th>
                {{-- <th style="text-align: center">Action</th> --}}
                {{-- <th class="hidden">Branch</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($programs as $program)
                @foreach ($program->courses as $course)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $course->courseCode }}</td>
                        <td>{{ $course->courseTitle }}</td>
                        <td class="text-center">{{ $program->programCode }}</td>
                        {{-- <td class="text-center">
                            <button type="button"
                                class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                                Class Record
                            </button>
                        </td> --}}
                        {{-- <td class="hidden">{{ $program->branch }}</td> --}}
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
