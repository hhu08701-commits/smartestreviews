@extends('layouts.app')

@section('title', 'Terms of Service - Smart Reviews')
@section('description', 'Terms of Service for Smart Reviews')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-breadcrumbs :items="[
        ['title' => 'Home', 'url' => route('home')],
        ['title' => 'Terms of Service']
    ]" />

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Terms of Service</h1>
        
        <div class="prose prose-lg max-w-none">
            <p class="text-gray-600 mb-6">Last updated: {{ date('F j, Y') }}</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Acceptance of Terms</h2>
            <p class="text-gray-700 mb-6">
                By accessing and using Smart Reviews, you accept and agree to be bound by the terms 
                and provision of this agreement.
            </p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Use License</h2>
            <p class="text-gray-700 mb-4">Permission is granted to temporarily download one copy of the materials on Smart Reviews for personal, non-commercial transitory viewing only.</p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Disclaimer</h2>
            <p class="text-gray-700 mb-6">
                The materials on Smart Reviews are provided on an 'as is' basis. Smart Reviews makes no warranties, 
                expressed or implied, and hereby disclaims and negates all other warranties.
            </p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Limitations</h2>
            <p class="text-gray-700 mb-6">
                In no event shall Smart Reviews or its suppliers be liable for any damages arising out of the use 
                or inability to use the materials on Smart Reviews.
            </p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Information</h2>
            <p class="text-gray-700">
                If you have any questions about these Terms of Service, please contact us at 
                <a href="mailto:legal@smartestreviews.com" class="text-blue-600 hover:text-blue-700">legal@smartestreviews.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
