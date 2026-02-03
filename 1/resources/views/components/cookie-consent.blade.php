<div id="cookie-consent" class="fixed inset-x-0 bottom-0 z-50 hidden">
    <div class="mx-auto mb-4 max-w-5xl rounded-xl border border-gray-200 bg-white p-4 shadow-lg sm:mb-6 sm:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="max-w-3xl text-sm text-gray-700">
                We use cookies to enhance your experience, analyze traffic, and for marketing. You can accept all, reject non-essential, or customize your preferences. See our
                <a href="{{ route('home.cookies') }}" class="text-blue-600 underline">Cookie Policy</a>
                and
                <a href="{{ route('home.privacy') }}" class="text-blue-600 underline">Privacy Policy</a>.
            </div>
            <div class="flex flex-wrap gap-2">
                <button id="cc-manage" class="rounded-md border border-gray-300 px-3 py-2 text-sm">Customize</button>
                <button id="cc-reject" class="rounded-md border border-gray-300 px-3 py-2 text-sm">Reject non-essential</button>
                <button id="cc-accept" class="rounded-md bg-blue-600 px-3 py-2 text-sm text-white">Accept all</button>
            </div>
        </div>
    </div>

    <!-- Preferences Modal -->
    <div id="cc-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
        <div class="w-full max-w-lg rounded-lg bg-white p-5 shadow-xl">
            <div class="mb-4 text-lg font-semibold">Cookie Preferences</div>
            <div class="space-y-3 text-sm text-gray-700">
                <label class="flex items-start gap-3">
                    <input type="checkbox" checked disabled class="mt-1" />
                    <span>
                        <span class="font-medium">Necessary</span> – Required for the site to function and cannot be disabled.
                    </span>
                </label>
                <label class="flex items-start gap-3">
                    <input id="cc-functional" type="checkbox" class="mt-1" />
                    <span>
                        <span class="font-medium">Functional</span> – Remember choices to improve your experience.
                    </span>
                </label>
                <label class="flex items-start gap-3">
                    <input id="cc-analytics" type="checkbox" class="mt-1" />
                    <span>
                        <span class="font-medium">Analytics</span> – Help us understand how the site is used.
                    </span>
                </label>
                <label class="flex items-start gap-3">
                    <input id="cc-marketing" type="checkbox" class="mt-1" />
                    <span>
                        <span class="font-medium">Marketing</span> – Personalize content and ads.
                    </span>
                </label>
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button id="cc-cancel" class="rounded-md border border-gray-300 px-3 py-2 text-sm">Cancel</button>
                <button id="cc-save" class="rounded-md bg-blue-600 px-3 py-2 text-sm text-white">Save preferences</button>
            </div>
        </div>
    </div>
</div>
