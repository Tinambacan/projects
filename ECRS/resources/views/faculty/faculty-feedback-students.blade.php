<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
    @vite('resources/js/feedbacks.js')
    <title>Student Feedback</title>
</head>

@section('content')

    <body>
        <div class="flex flex-col justify-center w-full pt-8">
            <div class="flex my-3 rounded-md justify-start items-start">
                <a href="{{ route('faculty.class-record') }}"
                    class="flex gap-2 text-white p-2 dark:hover:bg-[#161616] hover:bg-gray-200 rounded-md cursor-pointer">
                    <div class="text-red-900 dark:text-[#CCAA2C] flex gap-1 justify-center items-center">
                        <i class="fa-solid fa-circle-arrow-left text-2xl"></i>
                        {{-- <i class="fa-solid fa-rectangle-list text-2xl"></i> --}}
                    </div>
                    <span class="md:text-lg text-sm text-black dark:text-white">Back to class record list</span>
                </a>
            </div>
            <div class="dark:text-white w-full mx-auto px-0 sm:px-0 md:px-2 lg:px-12 xl:px-32 2xl:px-96 relative">
                <div class="flex flex-col gap-8 relative">
                    {{-- <div
                        class="w-full flex justify-center items-center md:text-4xl text-2xl my-2 text-red-900 dark:text-[#CCAA2C] font-bold">
                        Student's Feedback
                    </div> --}}
                    <div class="w-full flex justify-center items-center">
                        <x-titleText>
                            Student's Feedback
                        </x-titleText>
                    </div>
                    <div
                        class="bg-white border border-gray-300 rounded-lg shadow-lg mb-5 text-black md:px-10 px-2 pb-3 animate-fadeIn">
                        @php
                            $activeFeedbacks = $feedbacks->filter(function ($feedback) {
                                return is_null($feedback->deleted_at);
                            });
                        @endphp

                        @if ($activeFeedbacks->isEmpty())
                            <div class="flex justify-center items-center text-gray-500 h-[50vh]">
                                No student feedback available.
                            </div>
                        @else
                            <div class="flex gap-3 my-5">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="select-all">
                                    <div class="flex items-center">
                                        <span>Select All</span>
                                    </div>
                                </div>

                                <div class="relative group flex justify-center items-center">
                                    <div class="cursor-pointer hover:bg-gray-200 rounded-md p-1 flex items-center">
                                        <i class="fa-solid fa-trash text-2xl text-red-900 dark:text-[#CCAA2C]"></i>
                                    </div>
                                    <x-tooltips tooltipTitle="Delete" />
                                </div>

                                <div class="relative group flex justify-center items-center">
                                    <div class="cursor-pointer hover:bg-gray-200 rounded-md p-1 flex items-center">
                                        <i class="fa-solid fa-envelope text-2xl text-red-900 dark:text-[#CCAA2C]"></i>
                                    </div>
                                    <x-tooltips tooltipTitle="Mark as read" />
                                </div>

                            </div>

                            {{-- <div class="relative">
                                <div class="p-1 text-gray-800 flex gap-5 flex-col relative">
                                    @foreach ($activeFeedbacks as $feedback)
                                        <div class="flex gap-5 flex-col relative">
                                            <div
                                                class="flex justify-between shadow-md p-2 px-3 border-gray-200 border hover:bg-gray-200 rounded-md relative ">
                                                <div
                                                    class="flex gap-8 bg-blue-300 {{ is_null($feedback->read_at) ? 'font-bold' : '' }}">
                                                    <div class="flex items-center">
                                                        <input type="checkbox" class="feedback-checkbox"
                                                            data-feedback-id="{{ $feedback->feedbackID }}">
                                                    </div>
                                                    <div class="flex items-center bg-red-200 max-w-[13rem]">
                                                        <span class="text-ellipsis truncate">
                                                            {{ $feedback->student_name }}
                                                        </span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <div>
                                                            {{ $feedback->subject }}
                                                        </div>
                                                        <div>
                                                            {{ $feedback->body }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-center relative">
                                                    <div class="hover:bg-gray-300 cursor-pointer px-1 rounded-md flex items-center justify-center feed-btn"
                                                        data-feedback-id="{{ $feedback->feedbackID }}">
                                                        <i class="fa-solid fa-ellipsis text-2xl"></i>
                                                    </div>

                                                    <div class="feedback-modal-container hidden absolute bottom-10"
                                                        data-feedback-id="{{ $feedback->feedbackID }}">
                                                        <div
                                                            class="bg-stone-500 items-center rounded-md shadow-lg z-50 text-center flex flex-col justify-start">
                                                            <button
                                                                class="text-white hover:bg-stone-600 hover:rounded-t-md p-2 text-center w-full delete-btn text-xs">Delete</button>
                                                            <button
                                                                class="text-white hover:bg-stone-600 hover:rounded-b-md p-2 text-center flex justify-center text-xs w-24 read-btn">Mark
                                                                as read</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div> --}}

                            <div class="relative">
                                <div class="p-1 text-gray-800 flex gap-5 flex-col relative">
                                    @foreach ($activeFeedbacks as $feedback)
                                        <div class="flex gap-5 flex-col relative">
                                            <div
                                                class="flex justify-between shadow-md p-2 px-3 border-gray-200 border hover:bg-gray-200 rounded-md relative w-full">
                                                <div
                                                    class=" flex gap-5  {{ is_null($feedback->read_at) ? 'font-bold' : '' }} flex-wrap sm:flex-nowrap">
                                                    <div class="flex gap-2">
                                                        <div class="flex items-center p-2">
                                                            <input type="checkbox" class="feedback-checkbox"
                                                                data-feedback-id="{{ $feedback->feedbackID }}">
                                                        </div>
                                                        <div class="flex items-center  md:max-w-[12rem] w-full max-w-[10rem]">
                                                            <span
                                                                class="text-ellipsis truncate text-md">{{ $feedback->student_name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <div>
                                                            {{ $feedback->subject }}
                                                        </div>
                                                        <div>
                                                            {{ $feedback->body }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-center relative">
                                                    <div class="hover:bg-gray-300 cursor-pointer px-1 rounded-md flex items-center justify-center feed-btn"
                                                        data-feedback-id="{{ $feedback->feedbackID }}">
                                                        <i class="fa-solid fa-ellipsis text-2xl"></i>
                                                    </div>

                                                    <div class="feedback-modal-container hidden absolute bottom-10"
                                                        data-feedback-id="{{ $feedback->feedbackID }}">
                                                        <div
                                                            class="bg-stone-500 items-center rounded-md shadow-lg z-50 text-center flex flex-col justify-start">
                                                            <button
                                                                class="text-white hover:bg-stone-600 hover:rounded-t-md p-2 text-center w-full delete-btn text-xs">Delete</button>
                                                            <button
                                                                class="text-white hover:bg-stone-600 hover:rounded-b-md p-2 text-center flex justify-center text-xs w-24 read-btn">Mark
                                                                as read</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
    </body>

    </html>
@endsection
