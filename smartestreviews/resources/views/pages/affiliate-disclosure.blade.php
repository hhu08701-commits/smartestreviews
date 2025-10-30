@extends('layouts.app')

@section('title', 'Affiliate Disclosure - Smart Reviews')
@section('description', 'Affiliate disclosure and transparency information for Smart Reviews')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-breadcrumbs :items="[
        ['title' => 'Home', 'url' => route('home')],
        ['title' => 'Affiliate Disclosure']
    ]" />

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Affiliate Disclosure</h1>
        
        <div class="prose prose-lg max-w-none">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Important Notice</h3>
                        <p class="text-yellow-700">
                            Smart Reviews participates in various affiliate marketing programs, which means we may earn commissions 
                            from qualifying purchases made through our links at no additional cost to you.
                        </p>
                    </div>
                </div>
            </div>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">What This Means</h2>
            <p class="text-gray-700 mb-6">
                When you click on links to products on our site and make a purchase, we may receive a commission. 
                This does not affect the price you pay for the product, and it helps us continue to provide 
                free, high-quality content and reviews.
            </p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Our Commitment to Honesty</h2>
            <p class="text-gray-700 mb-4">We are committed to providing honest, unbiased reviews. Our affiliate relationships do not influence our editorial content:</p>
            <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                <li>We only recommend products we genuinely believe in</li>
                <li>Our reviews are based on thorough testing and research</li>
                <li>We clearly mark affiliate links and sponsored content</li>
                <li>We maintain editorial independence in all our recommendations</li>
            </ul>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Affiliate Programs</h2>
            <p class="text-gray-700 mb-4">We participate in affiliate programs with trusted retailers including:</p>
            <ul class="list-disc list-inside text-gray-700 mb-6 space-y-2">
                <li>Amazon Associates Program</li>
                <li>Other reputable retailers and manufacturers</li>
            </ul>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Your Support</h2>
            <p class="text-gray-700 mb-6">
                When you purchase through our affiliate links, you're supporting our ability to continue providing 
                free, valuable content. We appreciate your support and trust in our recommendations.
            </p>
            
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Questions?</h2>
            <p class="text-gray-700">
                If you have any questions about our affiliate relationships or this disclosure, please contact us at 
                <a href="mailto:affiliate@smartestreviews.com" class="text-blue-600 hover:text-blue-700">affiliate@smartestreviews.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
