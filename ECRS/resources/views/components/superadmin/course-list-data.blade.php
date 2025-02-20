@vite('resources/js/admin-lists.js')

<div class="flex justify-center w-full">
    <div class="flex flex-col w-full">
        <div id="program-info-section" class="shadow-xl p-2 rounded-lg">
            <div class="flex justify-end items-center mb-2">
                <div class="text-xl text-red-900 dark:text-[#CCAA2C] flex gap-2 top-4">
                    <div class="flex justify-center items-center">
                        <i id="add-course-btn"
                            class="fa-solid fa-file-circle-plus cursor-pointer z-10 hover:bg-gray-200 hover:rounded-md p-1"></i>
                    </div>
                    <div class="flex justify-center items-center">
                        <i id="add-course-list-btn"
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
                            <th style="text-align: center">No.</th>
                            <th style="text-align: center">Course Code</th>
                            <th style="text-align: center">Course Title</th>
                            <th style="text-align: center">Program Code</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programs as $program)
                            @foreach ($program->courses as $course)
                                <tr>
                                    <td style="text-align: center">{{ $loop->iteration }}</td>
                                    <td style="text-align: center">{{ $course->courseCode }}</td>
                                    <td>{{ $course->courseTitle }}</td>
                                    <td class="text-center">{{ $program->programCode }}</td>
                                    <td class="text-center">
                                        <i class="fa-solid fa-pen-to-square text-green-500 text-2xl edit-button hover:bg-gray-200 hover:rounded-md cursor-pointer p-1 edit-course-btn"
                                            data-course-id="{{ $course->courseID }}"
                                            data-course-code="{{ $course->courseCode }}"
                                            data-course-title="{{ $course->courseTitle }}"
                                            data-program-code="{{ $course->program->programCode }}"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<x-modal title="Import Course List" modalId="add-course-list-modal" closeBtnId="close-btn-add-course-list">
    <div class="bg-white rounded-lg shadow-xl transform transition-all  w-full max-w-screen-sm dark:bg-[#161616]">
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

<x-modal title="Add Course" modalId="add-course-modal" closeBtnId="close-btn-add-course">
    <div class="bg-white rounded-lg shadow-xl transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
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
                            <select name="programCode" id="programCode"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                required>
                                <option value="">Select Program</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->programCode }}">{{ $program->programCode }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="flex justify-center items-center p-5">
                        <button type="submit"
                            class="text-black rounded-lg p-3 shadow-lg border border-gray-300 dark:text-white">
                            <span>Add Course</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-modal>

<x-modal title="Edit Course" modalId="edit-course-modal" closeBtnId="close-btn-edit-course">
    <div class="bg-white rounded-lg shadow-xl transform transition-all w-full max-w-screen-sm dark:bg-[#161616]">
        <div class="flex justify-center items-center mt-5 px-8">
            <div class="flex flex-col w-full">
                <form id="edit-course-form">
                    @csrf

                    <input type="hidden" name="courseID" id="edit-courseID">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="my-2  items-center">
                            <label for="courseCode" class="block font-bold">Course Code</label>
                            <input type="text" name="courseCode" id="edit-courseCode"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                autocomplete="off" required />
                        </div>

                        <div class="my-2  items-center">
                            <label for="courseTitle" class="block font-bold">Course Title</label>
                            <input type="text" name="courseTitle" id="edit-courseTitle"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                autocomplete="off" required />
                        </div>
                        <div class="my-2 items-center">
                            <label for="programCode" class="block font-bold">Program Code</label>
                            <select name="programCode" id="edit-programCodeCourse"
                                class="border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 w-full"
                                required>
                                <option value="">Select Program</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->programCode }}">{{ $program->programCode }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-center items-center p-5">
                        <button type="submit"
                            class="text-black rounded-lg p-3 shadow-lg border border-gray-300 dark:text-white">
                            <span>Update Course</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-modal>

<x-loader modalLoaderId="loader-modal-import" titleLoader="Importing" />
