@props(['tbl_question_ans'])
<!DOCTYPE html>
<html lang="en">

<head>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @vite('resources/js/jquery.dataTables.min.js')
    @vite('resources/css/jquery.dataTables.min.css')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .table-container {
            height: 500px;
            /* Set the desired height for the scrollable area */
            overflow: auto;
        }
    </style>
</head>

<body>
    <div class="flex ml-5 mt-5 ">
        <i class="fas fa-circle-arrow-left cursor-pointer text-blue-700 hover:text-blue-600" style="font-size: 30px;"
            onclick="Subjects()"></i>
    </div>

    <div class="mt-3 animate-fade-in-up">
        <div class="flex justify-start">
            <div class=" mb-3 flex justify-between w-full items-center ml-12">

                @if ($tbl_question_ans->isNotEmpty() && $tbl_question_ans->first()->subject)
                    <h2 class="font-bold text-4xl text-indigo-800 text-shadow-[0_4px_5px_#808080]">
                        {{ $tbl_question_ans->first()->subject->subject_name }}
                    </h2>
                @elseif ($tbl_subject->isNotEmpty())
                    <h2 class="font-bold text-4xl text-indigo-800 text-shadow-[0_4px_5px_#808080]">
                        {{ $tbl_subject->first()->subject_name }}
                    </h2>
                @endif

                <div class=" flex flex-row gap-4">

                    <div id="selectTriggerFilter" class="flex flex-col my-auto">
                    </div>
                    <button type="button" id="create-ques"
                        class="group flex bg-white shadow-xl rounded-lg cursor-pointer text-gray-800 gap-2  hover:bg-orange-500 p-3  font-bold hover:text-white">
                        <i class="fa-solid fa-square-plus text-xl my-auto group-hover:text-white text-gray-900"></i>
                        <span class="my-auto"> Create new question</span>
                    </button>
                    <button type="button" id="question-btn"
                        class="group flex mr-14 bg-white shadow-xl rounded-lg cursor-pointer text-gray-800 gap-2  hover:bg-orange-500 p-3  font-bold hover:text-white">
                        <i class="fa-solid fa-file-csv text-xl my-auto group-hover:text-white text-gray-900"></i>
                        <span class="my-auto"> Batch Upload</span>
                    </button>
                </div>
            </div>

        </div>
        <div class="px-14">
            <table id="myTable"
                class="display overflow-y-scroll w-full text-sm text-gray-500 dark:text-gray-400 bg-white text-center justify-center animate-fade-in-up"
                style="">
                <thead class="text-xs uppercase text-gray-800" style="width:100%">
                    <tr>
                        <th scope="col" class="" style="text-align: center"> <input type="checkbox"
                                class="rounded-full" name="select_all" value="" id="question_select_all"></th>
                        <th scope="col" class="px-6 py-3 hidden" style="text-align: center">Questions: ID</th>
                        <th scope="col" class="px-6 py-3" style="text-align: center">Questions</th>
                        <th scope="col" class="px-6 py-3" style="text-align: center">Explanation</th>
                        <th scope="col" class="px-6 py-3" style="text-align: center">Level</th>
                        <th scope="col" class="px-6 py-3" style="text-align: center">Correct Answer</th>
                        <th scope="col" class="px-6 py-3 hidden" style="text-align: center">Incorrect Answer 1</th>
                        <th scope="col" class="px-6 py-3 hidden" style="text-align: center">Incorrect Answer 2</th>
                        <th scope="col" class="px-6 py-3 hidden" style="text-align: center">Incorrect Answer 3</th>
                        <th scope="col" class="px-6 py-3" style="text-align: center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @if ($tbl_question_ans->count() > 0)
                        @foreach ($tbl_question_ans as $tbl_question_answer)
                            <tr class="{{ $loop->odd ? 'bg-gray-200' : 'bg-white' }} hover:bg-gray-100">
                                <td class="text-sm">
                                    <input type="checkbox" class="prof_checkbox rounded-full text-center"
                                        value="{{ $tbl_question_answer->question_ID }}"
                                        data-id="{{ $tbl_question_answer->question_ID }}">
                                </td>
                                <td class="text-sm hidden">{{ $tbl_question_answer->question_ID }}</td>
                                <td class="text-sm">{{ $tbl_question_answer->question_desc }}</td>
                                <td class="text-sm">{{ $tbl_question_answer->question_exp }}</td>
                                <td class="text-sm">{{ $tbl_question_answer->level }}</td>
                                <td class="text-sm"
                                    data-correct-answer-id="{{ $correctAnswers[$tbl_question_answer->question_ID]->answer_ID }}">
                                    {{ $correctAnswers[$tbl_question_answer->question_ID]->choices_desc }}
                                </td>
                                @foreach ($incorrectAnswers[$tbl_question_answer->question_ID] as $answerID => $incorrectAnswer)
                                    <td class="text-sm hidden" data-answer-id="{{ $answerID }}">
                                        {{ $incorrectAnswer }}
                                    </td>
                                @endforeach

                                <td class="text-sm">
                                    <div class="flex justify-center gap-2">
                                        <button>
                                            <i id="edit-btn"
                                                class="fa-solid fa-pen-to-square text-sm text-gray-400 cursor-pointer hover:text-gray-600">
                                            </i>
                                        </button>
                                        <button class="delete-button">
                                            <i
                                                class="fa-solid fa-trash text-sm text-red-600 cursor-pointer hover:text-red-800"></i>
                                        </button>
                                        <button id="view-btn">
                                            <i class="fa-solid fa-eye text-sm text-green-600 hover:text-green-800"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Questions --}}
    <div id="modal-question" class="modal-question hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="p-2">
                    <div class="flex justify-end">
                        <i id="close-add-ques"
                            class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="flex flex-row items-center justify-center">
                    <img id="questionM" class="" src="{{ URL('images/QuesM.png') }}"
                        style="height: 3rem;
                width: 3rem;">
                    <h1 class=" text-2xl text-shadow-[0_4px_5px_#808080] text-white font-bold">Create new
                        question</h1>
                </div>
                <form id="add-question-form" method="POST">
                    @csrf
                    <input type="hidden" name="subject_ID" value="{{ session('subject_ID') }}">
                    <input type="hidden" name="question_ID" id="question_ID" value="">
                    <!-- To store the question_ID -->
                    <div class="my-2 flex flex-col gap-2 mx-5">
                        <label for="question" class="block font-bold text-indigo-900">Question:</label>
                        <textarea type="text" name="question" id="question"
                            class=" p-2 shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            autocomplete="off" required></textarea>
                        <span class="text-danger text-red-600">
                            @error('question')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="my-2 flex flex-col gap-2 mx-5">
                        <label for="explanation" class="block font-bold text-indigo-900">Explanation: Optional</label>
                        <textarea type="text" name="explanation" id="explanation"
                            class=" p-2 shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            autocomplete="off" required></textarea>
                        <span class="text-danger text-red-600">
                            @error('explanation')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="grid grid-cols-2 mx-5 gap-5 mb-5">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="flex flex-col">
                                <label for="answer{{ $i }}" class="block font-bold text-indigo-900">Choice
                                    {{ $i }}:</label>
                                <div class="gap-1 flex flex-row">
                                    <input type="text" name="answer{{ $i }}"
                                        id="answer{{ $i }}"
                                        class="shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        autocomplete="off" required />
                                    <span class="text-danger text-red-600">
                                        @error('answer' . $i)
                                            {{ $message }}
                                        @enderror
                                    </span>
                                    <input type="radio" name="choice" value="{{ $i }}"
                                        class="rounded-full my-auto" style="height: 1.5rem; width: 1.5rem">
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="flex justify-between items-center py-4 mx-6">
                        <div class="flex flex-col mb-5">
                            <label for="levels" class="block font-bold text-indigo-900">Diffuculty:</label>
                            <select name="levels" id="levels" class="text-center p-3 bg-white rounded-lg">
                                <option class="font-sans" value="Easy">Easy</option>
                                <option class="font-sans" value="Medium">Medium</option>
                                <option class="font-sans" value="Hard">Hard</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-4 my-auto">
                            <button type="submit"
                                class="font-bold text-indigo-900 ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm rounded-md bg-white hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Create
                            </button>
                            <button type="button" id="cancel-add-ques"
                                class="font-bold text-indigo-900 inline-flex items-center px-4 py-2 border border-transparent text-sm rounded-md bg-white hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>

    {{-- Batch Upload Q&A --}}
    <div class="modal-import hidden fixed z-10 inset-0 overflow-y-auto ">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div id="">
                    <form method="POST" action="/question-import" id="importing-question-form">
                        @csrf
                        <input type="hidden" name="subject_ID" value="{{ session('subject_ID') }}">
                        <div class="flex justify-end px-3 py-2">
                            <i id="close-import"
                                class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl  ">
                            </i>
                        </div>
                        <h1 class="text-white text-3xl text-center mb-5 font-bold text-shadow-[0_4px_5px_#808080]">
                            Import File
                        </h1>

                        <div class="mb-2 flex justify-center mx-5">
                            <input type="file" name="file" id="file"
                                class="block w-full bg-white border file:rounded-l-lg border-gray-300 file:text-sm file:bg-orange-500 file:text-white rounded-lg hover:file:bg-orange-700 file:py-2 file:px-3.5 cursor-pointer shadow-md"
                                required>
                        </div>

                        <div class="mt-2 p-4 flex justify-end gap-2">

                            <button type="button" id="cancel-import"
                                class="ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Import
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- View --}}
    <div id="modal-view" class="modal-question hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="p-2">
                    <div class="flex justify-end">
                        <i id="view-close-add-ques"
                            class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="flex flex-row items-center justify-center">
                    <img id="questionM" class="" src="{{ URL('images/QuesM.png') }}"
                        style="height: 3rem;
                width: 3rem;">
                    <h1 class=" text-2xl text-shadow-[0_4px_5px_#808080] text-white font-bold">View
                        Question</h1>
                </div>
                <form id="view-question-form" method="POST">
                    @csrf
                    <input type="hidden" id="view-question-ID">
                    <div class="my-2 flex flex-col gap-2 mx-5">
                        <label for="view-question" class="block font-bold text-indigo-900">Question:</label>
                        <textarea type="text" name="view-question" readonly id="view-question"
                            class="shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            autocomplete="off" required></textarea>
                        <span class="text-danger text-red-600">
                            @error('view-question')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="my-2 flex flex-col gap-2 mx-5">
                        <label for="view-exp-question" class="block font-bold text-indigo-900">Question:</label>
                        <textarea type="text" name="view-exp-question" readonly id="view-exp-question"
                            class="shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            autocomplete="off" required></textarea>
                        <span class="text-danger text-red-600">
                            @error('view-exp-question')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="grid grid-cols-2 mx-5 gap-5 mb-5">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="flex flex-col">
                                <label for="view-answer{{ $i }}"
                                    class="block font-bold text-indigo-900">Choice {{ $i }}:</label>
                                <div class="gap-1 flex flex-row">
                                    <input readonly type="text" name="view-answer{{ $i }}"
                                        id="view-answer{{ $i }}"
                                        class="shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        autocomplete="off" required />
                                    <span class="text-danger text-red-600">
                                        @error('answer' . $i)
                                            {{ $message }}
                                        @enderror
                                    </span>
                                    <input type="radio" name="choice" id="view-answer{{ $i }}-radio"
                                        value="{{ $i }}" class="rounded-full my-auto"
                                        style="height: 1.5rem; width: 1.5rem"
                                        @if ($i !== 1) disabled @endif>
                                </div>
                            </div>
                        @endfor

                    </div>
                    <div class="flex justify-between items-center py-4 mx-6">
                        <div>
                            <select disabled name="view-levels" id="view-levels"
                                class="text-center p-3 bg-white rounded-lg">
                                <option class="font-sans" value="Easy">Easy</option>
                                <option class="font-sans" value="Medium">Medium</option>
                                <option class="font-sans" value="Hard">Hard</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Update --}}
    <div id="modal-edit" class="modal-question hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="p-2">
                    <div class="flex justify-end">
                        <i id="edit-close-add-ques"
                            class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="flex flex-row items-center justify-center">
                    <img id="questionM" class="" src="{{ URL('images/QuesM.png') }}"
                        style="height: 3rem;
                width: 3rem;">
                    <h1 class=" text-2xl text-shadow-[0_4px_5px_#808080] text-white font-bold">Update
                        Question</h1>
                </div>
                <form id="edit-question-form" method="POST">
                    @csrf
                    <input type="hidden" name="question_ID" id="edit-question-ID">
                    <div class="my-2 flex flex-col gap-2 mx-5">
                        <label for="edit-question" class="block font-bold text-indigo-900">Question:</label>
                        <textarea type="text" name="question_desc" id="edit-question"
                            class="shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-1 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            autocomplete="off" required></textarea>
                        <span class="text-danger text-red-600">
                            @error('edit-question')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="my-2 flex flex-col gap-2 mx-5">
                        <label for="edit-exp-question" class="block font-bold text-indigo-900">Question:</label>
                        <textarea type="text" name="question_exp" id="edit-exp-question"
                            class="shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-1 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            autocomplete="off" required></textarea>
                        <span class="text-danger text-red-600">
                            @error('edit-exp-question')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>

                    <div class="grid grid-cols-2 mx-5 gap-5 mb-5">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="flex flex-col">
                                <label for="edit-answer{{ $i }}"
                                    class="block font-bold text-indigo-900">Choice {{ $i }}:</label>
                                <div class="gap-1 flex flex-row">
                                    <input type="text" name="edit-answer{{ $i }}"
                                        id="edit-answer{{ $i }}"
                                        class="shadow-md border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-1 w-full dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        autocomplete="off" required />
                                    <input type="hidden" name="edit-answer{{ $i }}-ID"
                                        id="edit-answer{{ $i }}-ID">
                                    <!-- Dynamic name for hidden field with -ID -->
                                    <span class="text-danger text-red-600">
                                        @error('answer' . $i)
                                            {{ $message }}
                                        @enderror
                                    </span>
                                    <input type="radio" name="choice" id="edit-answer{{ $i }}-radio"
                                        value="{{ $i }}" class="rounded-full my-auto"
                                        style="height: 1.5rem; width: 1.5rem">
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="flex justify-between items-center py-4 mx-6">
                        <div>
                            <select name="level" id="edit-levels" class="text-center p-3 bg-white rounded-lg">
                                <option class="font-sans" value="Easy">Easy</option>
                                <option class="font-sans" value="Medium">Medium</option>
                                <option class="font-sans" value="Hard">Hard</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-4">
                            <button type="submit"
                                class="font-bold text-indigo-900 ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm rounded-md bg-white hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Update
                            </button>
                            <button type="button" id="edit-cancel-add-ques"
                                class="font-bold text-indigo-900 inline-flex items-center px-4 py-2 border border-transparent text-sm rounded-md bg-white hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- delete --}}
    <div id="modal-delete" class="modal-question hidden fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>
            <div
                class="animate-fade-in-down inline-block align-bottom bg-orange-200 rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <div class="p-2">
                    <div class="flex justify-end">
                        <i id="delete-close-add-ques"
                            class=" fa-solid fa-x text-white hover:text-gray-900  rounded-lg cursor-pointer p-2 text-xl">
                        </i>
                    </div>
                </div>
                <div class="flex flex-row items-center justify-center">
                    <img id="questionM" class="" src="{{ URL('images/QuesM.png') }}"
                        style="height: 3rem;
                width: 3rem;">
                    <h1 class=" text-2xl text-shadow-[0_4px_5px_#808080] text-white font-bold">Delete
                        Question</h1>
                </div>
                <form id="delete-question-form" method="POST">
                    @csrf
                    <input type="hidden" name="question_ID" id="delete-question-ID">
                    <div class="my-2 flex flex-col gap-2 mx-5 text-center">
                        <span id="delete-question-text" class="text-danger text-red-600 font-bold">
                            Are you sure you want to delete this Question?
                        </span>
                    </div>

                    <div class="text-center gap-4 mb-5">
                        <button type="submit"
                            class="font-bold text-indigo-900 ml-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm rounded-md bg-white hover:text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Yes
                        </button>
                        <button type="button" id="delete-cancel-add-ques"
                            class="font-bold text-indigo-900 inline-flex items-center px-4 py-2 border border-transparent text-sm rounded-md bg-white hover:text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


