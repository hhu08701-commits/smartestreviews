@extends('layouts.app')

@section('title', 'Contact Us - Smart Reviews')
@section('description', 'Get in touch with Smart Reviews team')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-breadcrumbs :items="[
        ['title' => 'Home', 'url' => route('home')],
        ['title' => 'Contact']
    ]" />

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Contact Us</h1>
        <p class="text-gray-600 mb-8">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Get in Touch</h2>
                <p class="text-gray-600 mb-4">Email: contact@smartestreviews.com</p>
                <p class="text-gray-600">We typically respond within 24 hours.</p>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Send us a Message</h2>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
