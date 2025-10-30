@extends('layouts.app')

@section('title', 'How We Rank - Smartest Reviews')
@section('description', 'Learn about our rigorous product ranking methodology. We evaluate products based on ingredient safety, effectiveness, value, and return policies.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <article class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-8">How We Rank</h1>
                
                <div class="prose max-w-none">
                    <p class="text-lg text-gray-700 mb-8">
                        Our ranking methodology is based on rigorous evaluation criteria to ensure you get the most accurate and helpful product recommendations.
                    </p>

                    <div class="space-y-8">
                        <!-- Ingredient Safety -->
                        <div class="border-l-4 border-blue-500 pl-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Ingredient Safety</h2>
                            <p class="text-gray-700 mb-4">
                                We check every ingredient against safety databases to ensure proper dosage limits, ultimate safety, and absence of banned substances. Our team of experts reviews clinical studies and medical literature to verify ingredient safety profiles.
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>FDA-approved ingredients verification</li>
                                <li>Clinical trial safety data analysis</li>
                                <li>Dosage limit compliance checking</li>
                                <li>Contraindication identification</li>
                            </ul>
                        </div>

                        <!-- Projected Effectiveness -->
                        <div class="border-l-4 border-green-500 pl-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Projected Effectiveness</h2>
                            <p class="text-gray-700 mb-4">
                                We compare active ingredient dosages from clinical studies and medical databases with product labeling to measure effectiveness. Our analysis includes bioavailability studies and absorption rates.
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Clinical study dosage comparison</li>
                                <li>Bioavailability assessment</li>
                                <li>Absorption rate analysis</li>
                                <li>Efficacy timeline evaluation</li>
                            </ul>
                        </div>

                        <!-- Value Ranking -->
                        <div class="border-l-4 border-yellow-500 pl-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Value Ranking</h2>
                            <p class="text-gray-700 mb-4">
                                We list ingredients by amount and assess value by comparing active ingredients versus inactive fillers, then against the product's price. This ensures you get the best bang for your buck.
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Active ingredient concentration analysis</li>
                                <li>Filler vs. active ingredient ratio</li>
                                <li>Price per effective dose calculation</li>
                                <li>Competitive pricing comparison</li>
                            </ul>
                        </div>

                        <!-- Return Policy -->
                        <div class="border-l-4 border-purple-500 pl-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Return Policy</h2>
                            <p class="text-gray-700 mb-4">
                                A strong return policy demonstrates the brand's confidence in its product quality and customer satisfaction. We evaluate return policies based on time limits, conditions, and ease of process.
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Return time limit evaluation</li>
                                <li>Condition requirements assessment</li>
                                <li>Process ease analysis</li>
                                <li>Customer service quality review</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-12 bg-blue-50 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-blue-900 mb-4">Our Commitment to Transparency</h3>
                        <p class="text-blue-800">
                            We believe in complete transparency. All our reviews are based on objective criteria, and we clearly disclose any affiliate relationships. Our goal is to help you make informed decisions with confidence.
                        </p>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Search -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEARCH</h3>
                <form class="flex">
                    <input type="text" placeholder="Search products..." 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button type="submit" 
                            class="bg-pink-500 text-white px-4 py-2 rounded-r-md hover:bg-pink-600 transition-colors">
                        SEARCH
                    </button>
                </form>
            </div>

            <!-- Our Sources -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Our Sources</h3>
                <div class="space-y-4">
                    <!-- Mayo Clinic -->
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">M</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">MAYO CLINIC</h4>
                            <p class="text-sm text-gray-600">Medical expertise</p>
                        </div>
                    </div>

                    <!-- MedlinePlus -->
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">M+</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">MedlinePlus®</h4>
                            <p class="text-sm text-gray-600">Trusted Health Information for You</p>
                        </div>
                    </div>

                    <!-- Healthline -->
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg">H</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">healthline</h4>
                            <p class="text-sm text-gray-600">Health information</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
