<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

{{-- <style>
    table.dataTable.display tbody tr:nth-child(odd) {
        background-color: white;
    }

    table.dataTable.display tbody tr:nth-child(even) {
        background-color: #fed7aa;
    }
</style> --}}

<style>
    .buttonDevs {
        display: none;
    }

    .tooltip {
        visibility: hidden;
        width: auto;
        max-width: 200px;
        background-color: #0f172a;
        color: #fff;
        text-align: center;
        padding: 6px;
        border-radius: 6px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    button:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }
</style>

<body>


    <div class="px-14 my-5">
        <table id="myTable"
            class="display w-full text-sm  text-gray-500 dark:text-gray-400 bg-white text-center justify-center animate-fade-in-up ">
            <thead class="text-xs uppercase text-gray-800" style="width:100%">
                <tr>
                    <th scope="col" class="text-lg" style="text-align: center"> <input type="checkbox"
                            class="rounded-full" name="select_all" value="" id="stud_select_all"></th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Student Number</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">First Name</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Middle Name</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Last Name</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Email</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Status</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Profile</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @if (count($tbl_students) > 0)
                    @foreach ($tbl_students as $index => $tbl_student)
                        <tr class=" {{ $index % 2 === 0 ? 'bg-gray-300' : 'bg-white' }} hover:bg-gray-100 ">
                            <td class=" text-lg ">
                                <input type="checkbox" class="stud_checkbox rounded-full text-center"
                                    value="{{ $tbl_student->login_ID }}" data-id="{{ $tbl_student->login_ID }}"
                                    data-name="{{ $tbl_student->first_name }}"
                                    data-studnum="{{ $tbl_student->student_num }}">
                            </td>
                            <td class=" text-sm">
                                {{ $tbl_student->student_num }}
                            </td>
                            <td class=" text-sm">{{ $tbl_student->first_name }}</td>
                            <td class=" text-sm">{{ $tbl_student->middle_name }}</td>
                            <td class=" text-sm"> {{ $tbl_student->last_name }}</td>
                            <td class=" text-sm">{{ $tbl_student->email }}</td>

                            <td class=" text-sm">
                                @if ($tbl_student->isActive === 1)
                                    <button
                                        class=" relative px-2 py-2  toggleBtn rounded-md bg-green-500 text-white text-sm"
                                        id="toggleBtn" disabled>Active</button>
                                @elseif ($tbl_student->isActive === 0)
                                    <button
                                        class=" relative px-2 py-2  toggleBtn rounded-md bg-red-500 text-white text-sm"
                                        id="toggleBtn" disabled>Inactive</button>
                                @else
                                    <button
                                        class="relative px-2 py-2 toggleBtnActivate  rounded-md bg-orange-500 text-white text-sm"
                                        id="toggleBtnActivate" disabled>Disabled</button>
                                @endif
                            </td>
                            <td class=" text-sm">
                                <img class="mx-auto rounded-full border-2 border-gray-400"
                                    src="{{ asset('avatars/' . $tbl_student->profile_photo_path) }}"
                                    style="height: 3rem; width: 3rem;">
                            </td>
                            <td class=" text-sm">
                                <div class="flex justify-center gap-2">
                                    <button>
                                        <i id="edit-stud-{{ $tbl_student->login_ID }}"
                                            class="fa-solid fa-pen-to-square text-lg text-gray-400 cursor-pointer hover:text-gray-600"
                                            data-stud-id="{{ $tbl_student->login_ID }}"
                                            data-stud-studnum="{{ $tbl_student->student_num }}"
                                            data-stud-firstname="{{ $tbl_student->first_name }}"
                                            data-stud-middlename="{{ $tbl_student->middle_name }}"
                                            data-stud-lastname="{{ $tbl_student->last_name }}"
                                            data-stud-email="{{ $tbl_student->email }}">
                                        </i>
                                    </button>
                                    <button>
                                        <i class="fa-solid fa-trash text-lg text-red-600 cursor-pointer hover:text-red-800"
                                            id="delete-stud-btn"
                                            onclick="modalDeleteStudent('{{ $tbl_student->login_ID }}','{{ $tbl_student->student_num }}' ,'{{ $tbl_student->first_name }}')">
                                        </i>
                                    </button>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    {{-- Edit student --}}
    <div class="modal-edit-stud hidden fixed inset-0 overflow-y-auto z-40">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0  ">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="z-40 absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="p-2">
                    <div class="flex justify-between">
                        <div class="flex gap-1">
                            <img class="" src="{{ URL('images/Subject.png') }}"
                                style="height: 3rem; width: 3rem;">
                            <h1
                                class="text-white text-3xl text-center  font-bold text-shadow-[0_4px_5px_#808080]  mt-1">
                                Edit Information
                            </h1>
                        </div>
                        <i id="close-edit-stud"
                            class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="mx-5 mb-5">
                    <form method="POST" action="" id="student-info-update">
                        @csrf
                        <input type="hidden" name="stud_ID" id="stud_ID">

                        <div class="my-2 flex flex-row items-center gap-2">
                            <label for="stud_num" class="block  font-bold text-indigo-900">Student Number: </label>
                            <input type="text" name="stud_num" id="stud_num"
                                class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                autocomplete="off" required />
                            <span3 class="text-danger text-red-600">
                                @error('stud_num')
                                    {{ $message }}
                                @enderror
                            </span3>
                        </div>
                        <div class="my-2 flex flex-row items-center gap-2">
                            <label for="first_name" class="block  font-bold text-indigo-900">First Name:</label>
                            <input type="text" name="first_name" id="first_name"
                                class="ml-10 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                autocomplete="off" required />
                            <span1 class="text-danger text-red-600">
                                @error('first_name')
                                    {{ $message }}
                                @enderror
                            </span1>
                        </div>
                        <div class="my-2 flex flex-row items-center">
                            <label for="middle_name" class="block  font-bold text-indigo-900">Middle Name: </label>
                            <input type="text" name="middle_name" id="middle_name"
                                class=" ml-8 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Middle Name" autocomplete="off" />
                            <span class="text-danger text-red-600">
                                @error('middle_name')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div class="my-2 flex flex-row items-center">
                            <label for="last_name" class="block  font-bold text-indigo-900">Last Name </label>
                            <input type="text" name="last_name" id="last_name"
                                class=" ml-14 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                autocomplete="off" required />
                            <span2 class="text-danger text-red-600">
                                @error('last_name')
                                    {{ $message }}
                                @enderror
                            </span2>
                        </div>
                        <div class="my-2 flex justify-between items-center">
                            <label for="email" class="block  font-bold text-indigo-900">Email: </label>
                            <input type="text" name="email" id="email"
                                class=" ml-20 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                autocomplete="off" required />
                            <span3 class="text-danger text-red-600">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </span3>
                        </div>
                        <div class="mt-2 py-2 flex justify-end gap-2">
                            <button type="button" id="cancel-edit-stud"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- Delete Student --}}
    <div class="modal-delete-stud hidden fixed inset-0 overflow-y-auto z-40">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0  ">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="z-40 absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="p-2">
                    <div class="flex justify-between">
                        <div class="flex gap-1">
                            <img class="" src="{{ URL('images/Subject.png') }}"
                                style="height: 3rem; width: 3rem;">
                            <h1
                                class="text-white text-3xl text-center  font-bold text-shadow-[0_4px_5px_#808080]  mt-1">
                                Delete Student
                            </h1>
                        </div>
                        <i id="close-delete-stud"
                            class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="text-white text-2xl text-center my-5 font-bold text-shadow-[0_4px_5px_#808080]">
                    Are you sure you want to delete this?
                </div>
                <div class="mx-5 mb-5">
                    <form method="POST" id="stud-delete" action="">
                        @csrf
                        <input type="hidden" name="stud_ID" id="stud_ID">

                        <div class="mb-2 flex justify-center mx-5 flex-col">

                            <div class="w-full mb-2 hidden">
                                <input type="hidden" name="stud_id_input" id="stud_id_input" value="">
                                <p><strong>STUD ID:</strong> <span id="stud_id_text"></span></p>
                            </div>

                            <div class="my-2 flex flex-row items-center gap-2">
                                <label for="delete_stud_num" class="block  font-bold text-indigo-900">Student Number:
                                </label>
                                <input type="hidden" name="delete_stud_num_input" id="delete_stud_num_input"
                                    value="">
                                <span id="delete_stud_num_text" name="delete_stud_num_text"
                                    class="border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>


                            <div class="my-2 flex flex-row items-center gap-2">
                                <label id="" for="" class="block  font-bold text-indigo-900">Student
                                    Name:</label>
                                <input type="hidden" name="delete_fn_input" id="delete_fn_input" value="">
                                <span id="delete_fn_text" type="hidden" name="delete_fn_text"
                                    class="ml-5 border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>
                        </div>
                        <div class="mt-2 py-2 flex justify-end gap-2">
                            <button type="button" id="cancel-delete-stud"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "responsive": true,
            scrollCollapse: false,
            scrollY: 300,
            pagingType: 'simple',
            "paging": true,
            "lengthMenu": [10, 25, 50, 75, 100],
            "order": [],
            'columnDefs': [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'checkboxes': {
                    'selectRow': true,
                }
            }],
        });
    });

    function modalInfoStudent() {
        const form = document.querySelector('#student-info-update');
        const modal_edit_stud = document.querySelector('.modal-edit-stud');
        const span = document.querySelector('#close-edit-stud');
        const cancelModalButton = document.getElementById('cancel-edit-stud');

        function closeModal() {
            $("#side-bar").show();
            modal_edit_stud.classList.add('hidden');
        }

        span.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', closeModal);


        const editButtons = document.querySelectorAll('[id^="edit-stud-"]');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {

                const studNumber = button.getAttribute('data-stud-studnum');
                const studFname = button.getAttribute('data-stud-firstname');
                const studMname = button.getAttribute('data-stud-middlename');
                const studLname = button.getAttribute('data-stud-lastname');
                const studEmail = button.getAttribute('data-stud-email');
                const stud_ID = button.getAttribute('data-stud-id');

                document.querySelector('#stud_num').value = studNumber;
                document.querySelector('#first_name').value = studFname;
                document.querySelector('#middle_name').value = studMname;
                document.querySelector('#last_name').value = studLname;
                document.querySelector('#email').value = studEmail;
                document.querySelector('#stud_ID').value = stud_ID;

                modal_edit_stud.classList.remove('hidden');
                $("#side-bar").hide();
            });
        });
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            $.ajax({
                type: 'POST',
                url: '/update-student', // Replace with your actual route URL
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        successCRUD(response.message);
                        modal_edit_stud.classList.add('hidden');
                        studentAcc();
                        // Accounts();
                    } else {
                        errorModal(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // errorModal(response.message);
                }
            });
        });
    }
    modalInfoStudent();


    function modalDeleteStudent(login_ID, student_num, first_name) {
        const form = document.querySelector('#stud-delete');
        const modal_delete_stud = document.querySelector('.modal-delete-stud');
        const span = document.querySelector('#close-delete-stud');
        const cancelModalButton = document.getElementById('cancel-delete-stud');

        const buttons2 = document.querySelectorAll('#delete-stud-btn');

        function closeModal() {
            $("#side-bar").show();
            modal_delete_stud.classList.add('hidden');
        }

        span.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', closeModal);

        document.getElementById('stud_id_input').value = login_ID;
        document.getElementById('stud_id_text').innerText = login_ID;

        document.getElementById('delete_stud_num_input').value = student_num;
        document.getElementById('delete_stud_num_text').innerText = student_num;

        document.getElementById('delete_fn_input').value = first_name;
        document.getElementById('delete_fn_text').innerText = first_name;

        buttons2.forEach(function(button) {
            button.addEventListener('click', function() {
                modal_delete_stud.classList.remove('hidden');
                $("#side-bar").hide();
            });
        });
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            const stud_id = document.getElementById('stud_id_input').value;
            $.ajax({
                type: 'POST',
                url: '/delete-student/' + stud_id,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        successCRUD(response.message);
                        modal_delete_stud.classList.add('hidden');
                        studentAcc();
                        // Accounts();
                    } else {
                        // errorModal(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // errorModal(response.message);
                }
            });
        });
    }
    modalDeleteStudent();



    $(function() {
        const selectedIds = [];
        const selectedName = [];
        const selectedStudNum = [];
        const selectedIds2 = [];
        const selectedName2 = [];
        const selectedStudNum2 = [];
        const selectedIds3 = [];
        const selectedName3 = [];
        const selectedStudNum3 = [];
        const checkboxes = document.querySelectorAll('.stud_checkbox');
        const selectAllCheckbox = document.getElementById('stud_select_all');
        const buttonDev = document.getElementById('buttonDevs');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function(event) {
                const studId = event.target.dataset.id;
                const studName = event.target.dataset.name;
                const studNum = event.target.dataset.studnum;


                if (event.target.checked) {
                    selectedIds.push(studId);
                    selectedName.push(studName);
                    selectedStudNum.push(studNum);

                    selectedIds2.push(studId);
                    selectedName2.push(studName);
                    selectedStudNum2.push(studNum);

                    selectedIds3.push(studId);
                    selectedName3.push(studName);
                    selectedStudNum3.push(studNum);

                } else {
                    const index = selectedIds.indexOf(studId);
                    const name = selectedName.indexOf(studName);
                    const studnum = selectedStudNum.indexOf(studNum);

                    const index2 = selectedIds2.indexOf(studId);
                    const name2 = selectedName2.indexOf(studName);
                    const studnum2 = selectedStudNum2.indexOf(studNum);

                    const index3 = selectedIds3.indexOf(studId);
                    const name3 = selectedName3.indexOf(studName);
                    const studnum3 = selectedStudNum3.indexOf(studNum);

                    if (index !== -1) {
                        selectedIds.splice(index, 1);
                        selectedName.splice(index, 1);
                        selectedStudNum.splice(index, 1);

                        selectedIds2.splice(index, 1);
                        selectedName2.splice(index, 1);
                        selectedStudNum2.splice(index, 1);

                        selectedIds3.splice(index, 1);
                        selectedName3.splice(index, 1);
                        selectedStudNum3.splice(index, 1);
                    }
                }

                console.log('Selected IDs:', selectedIds);
                if (selectedIds.length > 0) {
                    buttonDev.classList.remove('hidden');

                } else {
                    buttonDev.classList.add('hidden');

                }

                if (selectedIds2.length > 0) {
                    buttonDev.classList.remove('hidden');
                } else {
                    buttonDev.classList.add('hidden');
                }

                if (selectedIds3.length > 0) {
                    buttonDev.classList.remove('hidden');
                } else {
                    buttonDev.classList.add('hidden');
                }
            });
        });

        selectAllCheckbox.addEventListener('change', function(event) {
            const isChecked = event.target.checked;

            const dataTable = $('#myTable').DataTable();
            const filteredRows = dataTable.rows({
                search: 'applied'
            }).nodes().toArray();

            checkboxes.forEach(function(checkbox) {
                const studId = checkbox.dataset.id;
                const studName = checkbox.dataset.name;
                const studNum = checkbox.dataset.studnum;
                if (isChecked) {
                    if (filteredRows.includes(checkbox.closest('tr'))) {
                        selectedIds.push(studId);
                        selectedName.push(studName);
                        selectedStudNum.push(studNum);

                        selectedIds2.push(studId);
                        selectedName2.push(studName);
                        selectedStudNum2.push(studNum);

                        selectedIds3.push(studId);
                        selectedName3.push(studName);
                        selectedStudNum3.push(studNum);

                        checkbox.checked = true;
                    }
                } else {
                    const index = selectedIds.indexOf(studId);
                    const name = selectedName.indexOf(studName);
                    const studnum = selectedStudNum.indexOf(studNum);

                    const index2 = selectedIds2.indexOf(studId);
                    const name2 = selectedName2.indexOf(studName);
                    const studnum2 = selectedStudNum2.indexOf(studNum);

                    const index3 = selectedIds3.indexOf(studId);
                    const name3 = selectedName3.indexOf(studName);
                    const studnum3 = selectedStudNum3.indexOf(studNum);

                    if (index !== -1) {
                        selectedIds.splice(index, 1);
                        selectedName.splice(index, 1);
                        selectedStudNum.splice(index, 1);

                        selectedIds2.splice(index, 1);
                        selectedName2.splice(index, 1);
                        selectedStudNum2.splice(index, 1);

                        selectedIds3.splice(index, 1);
                        selectedName3.splice(index, 1);
                        selectedStudNum3.splice(index, 1);
                    }
                    checkbox.checked = false;
                }
            });

            console.log('Selected IDs:', selectedIds);
            console.log('Name:', selectedName);

            if (selectedIds.length > 0) {
                buttonDev.classList.remove('hidden');
            } else {
                buttonDev.classList.add('hidden');
            }

            if (selectedIds2.length > 0) {
                buttonDev.classList.remove('hidden');
            } else {
                buttonDev.classList.add('hidden');
            }

            if (selectedIds3.length > 0) {
                buttonDev.classList.remove('hidden');
            } else {
                buttonDev.classList.add('hidden');
            }
        });



        $("#batch-deact-btn").click(function(e) {
            e.preventDefault();

            const modal_disable_acc = document.querySelector('.modal-disable-acc');
            const form = document.querySelector('#disable-form');
            const span3 = document.querySelector('#disable-close');
            const cancelModalButton3 = document.getElementById('disable-cancel');

            const idInput = document.getElementById('id_input_deac');
            const idText = document.getElementById('id_text_deac');
            idInput.value = selectedIds.join(', ');
            idText.innerText = selectedIds.join(', ');

            const nameInput = document.getElementById('name_input_deac');
            const nameText = document.getElementById('name_text_deac');

            nameInput.value = selectedName.join(', ');
            nameText.innerText = selectedName.join(', ');


            const studNumInput = document.getElementById('studNum_input_deac');
            const studNumText = document.getElementById('studNum_text_deac');

            studNumInput.value = selectedStudNum.join(', ');
            studNumText.innerText = selectedStudNum.join(', ');

            console.log('Selected IDs:', selectedIds);


            modal_disable_acc.classList.remove('hidden');
            $("#side-bar").hide();

            function closeModal() {
                modal_disable_acc.classList.add('hidden');
                $("#side-bar").show();

            }

            span3.addEventListener('click', closeModal);
            cancelModalButton3.addEventListener('click', closeModal);

        });

        modalBatchDeactivate(selectedIds);


        function modalBatchDeactivate(selectedIds) {
            const modal_disable_acc = document.querySelector('.modal-disable-acc');
            const form = document.querySelector('#disable-form');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const urlWithIds = new URL(form.action);
                selectedIds.forEach(id => urlWithIds.searchParams.append('student_ids[]', id));

                const formData = new FormData(form);

                $.ajax({
                    url: urlWithIds.toString(),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            successCRUD(response.message);
                            modal_disable_acc.classList.add('hidden');
                            buttonDev.classList.add('hidden');
                            // studentAcc();
                            Accounts();
                            $("#side-bar").show();
                        } else {
                            errorModal(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // errorModal(response.message);
                    }
                });
            });
        }


        $("#batch-react-btn").click(function(e) {
            e.preventDefault();

            const modal_enable_acc = document.querySelector('.modal-enable-acc');
            const form = document.querySelector('#enable-form');
            const span4 = document.querySelector('#enable-close');
            const cancelModalButton4 = document.getElementById('enable-cancel');

            const idInput2 = document.getElementById('id_input_reac');
            const idText2 = document.getElementById('id_text_reac');
            idInput2.value = selectedIds.join(', ');
            idText2.innerText = selectedIds.join(', ');

            const nameInput2 = document.getElementById('name_input_reac');
            const nameText2 = document.getElementById('name_text_reac');

            nameInput2.value = selectedName.join(', ');
            nameText2.innerText = selectedName.join(', ');


            const studNumInput2 = document.getElementById('studNum_input_reac');
            const studNumText2 = document.getElementById('studNum_text_reac');

            studNumInput2.value = selectedStudNum.join(', ');
            studNumText2.innerText = selectedStudNum.join(', ');

            modal_enable_acc.classList.remove('hidden');
            $("#side-bar").hide();

            console.log('Selected IDs:', selectedIds2);

            function closeModal() {
                modal_enable_acc.classList.add('hidden');
            }

            span4.addEventListener('click', closeModal);
            cancelModalButton4.addEventListener('click', closeModal);


        });

        modalBatchReactivate(selectedIds2);


        function modalBatchReactivate(selectedIds2) {
            const modal_enable_acc = document.querySelector('.modal-enable-acc');
            const form = document.querySelector('#enable-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const urlWithIdsReact = new URL(form.action);
                selectedIds2.forEach(id => urlWithIdsReact.searchParams.append('student_ids2[]', id));

                const formData = new FormData(form);

                $.ajax({
                    url: urlWithIdsReact.toString(),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            successCRUD(response.message);
                            modal_enable_acc.classList.add('hidden');
                            buttonDev.classList.add('hidden');
                            // studentAcc();
                            Accounts();
                            $("#side-bar").show();
                        } else {
                            errorModal(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // errorModal(response.message);
                    }
                });
            });
        }

        $("#batch-delete-btn").click(function(e) {
            e.preventDefault();

            const modal_delete_acc = document.querySelector('.modal-delete-acc');
            const form = document.querySelector('#delete-form-stud-batch');
            const span3 = document.querySelector('#delete-close');
            const cancelModalButton3 = document.getElementById('delete-cancel');

            const idInput3 = document.getElementById('id_input_delete');
            const idText3 = document.getElementById('id_text_delete');
            idInput3.value = selectedIds.join(', ');
            idText3.innerText = selectedIds.join(', ');

            const nameInput3 = document.getElementById('name_input_delete');
            const nameText3 = document.getElementById('name_text_delete');

            nameInput3.value = selectedName.join(', ');
            nameText3.innerText = selectedName.join(', ');


            const studNumInput3 = document.getElementById('studNum_input_delete');
            const studNumText3 = document.getElementById('studNum_text_delete');

            studNumInput3.value = selectedStudNum.join(', ');
            studNumText3.innerText = selectedStudNum.join(', ');

            console.log('Selected IDs:', selectedIds3);


            modal_delete_acc.classList.remove('hidden');
            $("#side-bar").hide();

            function closeModal() {
                modal_delete_acc.classList.add('hidden');
                $("#side-bar").show();

            }

            span3.addEventListener('click', closeModal);
            cancelModalButton3.addEventListener('click', closeModal);

        });

        modalBatchDelete(selectedIds3);


        function modalBatchDelete(selectedIds3) {
            const modal_delete_acc = document.querySelector('.modal-delete-acc');
            const form = document.querySelector('#delete-form-stud-batch');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const urlWithIds = new URL(form.action);
                selectedIds3.forEach(id => urlWithIds.searchParams.append('student_ids3[]', id));

                const formData = new FormData(form);

                $.ajax({
                    url: urlWithIds.toString(),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            successCRUD(response.message);
                            modal_delete_acc.classList.add('hidden');
                            buttonDev.classList.add('hidden');
                            // studentAcc();
                            Accounts();
                            $("#side-bar").show();
                        } else {
                            errorModal(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // errorModal(response.message);
                    }
                });
            });
        }
    });
</script>
