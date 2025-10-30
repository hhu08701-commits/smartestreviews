@extends('layouts.app')

@section('title', 'About Us - Smart Reviews')
@section('description', 'Learn about Smart Reviews - your trusted source for expert product reviews and recommendations')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumbs -->
    <x-breadcrumbs :items="[
        ['title' => 'Home', 'url' => route('home')],
        ['title' => 'About Us']
    ]" />

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">About Smart Reviews</h1>
        
        <div class="prose prose-lg max-w-none">
            <p class="text-lg text-gray-600 mb-6">
                Welcome to Smart Reviews, your trusted source for expert product reviews and recommendations. 
                We're dedicated to helping you make informed purchasing decisions by providing honest, 
                detailed reviews of products across various categories.
            </p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Mission</h2>
            <p class="text-gray-700 mb-6">
                Our mission is to simplify your shopping experience by providing comprehensive, 
                unbiased reviews that help you find the best products for your needs and budget. 
                We believe that everyone deserves access to reliable product information.
            </p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">What We Do</h2>
            <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                <li>Test and review products across multiple categories</li>
                <li>Provide detailed comparisons and recommendations</li>
                <li>Share expert insights and buying guides</li>
                <li>Keep you updated with the latest product releases</li>
            </ul>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Review Process</h2>
            <p class="text-gray-700 mb-4">
                Every product we review goes through a rigorous testing process:
            </p>
            <ol class="list-decimal list-inside text-gray-700 mb-6 space-y-2">
                <li>Thorough research and analysis</li>
                <li>Hands-on testing and evaluation</li>
                <li>Comparison with similar products</li>
                <li>User feedback and real-world usage</li>
                <li>Final rating and recommendation</li>
            </ol>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Us</h2>
            <p class="text-gray-700 mb-4">
                Have questions or suggestions? We'd love to hear from you!
            </p>
            <p class="text-gray-700">
                Email us at: <a href="mailto:contact@smartestreviews.com" class="text-blue-600 hover:text-blue-700">contact@smartestreviews.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
