@vite('resources/js/admin-lists.js')

<div class="flex justify-center w-full">
    <div class="flex flex-col w-full">
        <div id="program-info-section" class=" rounded-lg">
            <div class="flex justify-between items-center my-2">
                <div class=" my-2">
                    <x-titleText>
                        Course List
                    </x-titleText>
                </div>
                <div class="text-xl text-red-900 dark:text-[#CCAA2C] flex gap-1 top-4">
                    <div class="relative group flex justify-center items-center">
                        <div class="flex justify-center items-center">
                            <i id="add-course-btn"
                                class="fa-solid fa-file-circle-plus cursor-pointer z-10 hover:bg-gray-200 dark:hover:bg-[#161616] p-[5px] rounded-md"></i>
                        </div>
                        <x-tooltips tooltipTitle="Add Course" />
                    </div>
                    <div class="relative group flex justify-center items-center">
                        <div class="flex justify-center items-center">
                            <i id="add-course-list-btn"
                                class="fa-solid fa-file-arrow-up cursor-pointer z-10 hover:bg-gray-200 dark:hover:bg-[#161616] p-[5px] rounded-md"></i>
                        </div>
                        <x-tooltips tooltipTitle="Import List" />
                    </div>
                </div>
            </div>
            <div
                class="bg-white border dark:bg-[#161616] border-gray-300 dark:border-[#404040] dark:text-white text-black p-3 rounded-md animate-fadeIn shadow-lg">
                <table id="crsTable" class="display text-center">
                    <thead>
                        <tr>
                            <th style="text-align: center">Course Code</th>
                            <th style="text-align: center">Course Title</th>
                            <th style="text-align: center">Program Code</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal title="Import Course List" modalId="add-course-list-modal" closeBtnId="close-btn-add-course-list">
    <div class="bg-white rounded-lg  transform transition-all  w-full max-w-screen-sm dark:bg-[#161616]">
        <div class="flex justify-center items-center gap-10 mt-5">
            <div class="flex flex-col">
                <form id="add-course-list-form">
                    @csrf
                    <div class="flex gap-3 justify-center  items-center mt-6">
                        <div>
                            <input type="file" name="file" id="file"
                                class="block w-full  file:rounded-l-full shadow-lg  border-r-2 border-zinc-300 rounded-full  file:text-sm file:bg-amber-400 file:text-white rounded-l-lg hover:file:bg-amber-500 file:py-1.5 file:px-3.5 cursor-pointer"
                                required>
                        </div>
                    </div>
                    <div class="my-5 items-center">
                        <label for="programCode" class="block font-bold">Program Code</label>
                        <select name="programID" id="programCode"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            required>
                            <option value="">Select Program</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->programID }}">{{ $program->programCode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-center items-center p-5">
                        <button type="submit"
                            class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                            <span>Import</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-modal>

<x-modal title="Add Course" modalId="add-course-modal" closeBtnId="close-btn-add-course">
    <div class="bg-white rounded-lg transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
        <div class="flex justify-center items-center mt-5 px-8">
            <div class="flex flex-col w-full">
                <form id="add-course-form">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="my-2  items-center">
                            <label for="courseCode" class="block font-bold">Course Code</label>
                            <input type="text" name="courseCode"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                placeholder="Enter course code" autocomplete="off" required />
                        </div>

                        <div class="my-2  items-center">
                            <label for="courseTitle" class="block font-bold">Course Title</label>
                            <input type="text" name="courseTitle"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                placeholder="Enter course title" autocomplete="off" required />
                        </div>
                        <div class="my-2 items-center">
                            <label for="programCode" class="block font-bold">Program Code</label>
                            <select name="programID" id="programCode"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                required>
                                <option value="">Select Program</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->programID }}">{{ $program->programCode }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="flex justify-center items-center p-5">
                        <button type="submit"
                            class="text-black rounded-lg px-5 py-2 shadow-lg border border-gray-300 dark:text-white hover:bg-gray-100 dark:hover:bg-[#1E1E1E]">
                            <span>Add Course</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-modal>

<x-modal title="Edit Course" modalId="edit-course-modal" closeBtnId="close-btn-edit-course">
    <div class="bg-white rounded-lg  transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
        <div class="flex justify-center items-center mt-5 px-8">
            <div class="flex flex-col w-full">
                <form id="edit-course-form">
                    @csrf

                    <input type="hidden" name="courseID" id="edit-courseID">

                    <div class="my-2  items-center">
                        <label for="courseTitle" class="block font-bold">Course Title</label>
                        <input type="text" name="courseTitle" id="edit-courseTitle"
                            class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                            autocomplete="off" required />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="my-2  items-center">
                            <label for="courseCode" class="block font-bold">Course Code</label>
                            <input type="text" name="courseCode" id="edit-courseCode"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                autocomplete="off" required />
                        </div>

                        <div class="my-2 items-center">
                            <label for="programCode" class="block font-bold">Program Code</label>
                            <select name="programID" id="edit-programCodeCourse"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                required>
                                <option value="">Select Program</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->programID }}"
                                        {{ $program->programID == old('programID') ? 'selected' : '' }}>
                                        {{ $program->programCode }}</option>
                                @endforeach
                            </select>
                        </div>
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

<x-loader modalLoaderId="loader-modal-import" titleLoader="Importing" />
