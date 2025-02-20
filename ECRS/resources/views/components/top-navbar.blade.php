@php
    $roleNum = session('role');
    $loginID = session('loginID');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/fontawesome/css/all.min.css')
    @vite('resources/js/top-navbar.js')
    @vite('resources/js/app.js')
</head>

<body>
    <div class="bg-red-900 w-full md:px-8 px-3 fixed top-0 z-40 dark:bg-[#161616] transition-all duration-300">
        <div class="flex justify-between">
            <div class="flex gap-2">
                <img src="{{ URL('images/logo-bg.png') }}" alt="Logo" class="h-16 md:h-20">
                <div class="uppercase text-2xl md:text-2xl flex items-center justify-center text-white">
                    <span class="hidden sm:inline font-bold">E-class Record System</span>
                </div>
            </div>
            @if ($roleNum == 1)
                <div class="flex gap-28">
                    <div class="flex justify-center items-center text-white dark:text-[#CCAA2C] relative gap-4">
                        <div>
                            <div class="flex gap-3">
                                <div class="relative group flex justify-center items-center">
                                    <a href="{{ route('faculty.feedback') }}"
                                        class="flex justify-center items-center cursor-pointer hover:bg-red-800 dark:hover:bg-[#1E1E1E] p-2 rounded-md relative">
                                        <i class="fa-solid fa-comment-dots text-2xl"></i>
                                        @if ($unreadCountFeedback > 0)
                                            <span
                                                class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex justify-center items-center">
                                                {{ $unreadCountFeedback }}
                                            </span>
                                        @endif
                                    </a>
                                    <div
                                        class="absolute top-full mt-2 left-1/2 transform -translate-x-1/2 hidden group-hover:block">
                                        <div
                                            class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span
                                                class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Feedback</span>
                                            <div
                                                class="absolute top-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-b-8 border-transparent border-b-[#404040]">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative group flex justify-center items-center">
                                    <div id="notif-button"
                                        class="flex justify-center items-center cursor-pointer hover:bg-red-800 dark:hover:bg-[#1E1E1E] p-2 rounded-md relative">
                                        <i class="fa-solid fa-bell text-2xl"></i>
                                        {{-- @if ($unreadCount > 0)
                                            <span
                                                class="absolute top-0 right-0 bg-red-500 text-white  text-xs rounded-full h-5 w-5 flex justify-center items-center">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif --}}

                                        <div id="notif-badge"></div>
                                    </div>

                                    <div class="notification-area "></div>
                                    <div
                                        class="absolute top-full mt-2 left-1/2 transform -translate-x-1/2 hidden group-hover:block">
                                        <div
                                            class="flex justify-center items-center text-center transition-all duration-300 relative">
                                            <span
                                                class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Notifications</span>
                                            <div
                                                class="absolute top-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-b-8 border-transparent border-b-[#404040]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                {{-- <div id="notif-container"
                                    class="hidden shadow-lg z-40 absolute text-center rounded-lg mt-2">
                                    @if ($notifications->count() > 0)
                                        <div class=" border border-zinc-300 rounded-lg overflow-hidden bg-white ">
                                            <div class=" shadow-lg  rounded-lg  p-4 h-96 w-72 sm:w-96 overflow-y-auto">
                                                <h3 class="text-lg font-bold mb-2 text-red-900 text-left">
                                                    Notifications
                                                </h3>
                                                <div class="space-y-2">
                                                    @foreach ($notifications as $notification)
                                                        @if ($notification->type === 'grade_request')
                                                            <form action="/mark-as-read" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="classRecordIDRequest"
                                                                    value="{{ $notification->classRecordID }}">
                                                                <input type="hidden" name="notifID"
                                                                    value="{{ $notification->id }}">

                                                                <button type="submit"
                                                                    class="p-3 border {{ is_null($notification->read_at) ? 'border-red-900' : 'border-gray-200' }} rounded-md hover:bg-gray-200">
                                                                    <p class="text-gray-800">
                                                                        {{ $notification->message }}
                                                                        <small
                                                                            class="text-gray-500">{{ $notification->created_at }}</small>
                                                                    </p>
                                                                </button>
                                                            </form>
                                                        @elseif ($notification->type === 'notif_verified')
                                                            <form action="{{ route('notif.markasread-file') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="notifIDVerified"
                                                                    value="{{ $notification->id }}">
                                                                <button type="submit"
                                                                    class="p-3 border {{ is_null($notification->read_at) ? 'border-red-900' : 'border-gray-200' }} rounded-md hover:bg-gray-200">
                                                                    <p class="text-gray-800">
                                                                        {{ $notification->message }}
                                                                        <small
                                                                            class="text-gray-500">{{ $notification->created_at }}</small>
                                                                    </p>
                                                                </button>
                                                            </form>
                                                        @elseif ($notification->type === 'notice_faculty')
                                                            <form
                                                                action="{{ route('faculty.store-class-record-id-notice') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="notifIDNotice"
                                                                    value="{{ $notification->id }}">
                                                                <button type="submit"
                                                                    class="p-2 border flex gap-4 justify-center items-center  {{ is_null($notification->read_at) ? 'border-red-900' : 'border-gray-200' }} rounded-md hover:bg-gray-200">
                                                                    <div class="text-gray-800 flex flex-col">
                                                                        <div class="text-left">
                                                                            {!! $notification->message !!}
                                                                        </div>
                                                                        <div class="flex items-start justify-start">
                                                                            <small
                                                                                class="text-gray-500 py-1">{{ $notification->created_at }}</small>
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class=" border border-zinc-300 rounded-lg overflow-hidden bg-white">
                                            <div
                                                class=" shadow-lg  rounded-lg  p-4 h-96 w-72 sm:w-96 overflow-y-auto flex justify-center items-center">
                                                <p class="text-gray-600">No notifications</p>
                                            </div>
                                        </div>
                                    @endif
                                </div> --}}

                                <div class="notif-container hidden shadow-lg z-40 text-left absolute rounded-lg mt-2">
                                </div>


                            </div>
                        </div>
                        <div>
                            <x-user-profile :role="$role" :userinfo="$userinfo" :user="$user" />
                        </div>
                    </div>
                </div>
            @elseif ($roleNum == 2)
                <div class="flex gap-28">
                    <div class="flex justify-center items-center text-white dark:text-[#CCAA2C] relative gap-4">
                        <div>
                            <div class="relative group flex justify-center items-center">
                                <div id="notif-button"
                                    class="flex justify-center items-center cursor-pointer hover:bg-red-800 dark:hover:bg-[#1E1E1E] p-2 rounded-md relative">
                                    <i class="fa-solid fa-bell text-2xl "></i>
                                    {{-- @if ($unreadCount > 0)
                                        <span
                                            class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex justify-center items-center">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif --}}
                                    <div id="notif-badge"></div>
                                </div>
                                <div
                                    class="absolute top-full mt-2 left-1/2 transform -translate-x-1/2 hidden group-hover:block">
                                    <div
                                        class="flex justify-center items-center text-center transition-all duration-300 relative">
                                        <span
                                            class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Notifications</span>
                                        <div
                                            class="absolute top-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-b-8 border-transparent border-b-[#404040]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end ">
                                {{-- <div id="notif-container" class="hidden shadow-lg z-40 absolute text-center rounded-lg">
                                    @if ($notifications->count() > 0)
                                        <div class=" border border-zinc-300 rounded-lg overflow-hidden bg-white">
                                            <div class=" shadow-lg  rounded-lg  p-4 h-96 w-72 sm:w-96 overflow-y-auto">
                                                <h3 class="text-lg font-bold mb-2 text-red-900 text-left">
                                                    Notifications
                                                </h3>
                                                <div class="space-y-2">
                                                    @foreach ($notifications as $notification)
                                                        <form action="/store-file-id-notif" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="notifIDAdmin"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit"
                                                                class="p-2 border flex gap-4 justify-center items-center  {{ is_null($notification->read_at) ? 'border-red-900' : 'border-gray-200' }} rounded-md hover:bg-gray-200">
                                                                <div class="text-gray-800 flex flex-col">
                                                                    <div class="text-left">
                                                                        {!! $notification->message !!}
                                                                    </div>
                                                                    <div class="flex items-start justify-start">
                                                                        <small
                                                                            class="text-gray-500 py-1">{{ $notification->created_at }}</small>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </form>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class=" border border-zinc-300 rounded-lg overflow-hidden bg-white">
                                            <div
                                                class=" shadow-lg  rounded-lg  p-4 h-96 w-96 overflow-y-auto flex justify-center items-center">
                                                <p class="text-gray-600">No notifications</p>
                                            </div>
                                        </div>

                                    @endif
                                </div> --}}

                                <div class="notif-container hidden shadow-lg z-40 text-left absolute rounded-lg mt-2 ">
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-user-profile :role="$role" :userinfo="$userinfo" :user="$user" />
                        </div>
                    </div>
                </div>
            @elseif ($roleNum == 3)
                <div class="flex gap-28">
                    <div class="flex justify-center items-center text-white dark:text-[#CCAA2C] relative gap-4">
                        <div>
                            <div class="relative group flex justify-center items-center">
                                <div id="notif-button"
                                    class="flex justify-center items-center cursor-pointer hover:bg-red-800 dark:hover:bg-[#1E1E1E] p-2 rounded-md relative">
                                    <i class="fa-solid fa-bell text-2xl "></i>
                                    {{-- @if ($unreadCount > 0)
                                        <span id="unread-count"
                                            class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex justify-center items-center">
                                            {{ $unreadCount }}
                                        </span>
                                    @else
                                        <span id="unread-count" class="hidden"></span>
                                    @endif --}}
                                    <div id="notif-badge"></div>
                                </div>
                                <div
                                    class="absolute top-full mt-2 left-1/2 transform -translate-x-1/2 hidden group-hover:block">
                                    <div
                                        class="flex justify-center items-center text-center transition-all duration-300 relative">
                                        <span
                                            class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Notifications</span>
                                        <div
                                            class="absolute top-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-b-8 border-transparent border-b-[#404040]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                {{-- <div id="notif-container" class="hidden shadow-lg z-40 absolute text-center rounded-lg">
                                    @if ($notifications->count() > 0)
                                        <div class="border border-zinc-300 rounded-lg overflow-hidden bg-white">
                                            <div class="shadow-lg rounded-lg p-4 h-96 w-72 sm:w-96 overflow-y-auto">
                                                <h3 class="text-lg font-bold mb-2 text-red-900 text-left">
                                                    Notifications
                                                </h3>
                                                <div class="space-y-2">
                                                    @foreach ($notifications as $notification)
                                                        <form action="/store-stud-class-record-id-notif" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="classRecordIDStudentNotif"
                                                                value="{{ $notification->classRecordID }}">
                                                            <input type="hidden" name="notifIDStudent"
                                                                value="{{ $notification->id }}">

                                                            <input type="hidden" name="assessmentID"
                                                                value="{{ $notification->selectedAssessIDs }}">

                                                            <button type="submit"
                                                                class="p-2 border flex gap-4 justify-center items-center  {{ is_null($notification->read_at) ? 'border-red-900' : 'border-gray-200' }} rounded-md hover:bg-gray-200">
                                                                <div class="text-gray-800 flex flex-col">
                                                                    <div class="text-left">
                                                                        {!! $notification->message !!}
                                                                    </div>
                                                                    <div class="flex items-start justify-start">
                                                                        <small
                                                                            class="text-gray-500 py-1">{{ $notification->created_at }}</small>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </form>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="border border-zinc-300 rounded-lg overflow-hidden bg-white">
                                            <div
                                                class="shadow-lg rounded-lg p-4 h-96 w-72 sm:w-96 overflow-y-auto flex justify-center items-center">
                                                <p class="text-gray-600">No notifications</p>
                                            </div>
                                        </div>
                                    @endif
                                </div> --}}

                                <div class="notif-container hidden shadow-lg z-40 text-left absolute rounded-lg mt-2">
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-user-profile :role="$role" :userinfo="$userinfo" :user="$user" />
                        </div>
                        
                    </div>
                </div>
            @elseif ($roleNum == 4)
                <div class="flex gap-28">
                    <div class="flex justify-center items-center text-white dark:text-[#CCAA2C] relative gap-4">
                        <div>
                            <div class="relative group flex justify-center items-center">
                                {{-- <div id="notif-button"
                                    class="flex justify-center items-center cursor-pointer hover:bg-red-800 dark:hover:bg-[#1E1E1E] p-2 rounded-md relative">
                                    <i class="fa-solid fa-bell text-2xl "></i> --}}
                                    {{-- @if ($unreadCount > 0)
                                        <span id="unread-count"
                                            class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex justify-center items-center">
                                            {{ $unreadCount }}
                                        </span>
                                    @else
                                        <span id="unread-count" class="hidden"></span>
                                    @endif --}}
                                    {{-- <div id="notif-badge"></div>
                                </div> --}}
                                <div
                                    class="absolute top-full mt-2 left-1/2 transform -translate-x-1/2 hidden group-hover:block">
                                    <div
                                        class="flex justify-center items-center text-center transition-all duration-300 relative">
                                        <span
                                            class="p-2 text-sm text-white bg-[#404040] shadow-lg rounded-md">Notifications</span>
                                        <div
                                            class="absolute top-[-8px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-8 border-r-8 border-b-8 border-transparent border-b-[#404040]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <div id="notif-container" class="hidden shadow-lg z-40 absolute text-center rounded-lg">
                                    @if ($notifications->count() > 0)
                                        <div class="border border-zinc-300 rounded-lg overflow-hidden bg-white">
                                            <div class="shadow-lg rounded-lg p-4 h-96 w-96 overflow-y-auto">
                                                <h3 class="text-lg font-bold mb-2 text-red-900 text-left">
                                                    Notifications
                                                </h3>
                                                <div class="space-y-2">
                                                    @foreach ($notifications as $notification)
                                                        <form action="/store-stud-class-record-id-notif" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="classRecordIDStudentNotif"
                                                                value="{{ $notification->classRecordID }}">
                                                            <input type="hidden" name="notifIDStudent"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit"
                                                                class="p-2 border flex gap-4 justify-center items-center  {{ is_null($notification->read_at) ? 'border-red-900' : 'border-gray-200' }} rounded-md hover:bg-gray-200">
                                                                <div class="text-gray-800 flex flex-col">
                                                                    <div class="text-left">
                                                                        {!! $notification->message !!}
                                                                    </div>
                                                                    <div class="flex items-start justify-start">
                                                                        <small
                                                                            class="text-gray-500 py-1">{{ $notification->created_at }}</small>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </form>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="border border-zinc-300 rounded-lg overflow-hidden bg-white">
                                            <div
                                                class="shadow-lg rounded-lg p-4 h-96 w-96 overflow-y-auto flex justify-center items-center">
                                                <p class="text-gray-600">No notifications</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-user-profile :role="$role" :userinfo="$userinfo" :user="$user" />
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        function navigateToClassRecord() {
            window.location.href = "{{ route('faculty.class-record') }}";
        }

        function navigateToCourseList() {
            window.location.href = "{{ route('faculty.course-list') }}";
        }

        function navigateToProgramList() {
            window.location.href = "{{ route('faculty.program-list') }}";
        }

        function navigateToSubmitted() {
            window.location.href = "{{ route('faculty.submitted-report') }}";
        }
    </script>
</body>

</html>
