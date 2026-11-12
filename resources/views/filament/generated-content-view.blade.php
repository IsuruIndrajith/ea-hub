@php
    use Illuminate\Support\Str;
    $c = $record->content ?? [];
    $type = $record->type;
@endphp

@if($type === \App\Models\GeneratedContent::TYPE_PRODUCT_PAGE)

    {{-- ── Header ─────────────────────────────────────────────── --}}
    <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
            {{ $c['name'] ?? '' }}
        </h1>
        @if(!empty($c['tool_name']))
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tool: {{ $c['tool_name'] }}</p>
        @endif
    </div>

    {{-- ── Short description ───────────────────────────────────── --}}
    @if(!empty($c['short_description']))
        <div class="mb-6 p-4 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800">
            <p class="text-gray-800 dark:text-gray-200 leading-relaxed">{{ $c['short_description'] }}</p>
        </div>
    @endif

    {{-- ── Main description (markdown + inline images) ────────── --}}
    @if(!empty($c['description']))
        <div class="prose prose-indigo dark:prose-invert max-w-none mb-8 prose-img:rounded-lg prose-img:shadow-md prose-table:text-sm">
            {!! Str::markdown($c['description'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
        </div>
    @endif

    {{-- ── FAQ ───────────────────────────────────────────────── --}}
    @if(!empty($c['faq']) && is_array($c['faq']))
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                Frequently Asked Questions
            </h2>
            <div class="space-y-4">
                @foreach($c['faq'] as $item)
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                        <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $item['question'] ?? '' }}</p>
                        <p class="mt-1 text-gray-600 dark:text-gray-400 text-sm leading-relaxed">{{ $item['answer'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Telegram message ───────────────────────────────────── --}}
    @if(!empty($c['tg_message']))
        <div class="mb-6 rounded-lg border border-sky-200 dark:border-sky-800 bg-sky-50 dark:bg-sky-950/30 p-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-sky-600 dark:text-sky-400 mb-2">Telegram Message</p>
            @if(is_array($c['tg_message']))
                <p class="text-gray-700 dark:text-gray-300 mb-3 leading-relaxed">{{ $c['tg_message']['description'] ?? '' }}</p>
                @if(!empty($c['tg_message']['key_features']))
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">⚙️ Key Features:</p>
                    <ul class="space-y-0.5">
                        @foreach($c['tg_message']['key_features'] as $f)
                            <li class="text-sm text-gray-700 dark:text-gray-300">✅ {{ $f }}</li>
                        @endforeach
                    </ul>
                @endif
            @else
                <pre class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-sans">{{ $c['tg_message'] }}</pre>
            @endif
        </div>
    @endif

    {{-- ── SEO metadata ────────────────────────────────────────── --}}
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-4">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-3">SEO Metadata</p>
        <dl class="grid grid-cols-1 gap-y-2 text-sm sm:grid-cols-[auto,1fr] sm:gap-x-4">
            @foreach([
                'rank_math_title'           => 'Title',
                'rank_math_description'     => 'Meta description',
                'rank_math_focus_keyword'   => 'Focus keywords',
                'myfxbook'                  => 'Myfxbook',
                'myfxbook_link'             => 'Myfxbook link',
                'myfxbook_gain'             => 'Myfxbook gain',
            ] as $key => $label)
                @if(!empty($c[$key]))
                    <dt class="font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $label }}</dt>
                    <dd class="text-gray-800 dark:text-gray-200 break-all">{{ $c[$key] }}</dd>
                @endif
            @endforeach
        </dl>
    </div>

@elseif($type === \App\Models\GeneratedContent::TYPE_SET_REVIEW)

    @php $seo = $c['seo'] ?? []; $ct = $c['content'] ?? $c; @endphp

    {{-- ── SEO metadata ────────────────────────────────────────── --}}
    @if(!empty($seo))
        <div class="mb-6 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-3">SEO</p>
            <dl class="grid grid-cols-1 gap-y-2 text-sm sm:grid-cols-[auto,1fr] sm:gap-x-4">
                @foreach([
                    'recommended_meta_title' => 'Title',
                    'meta_description'       => 'Meta description',
                    'focus_keyword'          => 'Focus keyword',
                    'recommended_slug'       => 'Slug',
                    'suggested_h1'           => 'H1',
                ] as $key => $label)
                    @if(!empty($seo[$key]))
                        <dt class="font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $label }}</dt>
                        <dd class="text-gray-800 dark:text-gray-200">{{ $seo[$key] }}</dd>
                    @endif
                @endforeach
            </dl>
            @if(!empty($seo['secondary_keywords']))
                <div class="mt-3 flex flex-wrap gap-1">
                    @foreach($seo['secondary_keywords'] as $kw)
                        <span class="inline-block rounded px-2 py-0.5 text-xs bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300">{{ $kw }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- ── Content sections (all markdown) ────────────────────── --}}
    <div class="prose prose-indigo dark:prose-invert max-w-none prose-img:rounded-lg prose-img:shadow-md prose-table:text-sm">

        @php
            $stringSections = [
                'seo_introduction'           => 'Introduction',
                'specs_table'                => 'Specifications',
                'set_comparison_table'       => 'SET File Comparison',
                'requirements_table'         => 'Requirements',
                'customization_guidance'     => 'Customization Guidance',
                'customization_cautions'     => 'Customization Cautions',
                'config_strengths_limitations' => 'Strengths & Limitations',
                'who_should_use'             => 'Who Should Use',
                'final_verdict'              => 'Final Verdict',
                'product_page_cta'           => 'Product Page',
                'review_methodology'         => 'Review Methodology',
                'risk_disclaimer'            => 'Risk Disclaimer',
            ];
        @endphp

        {{-- SET profiles --}}
        @if(!empty($ct['set_profiles']) && is_array($ct['set_profiles']))
            <h2>SET Profiles</h2>
            @foreach($ct['set_profiles'] as $profile)
                <h3>{{ $profile['file_name'] ?? '' }}</h3>
                @if(!empty($profile['body']))
                    {!! Str::markdown($profile['body'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                @endif
            @endforeach
            <hr>
        @endif

        {{-- Detailed parameter analysis --}}
        @if(!empty($ct['detailed_parameter_analysis']) && is_array($ct['detailed_parameter_analysis']))
            <h2>Parameter Analysis</h2>
            @foreach($ct['detailed_parameter_analysis'] as $group)
                <h3>{{ $group['group'] ?? '' }}</h3>
                @if(!empty($group['table']))
                    {!! Str::markdown($group['table'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                @endif
            @endforeach
            <hr>
        @endif

        {{-- Load SET steps (mt4 / mt5) --}}
        @if(!empty($ct['load_set_steps']))
            <h2>Loading SET Files</h2>
            @if(!empty($ct['load_set_steps']['mt4']))
                <h3>MT4</h3>
                {!! Str::markdown($ct['load_set_steps']['mt4'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
            @endif
            @if(!empty($ct['load_set_steps']['mt5']))
                <h3>MT5</h3>
                {!! Str::markdown($ct['load_set_steps']['mt5'], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
            @endif
            <hr>
        @endif

        {{-- All string markdown sections --}}
        @foreach($stringSections as $key => $heading)
            @if(!empty($ct[$key]) && is_string($ct[$key]))
                <h2>{{ $heading }}</h2>
                {!! Str::markdown($ct[$key], ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                <hr>
            @endif
        @endforeach

        {{-- FAQ --}}
        @if(!empty($ct['faq']) && is_array($ct['faq']))
            <h2>Frequently Asked Questions</h2>
            @foreach($ct['faq'] as $item)
                <h3>{{ $item['question'] ?? '' }}</h3>
                <p>{{ $item['answer'] ?? '' }}</p>
            @endforeach
        @endif

    </div>

@else

    {{-- Fallback: pretty JSON --}}
    <pre class="text-xs text-gray-700 dark:text-gray-300 overflow-auto rounded-lg bg-gray-100 dark:bg-gray-800 p-4 leading-relaxed">{{ json_encode($c, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

@endif
