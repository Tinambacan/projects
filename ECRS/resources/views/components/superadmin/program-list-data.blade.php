@vite('resources/js/admin-lists.js')

<div class="flex justify-center w-full">
    <div class="flex flex-col w-full">
        <div id="program-info-section" class="shadow-xl p-2 rounded-lg">
            <div class="flex justify-end items-center mb-2">
                <div class="text-2xl text-red-900 dark:text-[#CCAA2C] flex gap-2 top-4">
                    <div class="flex justify-center items-center">
                        <i id="add-program-btn"
                            class="fa-solid fa-file-circle-plus cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1"></i>
                    </div>
                    <div class="flex justify-center items-center">
                        <i id="add-program-list-btn"
                            class="fa-solid fa-file-arrow-up cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1"></i>
                    </div>
                    <div class="flex justify-center items-center">
                        <i class="fa-solid fa-print  cursor-pointer z-10"></i>
                    </div>

                </div>
            </div>
            <div class="dark:bg-white p-2 rounded-md">
                <table id="myTable" class="display">
                    <thead>
                        <tr>
                            <th style="text-align: center">Program Code</th>
                            <th style="text-align: center">Program Title</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programs as $program)
                            <tr>
                                <td class="text-center">{{ $program->programCode }}</td>
                                <td>{{ $program->programTitle }}</td>
                                <td class="text-center">
                                    <i class="fa-solid fa-pen-to-square text-green-500 text-2xl hover:bg-gray-200 hover:rounded-md cursor-pointer p-1 edit-program-btn"
                                        data-programcode="{{ $program->programCode }}"
                                        data-programtitle="{{ $program->programTitle }}"
                                        data-programid="{{ $program->programID }}">
                                    </i>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal title="Import Program List" modalId="add-program-list-modal" closeBtnId="close-btn-add-program-list">
    <div class="bg-white rounded-lg shadow-xl transform transition-all  w-full max-w-screen-sm dark:bg-[#161616]">
        <div class="flex justify-center items-center gap-10 mt-5">
            <div class="flex flex-col">
                <form id="add-program-list-form">
                    @csrf
                    <div class="flex gap-3 justify-center  items-center mt-6">
                        <div>
                            <input type="file" name="file" id="file"
                                class="block w-full  file:rounded-l-full shadow-lg  border-r-2 border-zinc-300 rounded-full  file:text-sm file:bg-amber-400 file:text-white rounded-l-lg hover:file:bg-amber-500 file:py-1.5 file:px-3.5 cursor-pointer"
                                required>
                        </div>
                    </div>
                    <div class="flex justify-center items-center p-5">
                        <button type="submit"
                            class="text-black rounded-lg p-3 shadow-lg border border-gray-300 dark:text-white">
                            <span>Import</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-modal>

<x-modal title="Add Program" modalId="add-program-modal" closeBtnId="close-btn-add-program">
    <div class="bg-white rounded-lg shadow-xl transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
        <div class="flex justify-center items-center mt-5 px-8">
            <div class="flex flex-col w-full">
                <form id="add-program-form">
                    @csrf
                    <div class="my-2  items-center">
                        <label for="programCode" class="block font-bold">Program Code</label>
                        <input type="text" name="programCode"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            placeholder="Enter program code" autocomplete="off" required />
                    </div>

                    <div class="my-2  items-center">
                        <label for="programTitle" class="block font-bold">Program Title</label>
                        <input type="text" name="programTitle"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            placeholder="Enter program title" autocomplete="off" required />
                    </div>
                    <div class="flex justify-center items-center p-5">
                        <button type="submit"
                            class="text-black rounded-lg p-3 shadow-lg border border-gray-300 dark:text-white">
                            <span>Add Program</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-modal>

<x-modal title="Edit Program" modalId="edit-program-modal" closeBtnId="close-btn-edit-program">
    <div class="bg-white rounded-lg shadow-xl transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
        <div class="flex justify-center items-center mt-5 px-8">
            <div class="flex flex-col w-full">
                <form id="edit-program-form">
                    @csrf
                    <input type="hidden" name="programID" id="edit-programID">

                    <div class="my-2 items-center">
                        <label for="programCode" class="block font-bold">Program Code</label>
                        <input type="text" name="programCode" id="edit-programCode"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            placeholder="Enter program code" autocomplete="off" required />
                    </div>

                    <div class="my-2 items-center">
                        <label for="programTitle" class="block font-bold">Program Title</label>
                        <input type="text" name="programTitle" id="edit-programTitle"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            placeholder="Enter program title" autocomplete="off" required />
                    </div>
                    <div class="flex justify-center items-center p-5">
                        <button type="submit" class="text-black rounded-lg p-3 shadow-lg border border-gray-300 dark:text-white">
                            <span>Update Program</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-modal>

<x-loader modalLoaderId="loader-modal-import" titleLoader="Importing" />