<script>
    var correctAnswers = @json($correctAnswers);

    function successCRUD(message) {
        Swal.fire({
            icon: 'success',
            title: "<h5 style='color:black'>" + message + "</h5>",
            showConfirmButton: false,
            timer: 1500
        });
    }

    function errorModal(messages) {
        Swal.fire({
            iconHtml: '<img src="{{ URL('images/Error.png') }}">',
            title: "<h5 style='color:white'>" + messages + "</h5>",
            customClass: {
                confirmButton: 'btn-black-text btn-white-background',
                icon: 'border border-0'
            },
            confirmButtonText: 'Okay',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCancelButton: false,
            showCloseButton: false,
            focusConfirm: false,
        });

        $(".swal2-modal").css('background-color', '#F2A65F');
    }

    var table;
    $(document).ready(function() {
        table = $('#myTable').DataTable({
            "responsive": true,
            pagingType: 'simple',
            scrollCollapse: false,
            scrollY: 300,
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

            initComplete: function() {

                var column2 = this.api().column(4);

                var select2 = $(
                        '<select class="filter rounded-lg p-3 bg-white cursor-pointer shadow-md font-bold" ><option value="" >Level</option></select>'
                    )
                    .appendTo('#selectTriggerFilter')
                    .on('change', function() {
                        var val = $(this).val();
                        column2.search(val).draw();
                    });

                var level = [];

                var columnData = column2.data().toArray();
                var level = [];
                columnData.forEach(function(s) {
                    s = s.split(',');
                    s.forEach(function(d) {
                        if (!level.includes(
                                d
                            )) {
                            level.push(d);
                        }
                    });
                });

                level.sort();

                level.forEach(function(level) {
                    select2.append('<option value="' + level + '">' + level +
                        '</option>');
                });
            },
        });


    });




    function addQuestionAnswer() {
        const quesAnswer = document.querySelector('.modal-question');
        const form = document.querySelector('#add-question-form'); // Use the form id
        const btn = document.querySelector('#create-ques');
        const span = document.querySelector('#close-add-ques');
        const cancelModalButton = document.getElementById('cancel-add-ques');

        btn.addEventListener('click', function() {
            $("#side-bar").hide();
            quesAnswer.classList.remove('hidden');
        });

        function closeModal() {
            $("#side-bar").show();
            quesAnswer.classList.add('hidden');
        }

        span.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', playClickSound);
        btn.addEventListener("click", playClickSound);

        const myValue = sessionStorage.getItem('subjectId');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            $.ajax({
                type: 'POST',
                url: '/add-question-answer/' + myValue, // Replace with your actual route URL
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successCRUD(response.message);
                        closeModal();
                        setTimeout(function() {
                            ManageQuesAns();
                        }, 1500);
                        // DataTable.ajax.reload()
                    } else if (response.status === 'error') {
                        if (response.messages && Array.isArray(response.messages)) {
                            response.messages.forEach(function(message) {
                                errorModal(response.message);
                            });
                        } else {
                            errorModal(response.message);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    errorModal(response.message);
                }
            });
        });
    }
    addQuestionAnswer();


    function viewModal() {
        const quesAnswer = document.querySelector('#modal-view');
        const form = document.querySelector('#view-question-form');
        const viewButtons = document.querySelectorAll('#view-btn');
        const span = document.querySelector('#view-close-add-ques');
        const cancelModalButton = document.getElementById('#view-cancel-add-ques');

        function openQuestionModal(event) {
            const row = event.target.closest('tr');
            const questionID = row.querySelector('.text-sm:nth-child(2)').textContent;
            const question = row.querySelector('.text-sm:nth-child(3)').textContent;
            const explanation = row.querySelector('.text-sm:nth-child(4)').textContent;
            const level = row.querySelector('.text-sm:nth-child(5)').textContent;
            
            form.querySelector('#view-question-ID').value = questionID;
            form.querySelector('#view-question').value = question;
            form.querySelector('#view-exp-question').value = explanation;
            form.querySelector('#view-levels').value = level;

            for (let i = 1; i <= 4; i++) {
                const answerColumn = row.querySelector(
                    `.text-sm:nth-child(${i + 5})`); // Assuming answers start from the 5th column
                const choice = answerColumn.textContent.trim();
                const choiceInput = form.querySelector(`#view-answer${i}`);
                const choiceRadio = form.querySelector(`#view-answer${i}-radio`);

                if (choice !== '') {
                    choiceInput.value = choice;
                    if (choice === correctAnswers[questionID].choices_desc) {
                        choiceRadio.checked = true; // Activate the radio button for the correct answer
                    } else {
                        choiceRadio.checked = false; // Deactivate the radio button for incorrect answers
                    }
                } else {
                    choiceInput.value = '';
                    choiceRadio.checked = false;
                }
            }

            $("#side-bar").hide();
            quesAnswer.classList.remove('hidden');
        }
        viewButtons.forEach((button) => {
            button.addEventListener('click', openQuestionModal);
        });
        span.addEventListener('click', closeModal);

        function closeModal() {
            $("#side-bar").show();
            quesAnswer.classList.add('hidden');
        }
        viewButtons.forEach((button) => {
            button.addEventListener("click", playClickSound);
        });
    }
    viewModal();

    function editModal() {
        const quesAnswer = document.querySelector('#modal-edit');
        const form = document.querySelector('#edit-question-form');
        const editButtons = document.querySelectorAll('#edit-btn');
        const span = document.querySelector('#edit-close-add-ques');
        const cancelModalButton = document.getElementById('edit-cancel-add-ques');


        function openQuestionModal(event) {
            const row = event.target.closest('tr');
            const questionID = row.querySelector('.text-sm:nth-child(2)').textContent;
            const question = row.querySelector('.text-sm:nth-child(3)').textContent;
            const explanation = row.querySelector('.text-sm:nth-child(4)').textContent;
            const level = row.querySelector('.text-sm:nth-child(5)').textContent;

            form.querySelector('#edit-question-ID').value = questionID;
            form.querySelector('#edit-question').value = question;
            form.querySelector('#edit-exp-question').value = explanation;
            form.querySelector('#edit-levels').value = level;

            for (let i = 1; i <= 4; i++) {
                const answerColumn = row.querySelector(`.text-sm:nth-child(${i + 5})`);
                const choice = answerColumn.textContent.trim();
                const choiceInput = form.querySelector(`#edit-answer${i}`);
                const choiceRadio = form.querySelector(`#edit-answer${i}-radio`);

                if (choice !== '') {
                    choiceInput.value = choice;

                    if (choice === correctAnswers[questionID].choices_desc) {
                        choiceRadio.checked = true;
                        const correctAnswer = answerColumn.getAttribute('data-correct-answer-id');
                        form.querySelector(`#edit-answer${i}-ID`).value = correctAnswer; // Set the hidden field name
                    } else {
                        choiceRadio.checked = false;
                        const answerID = answerColumn.getAttribute('data-answer-id');
                        form.querySelector(`#edit-answer${i}-ID`).value = answerID; // Set the hidden field name
                    }
                } else {
                    choiceInput.value = '';
                    choiceRadio.checked = false;
                }
            }

            $("#side-bar").hide();
            quesAnswer.classList.remove('hidden');
        }

        editButtons.forEach((button) => {
            button.addEventListener('click', openQuestionModal);
        });

        editButtons.forEach((button) => {
            button.addEventListener("click", playClickSound);
        });
        span.addEventListener('click', closeModal);

        function closeModal() {
            $("#side-bar").show();
            quesAnswer.classList.add('hidden');
        }
        cancelModalButton.addEventListener('click', closeModal);

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            $.ajax({
                type: 'POST',
                url: '/edit-question-answer', // Replace with your actual route URL
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successCRUD(response.message);
                        closeModal();
                        setTimeout(function() {
                            ManageQuesAns();
                        }, 1500);
                    } else if (response.status === 'error') {
                        if (response.messages && Array.isArray(response.messages)) {
                            response.messages.forEach(function(message) {
                                errorModal(response.message);
                            });
                        } else {
                            errorModal(response.message);
                        }
                        form.reset();
                        // setTimeout(function() {
                        //     closeModal();
                        // }, 2000);
                        closeModal();
                        // studentAcc();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    errorModal(response.message);
                }
            });
        });
    }
    editModal();

    function deleteModal() {
        const quesAnswer = document.getElementById('modal-delete');
        const form = document.querySelector('#delete-question-form');
        const span = document.querySelector('#delete-close-add-ques');
        const cancelModalButton = document.getElementById('delete-cancel-add-ques');

        // Select all delete buttons
        const deleteButtons = document.querySelectorAll('.delete-button');

        // Add a click event listener to each delete button
        deleteButtons.forEach((button) => {
            button.addEventListener('click', function(event) {
                const row = event.target.closest('tr');
                const questionID = row.querySelector('.text-sm:nth-child(2)').textContent;

                console.log(questionID);

                form.querySelector('#delete-question-ID').value = questionID;


                $("#side-bar").hide();
                quesAnswer.classList.remove('hidden');
            });
        });

        function closeModal() {
            $("#side-bar").show();
            quesAnswer.classList.add('hidden');
        }

        span.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', playClickSound);

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            $.ajax({
                type: 'POST',
                url: '/delete-question-answer', // Replace with your actual route URL
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === 'success') {
                        form.reset();
                        successCRUD(response.message);
                        window.location.reload();
                        closeModal();
                    } else if (response.status === 'error') {
                        if (response.messages && Array.isArray(response.messages)) {
                            response.messages.forEach(function(message) {
                                errorModal(response.message);
                            });
                        } else {
                            errorModal(response.message);
                        }
                        form.reset();
                        // setTimeout(function() {
                        //     closeModal();
                        // }, 2000);
                        closeModal();
                        // studentAcc();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    errorModal(response.message);
                }
            });
        });
    }
    deleteModal();


    function endGame() {
        // Destroy the sessions
        sessionStorage.removeItem('difficulty');
        sessionStorage.removeItem('subjectId');

        // Redirect to the Subjects page
        Subjects();
        window.location.reload();
    }

    function questionImport() {
        const importQuestion = document.querySelector('.modal-import');
        const form = document.querySelector('#importing-question-form'); // Use the form id
        const btn = document.querySelector('#question-btn');
        const span = document.querySelector('#close-import');
        const cancelModalButton = document.getElementById('cancel-import');

        const myValue = sessionStorage.getItem('subjectId');

        btn.addEventListener('click', function() {
            $("#side-bar").hide();
            importQuestion.classList.remove('hidden');
        });

        function closeModal() {
            $("#side-bar").show();
            importQuestion.classList.add('hidden');
        }

        span.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', closeModal);
        cancelModalButton.addEventListener('click', playClickSound);
        btn.addEventListener("click", playClickSound);

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            $.ajax({
                    type: 'POST',
                    url: '/question-import/' + myValue,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            form.reset();
                            successCRUD(response.message);
                            closeModal();
                            setTimeout(function() {
                                ManageQuesAns();
                            }, 1500);
                        } else if (response.status === 'error') {
                            if (response.messages && Array.isArray(response.messages)) {
                                response.messages.forEach(function(message) {
                                    errorModal(message); // Fix: Pass 'message' instead of 'response.message'
                                });
                            } else {
                                errorModal(response.message);
                            }
                            form.reset();
                            closeModal();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        errorModal(error); // Fix: Pass 'error' instead of 'response.message'
                    }
                });

        });
    }
    questionImport();
</script>
