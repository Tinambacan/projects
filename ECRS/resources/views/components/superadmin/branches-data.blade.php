<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/js/superadmin-branches.js')
    @vite('resources/css/dataTable.css')
</head>

<body>

    <div class="flex justify-center w-full">
        <div class="flex flex-col w-full">
            <div class="flex justify-between items-center">
                <x-titleText>
                    Branches
                </x-titleText>
                <div class="text-xl text-red-900 dark:text-[#CCAA2C] flex gap-1 top-4">
                    <div class="relative group flex justify-center items-center">
                        <div class="flex justify-center items-center">
                            <div id="add-branch-btn"
                                class="cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1 flex gap-1">
                                <i class="fa-solid fa-building"></i>
                                <div class="flex justify-center items-center">
                                    <i class="fa-solid fa-plus text-sm "></i>
                                </div>
                            </div>
                        </div>
                        <x-tooltips tooltipTitle="Add Branch" />
                    </div>

                    {{-- <div class="relative group flex justify-center items-center">
                            <div class="flex justify-center items-center ">
                                <i id="add-branch-list-btn"
                                    class="fa-solid fa-file-circle-plus cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1 dark:text-[#5B82BD]">
                                </i>
                            </div>
                            <div
                                class="absolute top-[-48px] left-1/2 transform hidden group-hover:block -translate-x-1/2">
                                <div
                                    class="flex justify-center items-center text-center transition-all duration-300 relative">
                                    <span class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Import</span>
                                    <div
                                        class="absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-t-8 border-transparent border-t-[#404040]">
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                </div>
            </div>
            <div
                class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg my-3">
                <table id="myTable" class=" w-full display  text-center items-center" style="text-align: center">
                    <thead>
                        <tr>
                            <th style="text-align: center">Branch ID</th>
                            <th style="text-align: center">Branch Description</th>
                            <th style="text-align: center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal title="Add Branch" modalId="add-branch-modal" closeBtnId="close-btn-add-branch">
        <div class="bg-white rounded-lg  transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex justify-center items-center mt-5 px-8">
                <div class="flex flex-col w-full">
                    <form id="add-branch-form">
                        @csrf
                        <div class="my-2  items-center">
                            <label for="branchDescription" class="block font-bold">Branch</label>
                            <input type="text" name="branchDescription"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                placeholder="Enter branch" autocomplete="off" required />
                        </div>
                        <div class="flex justify-center items-center p-5">
                            <button type="submit"
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                <span>Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-modal>

    <x-modal title="Edit Branch" modalId="edit-branch-modal" closeBtnId="close-btn-edit-branch">
        <div class="bg-white rounded-lg  transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
            <div class="flex justify-center items-center mt-5 px-8">
                <div class="flex flex-col w-full">
                    <form id="edit-branch-form">
                        @csrf
                        <input type="hidden" name="branchID" id="edit-branchID">

                        <div class="my-2 items-center">
                            <label for="branchDescription" class="block font-bold">Branch Description</label>
                            <input type="text" name="branchDescription" id="edit-branchDescription"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                placeholder="Enter branch description" autocomplete="off" required />
                        </div>
                        <div class="flex justify-center items-center p-5">
                            <button type="submit"
                                class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                                <span>Save</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </x-modal>

</body>

</html>
