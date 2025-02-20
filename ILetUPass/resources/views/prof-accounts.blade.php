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
                    <th scope="col" class="" style="text-align: center"> <input type="checkbox"
                            class="rounded-full" name="select_all" value="" id="prof_select_all"></th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">First Name</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Middle Name</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Last Name</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Email</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Status</th>
                    <th scope="col" class="px-6 py-3" style="text-align: center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @if (count($tbl_prof) > 0)
                    @foreach ($tbl_prof as $index => $tbl_profs)
                        <tr class=" {{ $index % 2 === 0 ? 'bg-gray-200' : 'bg-white' }} hover:bg-gray-100 ">
                            <td class=" text-sm ">
                                <input type="checkbox" class="prof_checkbox rounded-full text-center"
                                    value="{{ $tbl_profs->login_ID }}" data-id="{{ $tbl_profs->login_ID }}"
                                    data-name="{{ $tbl_profs->first_name }}">
                            </td>
                            <td class=" text-sm">{{ $tbl_profs->first_name }}</td>
                            <td class=" text-sm">{{ $tbl_profs->middle_name }}</td>
                            <td class=" text-sm"> {{ $tbl_profs->last_name }}</td>
                            <td class=" text-sm">{{ $tbl_profs->email }}</td>
                            <td class=" text-sm">
                                @if ($tbl_profs->isActive === 1)
                                    <button
                                        class=" relative px-2 py-2  toggleBtn rounded-md bg-green-500 text-white text-sm"
                                        id="toggleBtn" disabled>Enabled</button>
                                @else
                                    <button
                                        class="relative px-2 py-2 toggleBtnActivate  rounded-md bg-red-500 text-white text-sm"
                                        id="toggleBtnActivate" disabled>Disabled</button>
                                @endif
                            </td>
                            <td class=" text-sm">
                                <div class="flex justify-center gap-2">
                                    <button>
                                        <i id="edit-prof-{{ $tbl_profs->login_ID }}"
                                            class="fa-solid fa-pen-to-square text-2xl text-gray-400 cursor-pointer hover:text-gray-600"
                                            data-prof-id="{{ $tbl_profs->login_ID }}"
                                            data-prof-firstname="{{ $tbl_profs->first_name }}"
                                            data-prof-middlename="{{ $tbl_profs->middle_name }}"
                                            data-prof-lastname="{{ $tbl_profs->last_name }}"
                                            data-prof-email="{{ $tbl_profs->email }}">
                                        </i>
                                    </button>
                                    <button>
                                        <i class="fa-solid fa-trash  text-2xl text-red-600 cursor-pointer hover:text-red-800"
                                            id="delete-prof-btn"
                                            onclick="modalDeleteProf('{{ $tbl_profs->login_ID }}','{{ $tbl_profs->first_name }}')">
                                        </i>
                                    </button>
                                </div>
                        </tr>
                    @endforeach
                @endif
            </tbody>

        </table>
    </div>

    {{-- Edit profent --}}
    <div class="modal-edit-prof hidden fixed inset-0 overflow-y-auto z-40">
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
                        <i id="close-edit-prof"
                            class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="mx-5 mb-5">
                    <form method="POST" action="" id="prof-info-update">
                        @csrf
                        <input type="hidden" name="prof_ID" id="prof_ID">

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
                            <button type="button" id="cancel-edit-prof"
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


    {{-- Delete Prof --}}
    <div class="modal-delete-prof hidden fixed inset-0 overflow-y-auto z-40">
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
                                Delete Professor
                            </h1>
                        </div>
                        <i id="close-delete-prof"
                            class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="text-white text-2xl text-center my-5 font-bold text-shadow-[0_4px_5px_#808080]">
                    Are you sure you want to delete this?
                </div>
                <div class="mx-5 mb-5">
                    <form method="POST" id="prof-delete" action="">
                        @csrf
                        <input type="hidden" name="prof_ID" id="prof_ID">

                        <div class="mb-2 flex justify-center mx-5 flex-col">

                            <div class="w-full mb-2 hidden">
                                <input type="hidden" name="prof_id_input" id="prof_id_input" value="">
                                <p><strong>Prof ID:</strong> <span id="prof_id_text"></span></p>
                            </div>

                            <div class="my-2 flex flex-row items-center ">
                                <label id="" for=""
                                    class="block  font-bold text-indigo-900">Professor
                                    Name:</label>
                                <input type="hidden" name="delete_fn_input" id="delete_fn_input" value="">
                                <span id="delete_fn_text" type="hidden" name="delete_fn_text"
                                    class=" border border-gray-300 text-gray-900  rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 lg:w-80 w-full bg-white"></span>
                            </div>
                        </div>
                        <div class="mt-2 py-2 flex justify-end gap-2">
                            <button type="button" id="cancel-delete-prof"
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

    function modalInfoProf() {
        const form = document.querySelector('#prof-info-update');
        const modal_edit_prof = document.querySelector('.modal-edit-prof');
        const span = document.querySelector('#close-edit-prof');
        const cancelModalButton = document.getElementById('cancel-edit-prof');

        function closeModal() {
            $("#side-bar").show();
            modal_edit_prof.classList.add('hidden');
        }

        span.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', closeModal);


        const editButtons = document.querySelectorAll('[id^="edit-prof-"]');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {

                const profFname = button.getAttribute('data-prof-firstname');
                const profMname = button.getAttribute('data-prof-middlename');
                const profLname = button.getAttribute('data-prof-lastname');
                const profEmail = button.getAttribute('data-prof-email');
                const prof_ID = button.getAttribute('data-prof-id');

                document.querySelector('#first_name').value = profFname;
                document.querySelector('#middle_name').value = profMname;
                document.querySelector('#last_name').value = profLname;
                document.querySelector('#email').value = profEmail;
                document.querySelector('#prof_ID').value = prof_ID;

                modal_edit_prof.classList.remove('hidden');
                $("#side-bar").hide();
            });
        });
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            $.ajax({
                type: 'POST',
                url: '/update-prof', // Replace with your actual route URL
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        successCRUD(response.message);
                        modal_edit_prof.classList.add('hidden');
                        profAcc();
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
    modalInfoProf();

    function modalDeleteProf(login_ID, first_name) {
        const form = document.querySelector('#prof-delete');
        const modal_delete_prof = document.querySelector('.modal-delete-prof');
        const span = document.querySelector('#close-delete-prof');
        const cancelModalButton = document.getElementById('cancel-delete-prof');

        const buttons2 = document.querySelectorAll('#delete-prof-btn');

        function closeModal() {
            $("#side-bar").show();
            modal_delete_prof.classList.add('hidden');
        }

        span.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', closeModal);

        document.getElementById('prof_id_input').value = login_ID;
        document.getElementById('prof_id_text').innerText = login_ID;

        document.getElementById('delete_fn_input').value = first_name;
        document.getElementById('delete_fn_text').innerText = first_name;

        buttons2.forEach(function(button) {
            button.addEventListener('click', function() {
                modal_delete_prof.classList.remove('hidden');
                $("#side-bar").hide();
            });
        });
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            const prof_id = document.getElementById('prof_id_input').value;
            $.ajax({
                type: 'POST',
                url: '/delete-prof/' + prof_id,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        successCRUD(response.message);
                        modal_delete_prof.classList.add('hidden');
                        profAcc();
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
    modalDeleteProf();



    $(function() {
        const selectedIds = [];
        const selectedName = [];

        const selectedIds2 = [];
        const selectedName2 = [];

        const selectedIds3 = [];
        const selectedName3 = [];

        const checkboxes = document.querySelectorAll('.prof_checkbox');
        const selectAllCheckbox = document.getElementById('prof_select_all');
        const buttonDev = document.getElementById('buttonDevsProf');

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function(event) {
                const profId = event.target.dataset.id;
                const profName = event.target.dataset.name;


                if (event.target.checked) {
                    selectedIds.push(profId);
                    selectedName.push(profName);

                    selectedIds2.push(profId);
                    selectedName2.push(profName);

                    selectedIds3.push(profId);
                    selectedName3.push(profName);


                } else {
                    const index = selectedIds.indexOf(profId);
                    const name = selectedName.indexOf(profName);

                    const index2 = selectedIds2.indexOf(profId);
                    const name2 = selectedName2.indexOf(profName);

                    const index3 = selectedIds3.indexOf(profId);
                    const name3 = selectedName3.indexOf(profName);


                    if (index !== -1) {
                        selectedIds.splice(index, 1);
                        selectedName.splice(index, 1);

                        selectedIds2.splice(index, 1);
                        selectedName2.splice(index, 1);


                        selectedIds3.splice(index, 1);
                        selectedName3.splice(index, 1);

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
                const profId = checkbox.dataset.id;
                const profName = checkbox.dataset.name;

                if (isChecked) {
                    if (filteredRows.includes(checkbox.closest('tr'))) {
                        selectedIds.push(profId);
                        selectedName.push(profName);

                        selectedIds2.push(profId);
                        selectedName2.push(profName);

                        selectedIds3.push(profId);
                        selectedName3.push(profName);


                        checkbox.checked = true;
                    }
                } else {
                    const index = selectedIds.indexOf(profId);
                    const name = selectedName.indexOf(profName);

                    const index2 = selectedIds2.indexOf(profId);
                    const name2 = selectedName2.indexOf(profName);

                    const index3 = selectedIds3.indexOf(profId);
                    const name3 = selectedName3.indexOf(profName);


                    if (index !== -1) {
                        selectedIds.splice(index, 1);
                        selectedName.splice(index, 1);

                        selectedIds2.splice(index, 1);
                        selectedName2.splice(index, 1);

                        selectedIds3.splice(index, 1);
                        selectedName3.splice(index, 1);
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



        $("#batch-deact-btn-prof").click(function(e) {
            e.preventDefault();

            const modal_disable_acc = document.querySelector('.modal-disable-acc-prof');
            const form = document.querySelector('#disable-form-prof');
            const span3 = document.querySelector('#disable-close-prof');
            const cancelModalButton3 = document.getElementById('disable-cancel-prof');

            const idInput = document.getElementById('id_input_deac_prof');
            const idText = document.getElementById('id_text_deac_prof');
            idInput.value = selectedIds.join(', ');
            idText.innerText = selectedIds.join(', ');

            const nameInput = document.getElementById('name_input_deac_prof');
            const nameText = document.getElementById('name_text_deac_prof');

            nameInput.value = selectedName.join(', ');
            nameText.innerText = selectedName.join(', ');


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
            const modal_disable_acc = document.querySelector('.modal-disable-acc-prof');
            const form = document.querySelector('#disable-form-prof');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const urlWithIds = new URL(form.action);
                selectedIds.forEach(id => urlWithIds.searchParams.append('prof_ids[]', id));

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
                            $("#side-bar").show();
                            Accounts();
                        
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


        $("#batch-react-btn-prof").click(function(e) {
            e.preventDefault();

            const modal_enable_acc = document.querySelector('.modal-enable-acc-prof');
            const form = document.querySelector('#enable-form-prof');
            const span4 = document.querySelector('#enable-close-prof');
            const cancelModalButton4 = document.getElementById('enable-cancel-prof');

            const idInput2 = document.getElementById('id_input_reac_prof');
            const idText2 = document.getElementById('id_text_reac_prof');
            idInput2.value = selectedIds.join(', ');
            idText2.innerText = selectedIds.join(', ');

            const nameInput2 = document.getElementById('name_input_reac_prof');
            const nameText2 = document.getElementById('name_text_reac_prof');

            nameInput2.value = selectedName.join(', ');
            nameText2.innerText = selectedName.join(', ');


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
            const modal_enable_acc = document.querySelector('.modal-enable-acc-prof');
            const form = document.querySelector('#enable-form-prof');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const urlWithIdsReact = new URL(form.action);
                selectedIds2.forEach(id => urlWithIdsReact.searchParams.append('prof_ids2[]', id));

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
                            $("#side-bar").show();
                            Accounts();
                            
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

        $("#batch-delete-btn-prof").click(function(e) {
            e.preventDefault();

            const modal_delete_acc = document.querySelector('.modal-delete-acc-prof');
            const form = document.querySelector('#delete-form-prof-batch');
            const span3 = document.querySelector('#delete-close-prof');
            const cancelModalButton3 = document.getElementById('delete-cancel-prof');

            const idInput3 = document.getElementById('id_input_delete_prof');
            const idText3 = document.getElementById('id_text_delete_prof');
            idInput3.value = selectedIds.join(', ');
            idText3.innerText = selectedIds.join(', ');

            const nameInput3 = document.getElementById('name_input_delete_prof');
            const nameText3 = document.getElementById('name_text_delete_prof');

            nameInput3.value = selectedName.join(', ');
            nameText3.innerText = selectedName.join(', ');

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
            const modal_delete_acc = document.querySelector('.modal-delete-acc-prof');
            const form = document.querySelector('#delete-form-prof-batch');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const urlWithIds = new URL(form.action);
                selectedIds3.forEach(id => urlWithIds.searchParams.append('prof_ids3[]', id));

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
                            $("#side-bar").show();
                            Accounts();
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
