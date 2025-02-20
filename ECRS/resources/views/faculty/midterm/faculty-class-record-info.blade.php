<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="/images/logo-ecrs.png">
    @vite('resources/js/app.js')
    @vite('resources/js/class-record-quiz.js')
    <title>Midterm | {{ ucwords($assessmentType) }}</title>
</head>

<body>
    @extends('layout.ClassRecordLayout')

    @section('classrecordcontent')
        <div class="flex justify-center w-full">
            <div class="flex flex-col w-full">
                <div id="student-info-section" class="shadow-xl p-2 rounded-lg my-5">
                    <div class="flex justify-end items-center mb-2">
                        <div id="add-{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-btn"
                            class="text-lg flex gap-2 justify-center items-center text-red-900 rounded-lg p-2 shadow-lg border border-gray-300">
                            <i class="fa-solid fa-file-circle-plus cursor-pointer z-10 text-red-900"></i>
                            <div class="flex justify-center items-center">
                                <span class="text-md">Add {{ ucwords($assessmentType) }}</span>
                            </div>
                        </div>

                    </div>
                    <table id="myTable" class="display">
                        <thead>
                            <tr>
                                <th style="text-align: center">No.</th>
                                <th style="text-align: center">{{ ucwords($assessmentType) }} Title</th>
                                @if (
                                    !request()->is('faculty/class-record/midterm/attendance') &&
                                        !request()->is('faculty/class-record/finals/attendance'))
                                    <th style="text-align: center">{{ ucwords($assessmentType) }} Total Score</th>
                                    <th style="text-align: center">{{ ucwords($assessmentType) }} Passing Score</th>
                                @endif
                                <th style="text-align: center">{{ ucwords($assessmentType) }} Date</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assessments as $assessment)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td style="text-align: center">{{ $assessment->assessmentName }}</td>
                                    @if (
                                        !request()->is('faculty/class-record/midterm/attendance') &&
                                            !request()->is('faculty/class-record/finals/attendance'))
                                        <td style="text-align: center">{{ $assessment->totalItem }}</td>
                                        <td style="text-align: center">{{ $assessment->passingItem }}</td>
                                    @endif
                                    <td style="text-align: center">
                                        {{ date('m-d-Y', strtotime($assessment->assessmentDate)) }}</td>
                                    <td class="text-center text-2xl flex gap-1 justify-center items-center">
                                        <form action="/store-assessment-id" method="POST">
                                            @csrf
                                            <input type="hidden" name="assessmentID"
                                                value="{{ $assessment->assessmentID }}">
                                            <button type="submit"
                                                class="text-white hover:bg-gray-200 hover:rounded-md p-1 text-center w-full flex justify-center">
                                                <i class="fa-solid fa-book text-blue-500"></i>
                                            </button>
                                        </form>
                                        <i 
                                            class="fa-solid fa-pen-to-square text-green-500 edit-assessment hover:bg-gray-200 hover:rounded-md p-1 cursor-pointer"
                                            data-assessment-id="{{ $assessment->assessmentID }}"
                                            data-assessment-name="{{ $assessment->assessmentName }}"
                                            data-assessment-date="{{ date('m-d-Y', strtotime($assessment->assessmentDate)) }}"
                                            data-total-item="{{ $assessment->totalItem }}"
                                            data-passing-item="{{ $assessment->passingItem }}"
                                            data-assessment-type="{{ $assessmentType }}"
                                        ></i>

                                        <i class="fa-solid fa-trash text-red-500"></i>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <x-modal title="Add {{ ucwords($assessmentType) }}"
            modalId="{{ 'add-' . strtolower(str_replace(' ', '-', $assessmentType)) . '-modal' }}"
            closeBtnId="{{ 'close-btn-add-' . strtolower(str_replace(' ', '-', $assessmentType)) }}">
            <div class="bg-white rounded-lg shadow-xl transform transition-all w-full max-w-screen-sm px-10">
                <div class="flex gap-10 mt-5">
                    <div class="flex flex-col">
                        <form id="add-assessment-form" action="{{ route('assessment.store-midterms') }}">
                            @csrf
                            <input type="hidden" name="classRecordID" value="{{ $classRecords->classRecordID }}" />
                            <input type="hidden" name="assessmentType" value="{{ $assessmentType }}" />

                            <div class="flex gap-5">
                                <div class="my-2 flex flex-col gap-2">
                                    <label for="{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-name"
                                        class="block font-bold">
                                        {{ ucwords($assessmentType) }} Title:
                                    </label>
                                    <input type="text" name="assessmentName"
                                        id="{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-name"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-72 w-full"
                                        placeholder="Enter title" autocomplete="off" required />
                                </div>

                                <div class="my-2 flex flex-col gap-2">
                                    <label for="{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-date"
                                        class="block font-bold">
                                        {{ ucwords($assessmentType) }} Date:
                                    </label>
                                    <input type="date" id="{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-date"
                                        name="assessmentDate"
                                        value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-56 w-full"
                                        autocomplete="off" required />

                                </div>
                            </div>

                            @if (
                                !request()->is('faculty/class-record/midterm/attendance') &&
                                    !request()->is('faculty/class-record/finals/attendance'))
                                <div class="flex gap-5">
                                    <div class="my-2 flex flex-col gap-2">
                                        <label for="{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-total"
                                            class="block font-bold">
                                            {{ ucwords($assessmentType) }} Total Score:
                                        </label>
                                        <input type="text" name="totalItem"
                                            id="{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-total"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                            placeholder="Enter total score" autocomplete="off" required />
                                    </div>

                                    <div class="my-2 flex flex-col gap-2">
                                        <label for="{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-passing"
                                            class="block font-bold">
                                            {{ ucwords($assessmentType) }} Passing Percentage:
                                        </label>
                                        <input type="text" name="passingItem"
                                            id="{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-passing"
                                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                            placeholder="Enter passing percentage" autocomplete="off" required />
                                    </div>
                                </div>
                            @endif

                            <div class="flex gap-2 justify-center items-center py-6">
                                <button type="submit" class="text-black rounded-lg p-3 shadow-lg border border-gray-300">
                                    <span>Add {{ ucwords($assessmentType) }}</span>
                                </button>
                                <button type="button"
                                    class="text-black rounded-lg p-3 shadow-lg border border-gray-300 close-btn">
                                    <span>Cancel</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-modal>



        <x-modal title="Edit {{ ucwords($assessmentType) }}"
         modalId="{{ 'edit-' . strtolower(str_replace(' ', '-', $assessmentType)) . '-modal' }}"
         closeBtnId="{{ 'close-btn-edit-' . strtolower(str_replace(' ', '-', $assessmentType)) }}">
            <div class="bg-white rounded-lg shadow-xl transform transition-all w-full max-w-screen-sm px-10">
                <div class="flex gap-10 mt-5">
                    <div class="flex flex-col">
                        <form id="edit-assessment-form" action="{{ route('assessment.update-midterms') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="classRecordID" value="{{ $classRecords->classRecordID }}" />
                            <input type="hidden" name="assessmentID" id="edit-assessment-id" />
                            <input type="hidden" name="assessmentType" value="{{ $assessmentType }}" />

                            <div class="flex gap-5">
                                <div class="my-2 flex flex-col gap-2">
                                    <label for="edit-{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-name" class="block font-bold">
                                        {{ ucwords($assessmentType) }} Title:
                                    </label>
                                    <input type="text" name="assessmentName"
                                        id="edit-{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-name"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-72 w-full"
                                        placeholder="Enter title" autocomplete="off" required />
                                </div>

                                <div class="my-2 flex flex-col gap-2">
                                    <label for="edit-{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-date" class="block font-bold">
                                        {{ ucwords($assessmentType) }} Date:
                                    </label>
                                    <input type="date" name="assessmentDate"
                                        id="edit-{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-date"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-56 w-full"
                                        autocomplete="off" required />
                                </div>
                            </div>
                            @if (!request()->is('faculty/class-record/midterm/attendance') &&
                                !request()->is('faculty/class-record/finals/attendance'))
                            <div class="flex gap-5">
                                <div class="my-2 flex flex-col gap-2">
                                    <label for="edit-{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-total" class="block font-bold">
                                        {{ ucwords($assessmentType) }} Total Score:
                                    </label>
                                    <input type="text" name="totalItem"
                                        id="edit-{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-total"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                        placeholder="Enter total score" autocomplete="off" required />
                                </div>

                                <div class="my-2 flex flex-col gap-2">
                                    <label for="edit-{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-passing" class="block font-bold">
                                        {{ ucwords($assessmentType) }} Passing Score:
                                    </label>
                                    <input type="text" name="passingItem"
                                        id="edit-{{ strtolower(str_replace(' ', '-', $assessmentType)) }}-passing"
                                        class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 lg:w-64 w-full"
                                        placeholder="Enter passing score" autocomplete="off" required />
                                </div>
                            </div>
                            @endif

                            <div class="flex gap-2 justify-center items-center py-6">
                                <button type="submit" class="text-black rounded-lg p-3 shadow-lg border border-gray-300">
                                    <span>Edit {{ ucwords($assessmentType) }}</span>
                                </button>
                                <button type="button" class="text-black rounded-lg p-3 shadow-lg border border-gray-300 close-btn">
                                    <span>Cancel</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-modal>        

    @endsection
</body>

</html>
