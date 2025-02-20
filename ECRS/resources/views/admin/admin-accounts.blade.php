<!DOCTYPE html>
@extends('layout.AppLayout')

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin | Accounts</title>
</head>

@section('content')

    <body>
        <div class="flex flex-col gap-2 w-full transition-all duration-300  mt-12">
           
            <div class="animate-fadeIn">
                <x-admin.accounts-data />
                {{-- <div class="bg-red-200 p-2">
                    <form id="sendEmailForm" method="POST" action="{{ route('admin.send-account-email') }}" class="flex flex-col gap-4">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        </div>
                
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea id="message" name="message" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        </div>
                
                        <button type="submit" 
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Send
                        </button>
                    </form>
                </div> --}}
                
            </div>
        </div>
    </body>

    </html>
@endsection
