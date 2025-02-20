<div class="shadow-xl p-2 rounded-lg bg-white">
    <table id="myTable" class="display">
        <thead>
            <tr>
                <th style="text-align: center">Program Code</th>
                <th style="text-align: center">Program Title</th>
                {{-- <th class="hidden">Branch</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($programs as $program)
                <tr>
                    <td>{{ $program->programCode }}</td>
                    <td>{{ $program->programTitle }}</td>
                    {{-- <td class="hidden">{{ $program->branch }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
